<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $probability
 * @property int    $status
 * @property int    $progression
 * @property int    $created_at
 * @property int    $deleted_at
 * @property string $enterprise
 * @property string $site
 * @property string $emp_signaling
 * @property string $emp_matricule
 * @property string $emp_email
 * @property string $description
 * @property string $gravity
 */
class Dysfunction extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dysfunction';

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
        'enterprise', 'site', 'emp_signaling', 'emp_matricule', 'emp_email', 'description', 'concern_processes', 'impact_processes', 'gravity', 'probability', 'corrective_acts', 'invitations', 'status', 'progression', 'pj', 'created_at', 'deleted_at'
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
        'id' => 'int', 'enterprise' => 'string', 'site' => 'string', 'emp_signaling' => 'string', 'emp_matricule' => 'string', 'emp_email' => 'string', 'description' => 'string', 'gravity' => 'string', 'probability' => 'int', 'status' => 'int', 'progression' => 'int', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp'
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
