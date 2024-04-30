<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $created_at
 * @property int $dates
 * @property int $holliday
 * @property int $pme
 * @property int $pne
 */
class PublicHolliday extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'public_holliday';

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
        'created_at', 'dates', 'holliday', 'pme', 'pne'
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
        'id' => 'int', 'created_at' => 'timestamp', 'dates' => 'timestamp', 'holliday' => 'int', 'pme' => 'int', 'pne' => 'int'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'dates'
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
