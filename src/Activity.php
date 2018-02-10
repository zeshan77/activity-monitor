<?php

namespace Zeshan77\ActivityMonitor;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id',
        'type',
        'subject_type',
        'old',
        'new',
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * @param $user
     * @param int $take
     * @return mixed
     */
    public static function feed($user, $take = 50)
    {
        return self::where('user_id', $user->id)->with('subject')->latest()->take($take)->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }

}
