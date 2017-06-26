<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Action;
use App\Models\Media;
use App\Traits\Common\UploadableTrait;
use App\Repositories\Contracts\ActionInterface;

class ActionRepository extends BaseRepository implements ActionInterface
{
    use UploadableTrait;

    public function model()
    {
        return Action::class;
    }

    public function createOrDeleteLike($action, $userId)
    {
        if (!is_numeric($userId) || !$action) {
            return false;
        }

        if ($action->likes->where('user_id', $userId)->isEmpty()) {
            return $this->createByRelationship('likes', [
                'model' => $action,
                'attribute' => ['user_id' => $userId],
            ]);
        }

        return $action->likes()->where('user_id', $userId)->first()->forceDelete();
    }

    public function update($action, $inputs)
    {
        if (!empty($inputs['upload'])) {
            $result = $this->makeDataMedias($inputs['upload']);
            $action->media()->createMany($result);
        }

        return parent::update($action->id, $inputs['data_action']);
    }
}