<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int     $id
 * @property int     $user
 * @property int     $enterprise
 * @property int     $created_at
 * @property int     $deleted_at
 * @property int     $updated_at
 * @property boolean $interim
 */
class AuthorisationRq extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'authorisation_rq';

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
        'user', 'enterprise', 'created_at', 'deleted_at', 'interim', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'int', 'user' => 'int', 'enterprise' => 'int', 'created_at' => 'datetime:Y-m-d H:i', 'deleted_at' => 'timestamp', 'interim' => 'boolean', 'updated_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'deleted_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...
    protected static function booted()
    {
        static::addGlobalScope(new YearScope());
    }

    // Relations ...
    public function user()
    {
        return $this->belongsTo(Users::class, 'user');
    }
}
