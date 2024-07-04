<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{ use SoftDeletes;
    use HasFactory;
    protected $appends = ["open"]; 
    public function getOpenAttribute(){        return true;    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'task');
    }
}
