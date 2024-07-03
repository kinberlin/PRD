<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int    $id
 * @property int    $access
 * @property int    $created_at
 * @property int    $deleted_at
 * @property int    $department
 * @property int    $email_verified_at
 * @property int    $enterprise
 * @property int    $holiday
 * @property int    $role
 * @property string $email
 * @property string $firstname
 * @property string $image
 * @property string $lastname
 * @property string $matricule
 * @property string $password
 * @property string $phone
 * @property string $poste
 * @property string $remember_token
 */
class Users extends  Authenticatable
{use SoftDeletes;
    use \Illuminate\Auth\Authenticatable, Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'access', 'created_at', 'deleted_at', 'department', 'email', 'email_verified_at', 'enterprise', 'firstname', 'holiday', 'image', 'lastname', 'matricule', 'password', 'phone', 'poste', 'remember_token', 'role'
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
        'id' => 'int', 'access' => 'int', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp', 'department' => 'int', 'email' => 'string', 'email_verified_at' => 'timestamp', 'enterprise' => 'int', 'firstname' => 'string', 'holiday' => 'int', 'image' => 'string', 'lastname' => 'string', 'matricule' => 'string', 'password' => 'string', 'phone' => 'string', 'poste' => 'string', 'remember_token' => 'string', 'role' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'deleted_at', 'email_verified_at'
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
    public function department()
    {
        return $this->belongsTo(Department::class, 'department');
    }
    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'enterprise');
    }
    public function authorisationRqs()
    {
        return $this->hasMany(AuthorisationRq::class, 'user');
    }
    public function authorisationPilotes()
    {
        return $this->hasMany(AuthorisationPilote::class, 'user');
    }
    public function dysfunctions()
    {
        return $this->hasMany(Dysfunction::class, 'emp_matricule', 'matricule');
    }
}
