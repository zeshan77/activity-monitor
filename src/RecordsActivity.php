<?php

namespace Zeshan77\ActivityMonitor;

trait RecordsActivity
{

    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach (self::getEventsToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleted(function($model) {
            $model->activity()->delete();
        });

    }

    protected static function getEventsToRecord()
    {
        return ['created', 'updated'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        $class = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$class}";
    }

}