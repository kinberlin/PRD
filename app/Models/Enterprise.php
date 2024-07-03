<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $deleted_at
 * @property int    $manager
 * @property int    $vice-manager
 * @property string $name
 * @property string $surfix
 */
class Enterprise extends Model
{use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enterprise';

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
        'deleted_at', 'manager', 'name', 'vice-manager', 'surfix'
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
        'id' => 'int', 'deleted_at' => 'timestamp', 'manager' => 'int', 'name' => 'string', 'vice-manager' => 'int', 'surfix'=> 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
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
    public function departments() : HasMany
    {
        return $this->hasMany(Department::class, 'enterprise');
    }
    public function users()
    {
        return $this->hasMany(Users::class, 'enterprise');
    }
    public function dysfunctions()
    {
        return $this->hasMany(Dysfunction::class, 'enterprise_id');
    }
    public function sites()
    {
        return $this->hasMany(Site::class, 'enterprise');
    }
    public function authorisationRqs()
    {
        return $this->hasMany(AuthorisationRq::class, 'enterprise');
    }

}
