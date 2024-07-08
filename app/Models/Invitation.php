<?php

namespace App\Models;

use App\Casts\ParticipationCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    $id
 * @property int    $created_at
 * @property int    $closed_at
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
 * @property mixed  $participation
 */
class Invitation extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitation';
    // Store the original attributes before updating
    protected $originalAttributes = [];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->originalAttributes = $this->attributesToArray();
    }
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
        'begin', 'closed_at', 'created_at', 'dates', 'deleted_at', 'description', 'dysfonction', 'end', 'external_invites', 'internal_invites', 'link', 'motif', 'object', 'place', 'rq', 'participation'
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
        'id' => 'int', 'begin' => 'string', 'created_at' => 'timestamp', 'closed_at' => 'datetime:Y-m-d H:i', 'dates' => 'datetime:Y-m-d H:i', 'deleted_at' => 'timestamp', 'description' => 'string', 'dysfonction' => 'int', 'end' => 'string', 'link' => 'string', 'motif' => 'string', 'object' => 'string', 'place' => 'string', 'rq' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'closed_at', 'dates', 'deleted_at',
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
        $invites = $this->internal_invites == null ? [] : json_decode($this->internal_invites, true);
        $inviteObjects = [];

        foreach ($invites as $inviteData) {
            $inviteObjects[] = new Invites(null, $inviteData);
        }

        return $inviteObjects;
    }
    // Function to get the JSON array into a Laravel array of Participants
    public function getParticipants()
    {
        $participants = $this->participation == null ? [] : json_decode($this->participation, true);
        $participantObjects = [];

        foreach ($participants as $participantData) {
            $participantObjects[] = new Participation([
                'matricule' => $participantData['matricule'],
                'names' => $participantData['names'],
                'marked_by' => $participantData['marked_by'],
                'marked_matricule' => $participantData['marked_matricule'],
                'created_at' => $participantData['created_at'],
            ]);
        }

        return $participantObjects;
    }
    // Function to update an item of type Invite in the JSON array and save it
    public function updateInviteByMatricule($inviteObject)
    {
        $invites = json_decode($this->internal_invites, true);
        $found = false;

        foreach ($invites as $key => &$inviteData) {
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

    // Function to find a participant by matricule
    public function findParticipantByMatricule($matricule)
    {
        $participants = $this->getParticipants();

        foreach ($participants as $participant) {
            if ($participant->matricule == $matricule) {
                return $participant;
            }
        }

        return null;
    }
    public function getUpdateMessage()
    {
        $messages = '
            <p style="text-align:justify" class="x_MsoNormal">
        <span
            style="font-family:&quot;Century Gothic&quot;,sans-serif">Nous souhaitons vous informer qu\'une réunion de résolution d\'incidents a été programmée. Nous avons récemment apporté quelques mises à jour importantes à l\'ordre du jour et au contenu de la réunion.
            : <span style="background-color: yellow">{{$invitation->motif}}</span>. Cette
            réunion concerne le dysfonctionnement No.
            <b>'.$this->dysfunction->code.'</b> dont la gravité a été noté :
            <b>'.$this->dysfunction->gravity.'</b></span></p>
            <p style="text-align:justify" class="x_MsoNormal">
        <span
            style="font-family:&quot;Century Gothic&quot;,sans-serif">Détails
            de la réunion :</span></p>
            <ul style="margin-top:0cm" type="disc">
                <li style="margin-left:0cm; text-align:justify"
                    class="x_MsoListParagraphCxSpFirst">';

        // Check each attribute for changes and add the corresponding message
        if ($this->isDirty('dates')) {
            $messages .= '
            <span style="font-family:&quot;Century Gothic&quot;,sans-serif">Date
                : '.$this->odates->locale('fr')->isoFormat('dddd, D MMMM YYYY').'</span>';
        }

        if ($this->isDirty('dysfonction') || $this->isDirty('meeting_link')) {
            $messages[] = 'The meeting place or link has been updated.';
        }

        if ($this->isDirty('begin')) {
            $messages[] = 'The meeting object has been updated.';
        }

        if ($this->isDirty('end')) {
            $messages[] = 'The meeting object has been updated.';
        }

        if ($this->isDirty('description')) {
            $messages[] = 'The meeting concern has been updated.';
        }

        if ($this->isDirty('link')) {
            $messages[] = 'The meeting object has been updated.';
        }

        if ($this->isDirty('motif')) {
            $messages[] = 'The meeting object has been updated.';
        }

        if ($this->isDirty('object')) {
            $messages[] = 'The meeting object has been updated.';
        }

        if ($this->isDirty('place')) {
            $messages[] = 'The meeting object has been updated.';
        }

        // Special case for meeting cancellation
        if ($this->isDirty('status') && $this->status == 'cancelled') {
            return 'The meeting has been cancelled.';
        }

        // Generate the global message if multiple changes
        if (count($messages) > 1) {
            return 'Multiple changes have been made to the meeting: ' . implode(' ', $messages);
        }

        return count($messages) > 0 ? implode(' ', $messages) : null;
    }

    public function getParticipantMessage($action)
    {
        switch ($action) {
            case 'removed':
                return 'You have been removed from the meeting.';
            case 'joined':
                return 'You have joined the meeting.';
            default:
                return null;
        }
    }

    public function save(array $options = [])
    {
        $this->originalAttributes = $this->getOriginal();
        parent::save($options);
    }

    public function isDirty($attribute = null)
    {
        return $this->getOriginal($attribute) !== $this->getAttribute($attribute);
    }
    // Relations ...
    public function dysfunction()
    {
        return $this->belongsTo(Dysfunction::class, 'dysfonction');
    }
}
