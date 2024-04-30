<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $deleted_at
 * @property int    $holliday
 * @property int    $pme
 * @property int    $pne
 * @property int    $status
 * @property int    $validator
 * @property string $reasons
 */
class Validation extends Model
{use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'validation';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at', 'deleted_at', 'holliday', 'pme', 'pne', 'reasons', 'status', 'validator'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp', 'holliday' => 'int', 'pme' => 'int', 'pne' => 'int', 'reasons' => 'string', 'status' => 'int', 'validator' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'deleted_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
