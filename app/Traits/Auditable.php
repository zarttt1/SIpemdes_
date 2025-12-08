<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the auditable trait for a model
     */
    public static function bootAuditable()
    {
        static::creating(function ($model) {
            AuditLog::log('create', class_basename($model), null, null, $model->getAttributes());
        });

        static::updating(function ($model) {
            $oldValues = $model->getOriginal();
            $newValues = $model->getChanges();

            if (!empty($newValues)) {
                AuditLog::log('update', class_basename($model), $model->getKey(), $oldValues, $newValues);
            }
        });

        static::deleting(function ($model) {
            AuditLog::log('delete', class_basename($model), $model->getKey(), $model->getAttributes(), null);
        });
    }
}
