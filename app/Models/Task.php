<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{ use SoftDeletes;
    use HasFactory;
    use Notifiable;
    protected $appends = ["open"];
    public function getOpenAttribute(){        return true;    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'task');
    }
    //Functions
        protected static function booted()
    {
        static::addGlobalScope(new YearScope());
    }
}
