<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $dates
 * @property int    $deleted_at
 * @property int    $duration
 * @property int    $dysfonction
 * @property string $description
 * @property string $link
 * @property string $motif
 * @property string $object
 * @property string $place
 * @property string $rq
 */
class Invitation extends Model
{use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitation';

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
        'created_at', 'dates', 'deleted_at', 'description', 'duration', 'dysfonction', 'link', 'motif', 'object', 'place', 'rq'
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
        'id' => 'int', 'created_at' => 'timestamp', 'dates' => 'timestamp', 'deleted_at' => 'timestamp', 'description' => 'string', 'duration' => 'int', 'dysfonction' => 'int', 'link' => 'string', 'motif' => 'string', 'object' => 'string', 'place' => 'string', 'rq' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'dates', 'deleted_at'
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
