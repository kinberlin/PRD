<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $enterprise
 * @property string $site
 * @property string $emp_signaling
 * @property string $emp_matricule
 * @property string $emp_email
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
 * @property string $created_at
 * @property string $deleted_at
 * @property string $occur_date
 */
class Dysfunction extends Model
{
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
    protected $fillable = ['enterprise', 'site', 'emp_signaling', 'emp_matricule', 'emp_email', 'description', 'concern_processes', 'impact_processes', 'gravity', 'probability', 'corrective_acts', 'invitations', 'status', 'progression', 'pj', 'created_at', 'deleted_at', 'occur_date'];
}
