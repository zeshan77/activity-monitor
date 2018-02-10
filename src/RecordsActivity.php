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

        $after = $this->getDirty();
        $before = [];
        foreach ($this->getDirty() as $field => $value) {

            $before[$field] = $this->getOriginal($field);

        }

        if($this->dontLogFields) {
            foreach ($this->dontLogFields as $field) {
                if (array_key_exists($field, $before)) unset($before[$field]);
                if (array_key_exists($field, $after)) unset($after[$field]);
            }
        }

        $after = json_encode($after);
        $before = json_encode($before);

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'before' => $before,
            'after' => $after
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
    protected function getActivityType($event)
    {
        $class = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$class}";
    }

}
