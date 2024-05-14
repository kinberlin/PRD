<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $dates
 * @property int    $deleted_at
 * @property int    $dysfonction
 * @property string $begin
 * @property string $description
 * @property string $end
 * @property string $link
 * @property string $motif
 * @property string $object
 * @property string $place
 * @property string $rq
 * @property mixed  $internal_invites
 * @property mixed  $external_invites
 */
class Invitation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitation';

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
        'begin', 'created_at', 'dates', 'deleted_at', 'description', 'dysfonction', 'end', 'external_invites', 'internal_invites', 'link', 'motif', 'object', 'place', 'rq','internal_invites', 'external_invites'
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
        'id' => 'int', 'begin' => 'string', 'created_at' => 'timestamp', 'dates' => 'timestamp', 'deleted_at' => 'timestamp', 'description' => 'string', 'dysfonction' => 'int', 'end' => 'string', 'link' => 'string', 'motif' => 'string', 'object' => 'string', 'place' => 'string', 'rq' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'dates', 'deleted_at',
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
