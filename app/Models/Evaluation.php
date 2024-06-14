<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $task
 * @property int    $satisfaction
 * @property int    $completion
 * @property int    $created_at
 * @property string $evaluation_criteria
 */
class Evaluation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'evaluation';

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
        'task', 'satisfaction', 'completion', 'evaluation_criteria', 'created_at'
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
        'id' => 'int', 'task' => 'int', 'satisfaction' => 'int', 'completion' => 'int', 'evaluation_criteria' => 'string', 'created_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
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
