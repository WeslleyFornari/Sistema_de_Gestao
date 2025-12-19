<?php

namespace App\Traits;

use App\Models\Auditoria;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            static::registrarAuditoria($model, 'created');
        });

        static::updated(function ($model) {
            if ($model->wasChanged()) {
                static::registrarAuditoria($model, 'updated');
            }
        });

        static::deleted(function ($model) {
            static::registrarAuditoria($model, 'deleted');
        });
    }

    protected static function registrarAuditoria($model, $event)
    {
        Auditoria::create([
            'model'          => get_class($model),
            'auditable_id'   => $model->id,
            'event'          => $event,
            'old_values'     => $event === 'updated' 
                ? array_intersect_key($model->getOriginal(), $model->getChanges()) 
                : ($event === 'deleted' ? $model->getOriginal() : null),
            'new_values'     => $event === 'updated' 
                ? $model->getChanges() 
                : ($event === 'created' ? $model->getAttributes() : null),
            'colaborador_id' => auth()->id(),
        ]);
    }
}