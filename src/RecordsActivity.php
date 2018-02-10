<?php

namespace Zeshan77\ActivityMonitor;

trait RecordsActivity
{
    protected $dontLogFields = ['created_at', 'updated_at', 'id'];

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

        $new = $this->getDirty();
        $old = [];
        foreach ($this->getDirty() as $field => $value) {
            $old[$field] = $this->getOriginal($field);
        }

        if($this->dontLogFields) {
            foreach ($this->dontLogFields as $field) {
                if (array_key_exists($field, $old)) unset($old[$field]);
                if (array_key_exists($field, $new)) unset($new[$field]);
            }
        }

        $new = json_encode($new);
        $old = json_encode($old);
        
        if(strpos($this->getActivityType($event), 'created') !== false) {
            $old = null;
        }
        
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
            'old' => $old,
            'new' => $new
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
