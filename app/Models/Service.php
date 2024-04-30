<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $createdat
 * @property int    $deleted_at
 * @property int    $department
 * @property int    $enterprise
 * @property int    $level
 * @property int    $manager
 * @property int    $parent
 * @property int    $vice_manager
 * @property string $name
 */
class Service extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'service';

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
        'createdat', 'deleted_at', 'department', 'enterprise', 'level', 'manager', 'name', 'parent', 'vice_manager'
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
        'id' => 'int', 'createdat' => 'timestamp', 'deleted_at' => 'int', 'department' => 'int', 'enterprise' => 'int', 'level' => 'int', 'manager' => 'int', 'name' => 'string', 'parent' => 'int', 'vice_manager' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'createdat'
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
