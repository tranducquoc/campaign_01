<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequest;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\DonationInterface;
use App\Notifications\AcceptDonation;
use App\Notifications\UserDonate;
use Notification;

class DonationController extends ApiController
{
    protected $eventRepository;
    protected $donationRepository;

    public function __construct(
        DonationInterface $donationRepository,
        EventInterface $eventRepository
    ) {
        parent::__construct();
        $this->donationRepository = $donationRepository;
        $this->eventRepository = $eventRepository;
    }

    public function createMany(Request $request)
    {
        $event = $this->eventRepository->findOrFail($request->get('event_id'));
        $donateData = [];

        foreach ($request->get('goal_id') as $key => $value) {
            $donateData[$key]['goal_id'] = $value;
            $donateData[$key]['value'] = $request->get('value')[$key];
            $donateData[$key]['user_id'] = $request->has('donor_name') ? null : $request->get('user_id', $this->user->id);
            $donateData[$key]['status'] = (bool) ($request->has('status') ? $request->get('status')[$key] : Donation::NOT_ACCEPT);
            $donateData[$key]['campaign_id'] = $event->campaign_id;
            $donateData[$key]['donor_name'] = $request->get('donor_name');
            $donateData[$key]['donor_email'] = $request->get('donor_email');
            $donateData[$key]['donor_phone'] = $request->get('donor_phone');
            $donateData[$key]['donor_address'] = $request->get('donor_address');
            $donateData[$key]['recipient_id'] = $request->get('recipient_id');
            $donateData[$key]['note'] = $request->get('note');
            $donateData[$key]['created_at'] = $request->get('donated_at')[$key];
        }

        return $this->doAction(function () use ($event, $donateData) {
            $event->donations()->createMany($donateData);
            $this->compacts['donations'] = $event
                ->goals()
                ->select('id', 'donation_type_id', 'goal')
                ->with([
                    'donations' => function ($query) {
                        return $query->with('user')->latest();
                    },
                    'donationType.quality',
                ])
                ->get();

            if ($event->user_id != $this->user->id) {
                Notification::send($event->user, new UserDonate($this->user->id, $event->id));
                $this->sendNotification(
                    $event->user_id,
                    $event,
                    UserDonate::class,
                    config('settings.type_notification.event')
                );
            }
        });
    }

    public function store(DonationRequest $request)
    {
        $input = $request->only('event_id', 'goal_id', 'value');
        $event = $this->eventRepository->findOrFail($input['event_id']);
        $input['user_id'] = $this->user->id;
        $input['campaign_id'] = $event->campaign_id;

        if ($this->user->cannot('view', $event)) {
            throw new UnknowException('Permission error: User can not donate.');
        }

        $input['status'] = ($this->user->can('manage', $event))
            ? Donation::ACCEPT
            : Donation::NOT_ACCEPT;

        return $this->doAction(function () use ($input) {
            $this->compacts['donation'] = $this->donationRepository->create($input);
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $donation = $this->donationRepository->findOrFail($id);

        if (!in_array($request->status, [
            Donation::ACCEPT,
            Donation::NOT_ACCEPT,
        ])) {
            throw new UnknowException('Error: Invalid input.');
        }

        if ($this->user->cannot('manage', $donation)) {
            throw new UnknowException('Permission error: User can not change this donation.');
        }

        return $this->doAction(function () use ($request, $donation) {
            $this->compacts['donation'] = $this->donationRepository->update($donation->id, [
                'status' => $request->status,
            ])->load('user');

            if ($donation->user_id && $donation->user_id != $this->user->id) {
                $event = $donation->event;
                Notification::send($donation->user, new AcceptDonation($this->user->id, $event->id));
                $this->sendNotification(
                    $donation->user_id,
                    $event,
                    AcceptDonation::class,
                    config('settings.type_notification.event')
                );
            }
        });
    }

    public function update(Request $request, $id)
    {
        $donation = $this->donationRepository->findOrFail($id);

        $data = $request->only(
            'donor_name',
            'donor_email',
            'donor_address',
            'donor_phone',
            'goal_id',
            'value',
            'status',
            'note'
        );

        $data['status'] = $data['status'] === '' ? Donation::NOT_ACCEPT : $data['status'];

        $data = array_filter($data, function ($value) {
            return $value !== null;
        });

        return $this->doAction(function () use ($data, $donation) {
            $this->authorize('manage', $donation);
            $this->compacts['donation'] = $this->donationRepository
                ->update($donation->id, $data)
                ->load(['goal.donationType.quality', 'user']);
        });
    }

    public function destroy($id)
    {
        $donation = $this->donationRepository->findOrFail($id);

        if ($this->user->cannot('manage', $donation)) {
            throw new UnknowException('Permission error: User can not delete this donation.');
        }

        return $this->doAction(function () use ($donation) {
            $this->compacts['donation'] = $this->donationRepository->delete($donation->id);
        });
    }
}
