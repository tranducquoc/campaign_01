<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Carbon\Carbon;

class Donation extends BaseModel
{
    use SoftDeletes, SearchableTrait;

    // Donation's status
    const ACCEPT = 1;
    const NOT_ACCEPT = 0;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id',
        'user_id',
        'event_id',
        'value',
        'campaign_id',
        'goal_id',
        'status',
        'note',
        'recipient_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donor_address',
        'created_at',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['donated_at'];

    protected $searchable = [
        'columns' => [
            'donations.donor_name' => 10,
            'donations.donor_email' => 10,
            'users.name' => 10,
            'users.email' => 10,
        ],
        'joins' => [
            'users' => [ 'users.id', 'donations.user_id' ],
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function getDonatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
