<?php

namespace App\Casts;

use App\Models\Participation;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Models\Receipt;
use Illuminate\Support\Collection;

class ParticipationCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        $receiptsArray = json_decode($attributes[$key], true);
        return collect(array_map(function ($receiptData) {
            return new Participation($receiptData);
        }, $receiptsArray));
    }

    public function set($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return json_encode($value->toArray());
    }
}
