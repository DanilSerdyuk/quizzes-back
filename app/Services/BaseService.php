<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseService
{
    /**
     * @param string     $model
     * @param int|string $param
     * @param string     $key
     *
     * @return Model
     */
    protected function checkOnExist(string $model, int|string $param, string $key = 'id'): Model
    {
        /** @var Model $model */
        if (!$entity = $model::query()->where([$key => $param])->first()) {
            throw (new ModelNotFoundException())->setModel(class_basename($model), $param);
        }

        return $entity;
    }
}
