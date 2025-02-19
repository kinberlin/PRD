<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property integer $id
 * @property integer $type
 * @property integer $solved
 * @property integer $cost
 * @property integer $closed_at

 * @property int $enterprise_id
 * @property int $site_id
 * @property int $gravity_id
 * @property int $origin
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
    use Notifiable;
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

    protected $fillable = ['enterprise', 'enterprise_id', 'site', 'site_id', 'emp_signaling', 'emp_matricule', 'emp_email', 'code', 'description', 'concern_processes', 'impact_processes', 'gravity', 'gravity_id', 'probability', 'corrective_acts', 'status', 'progression', 'pj', 'created_at', 'deleted_at', 'occur_date', 'cause', 'rej_reasons', 'type', 'solved', 'cost', 'satisfaction_description', 'closed_by', 'closed_at', 'origin'];
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i',
        'occur_date' => 'datetime:d-m-Y H:i',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'occur_date',
    ];
    //Functions
    protected static function booted()
    {
        static::addGlobalScope(new YearScope(session('currentYear')));
    }
    public function getCProcesses(): Collection
    {
        $processes = $this->concern_processes == null ? [] : json_decode($this->concern_processes, true);
        $processObjects = [];

        foreach ($processes as $processData) {
            $processObjects[] = Processes::where('name', $processData)->get()->first();
        }

        return collect($processObjects);
    }
    public function getIProcesses(): Collection
    {
        $processes = $this->impact_processes == null ? [] : json_decode($this->impact_processes, true);
        $processObjects = [];

        foreach ($processes as $processData) {
            $processObjects[] = Processes::where('name', $processData)->get()->first();
        }

        return collect($processObjects);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'dysfunction');
    }
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'dysfonction');
    }
    // Define the relationship with the Gravity model
    public function gravities()
    {
        return $this->belongsTo(Gravity::class, 'id', 'gravity_id');
    }

    // Define the relationship with the Origin model
    public function origins()
    {
        return $this->belongsTo(Origin::class, 'origin');
    }
    // Define the relationship with the Enterprise model
    public function enterprises()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise_id');
    }
    // Define the relationship with the Probability model
    public function probabilities()
    {
        return $this->belongsTo(Probability::class, 'probability');
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'emp_matricule', 'matricule');
    }
    // Define the relationship with the Site model
    public function site_id()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
    // Define the relationship with the Site model
    public function status_id()
    {
        return $this->belongsTo(Status::class, 'status');
    }
}
