<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $begin
 * @property int    $created_at
 * @property int    $deleted_at
 * @property int    $department
 * @property int    $duration
 * @property int    $end
 * @property int    $enterprise
 * @property int    $status
 * @property int    $substitution
 * @property int    $type
 * @property int    $user
 * @property string $description
 * @property string $email
 * @property string $matricule
 * @property string $name
 * @property string $pj
 */
class Holliday extends Model
{use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'holliday';

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
        'begin', 'created_at', 'deleted_at', 'department', 'description', 'duration', 'email', 'end', 'enterprise', 'matricule', 'name', 'pj', 'status', 'substitution', 'type', 'user'
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
        'id' => 'int', 'begin' => 'timestamp', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp', 'department' => 'int', 'description' => 'string', 'duration' => 'int', 'email' => 'string', 'end' => 'timestamp', 'enterprise' => 'int', 'matricule' => 'string', 'name' => 'string', 'pj' => 'string', 'status' => 'int', 'substitution' => 'int', 'type' => 'int', 'user' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'begin', 'created_at', 'deleted_at', 'end'
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
