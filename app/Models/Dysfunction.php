<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $type
 * @property integer $solved
 * @property integer $cost
 * @property integer $closed_at
 * @property int $created_at
 * @property string $enterprise
 * @property string $site
 * @property string $emp_signaling
 * @property string $emp_matricule
 * @property string $emp_email
 * @property string $code
 * @property string $description
 * @property mixed $concern_processes
 * @property mixed $impact_processes
 * @property string $gravity
 * @property integer $probability
 * @property mixed $corrective_acts
 * @property mixed $invitations
 * @property integer $status
 * @property integer $progression
 * @property mixed $pj
 * @property string $deleted_at
 * @property string $occur_date
 * @property string $cause
 * @property string $rej_reason
 * @property string $closed_by
 * @property string $satisfaction_description
 */
class Dysfunction extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
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
     * @var array
     */

    protected $fillable = ['enterprise', 'site', 'emp_signaling', 'emp_matricule', 'emp_email', 'code', 'description', 'concern_processes', 'impact_processes', 'gravity', 'probability', 'corrective_acts', 'invitations', 'status', 'progression', 'pj', 'created_at', 'deleted_at', 'occur_date', 'cause', 'rej_reasons', 'type', 'solved', 'cost', 'satisfaction_description', 'closed_by', 'closed_at'];
    public $timestamps = false;
    protected $casts = [
       'created_at' => 'datetime:d-m-Y H:i'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class, 'dysfunction', 'id');
    }
}
