<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user
 * @property int $processus
 * @property int $created_at
 * @property int $deleted_at
 * @property int $interim
 */
class AuthorisationPilote extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'authorisation_pilote';

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
        'user', 'processus', 'created_at', 'deleted_at', 'interim'
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
        'id' => 'int', 'user' => 'int', 'processus' => 'int', 'created_at' => 'timestamp', 'deleted_at' => 'timestamp', 'interim' => 'int'
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
