<?php

namespace App\Http\Repositories;

use AllowDynamicProperties;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = new ($this->getModelName())();
    }

    abstract protected function getModelName(): string;
}
