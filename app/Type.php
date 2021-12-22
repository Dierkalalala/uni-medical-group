<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Type extends Model
{
    public function nurseries() {
        return $this->belongsToMany(Nursery::class);
    }
}
