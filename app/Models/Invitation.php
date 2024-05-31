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
        'begin', 'created_at', 'dates', 'deleted_at', 'description', 'dysfonction', 'end', 'external_invites', 'internal_invites', 'link', 'motif', 'object', 'place', 'rq', 'internal_invites', 'external_invites'
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
        'id' => 'int', 'begin' => 'string', 'created_at' => 'timestamp', 'dates' => 'datetime:Y-m-d H:i', 'deleted_at' => 'timestamp', 'description' => 'string', 'dysfonction' => 'int', 'end' => 'string', 'link' => 'string', 'motif' => 'string', 'object' => 'string', 'place' => 'string', 'rq' => 'string'
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
    // Function to get the JSON array into a Laravel array of Invites
    public function getInternalInvites()
    {
        $invites = json_decode($this->internal_invites, true);
        $inviteObjects = [];

        foreach ($invites as $inviteData) {
            $inviteObjects[] = new Invites(null,$inviteData);
        }

        return $inviteObjects;
    }
    // Function to update an item of type Invite in the JSON array and save it
    public function updateInviteByMatricule($inviteObject)
    {
        $invites = json_decode($this->internal_invites, true);
        $found = false;

        foreach ($invites as $key=>&$inviteData) {
            if ($inviteData['matricule'] == $inviteObject->matricule) {
                $invites[$key] = $inviteObject;
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->internal_invites = json_encode($invites);
            $this->save();
            return $this;
        } else {
            return null;
        }
    }

    // Function to find an invite by matricule
    public function findInviteByMatricule($matricule)
    {
        $invites = $this->getInternalInvites();

        foreach ($invites as $invite) {
            if ($invite->matricule == $matricule) {
                return $invite;
            }
        }

    return null;
    }
    // Relations ...
}
