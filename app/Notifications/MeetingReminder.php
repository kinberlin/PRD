<?php

namespace App\Notifications;

use App\Models\ApiMail;
use App\Models\Dysfunction;
use App\Models\Invitation;
use App\Models\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingReminder extends Notification
{
    use Queueable;
    protected Invitation $invitation;
    /**
     * Create a new notification instance.
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        $content = view('employees.invitation_reminder', ['invitation' => $this->invitation, 'dysfunction' => Dysfunction::find($this->invitation->dysfonction)])->render();
        //invites
        $allinvite = collect($this->invitation->getInternalInvites());
        $internal = Users::whereIn('matricule', $allinvite->pluck('matricule')->unique())->get();
        $external = [];
        foreach ($allinvite as $u) {
            if(is_null($internal->where('matricule', $u->matricule))){
                $external[] = $u->matricule;
            }
        }
        $newmail = new ApiMail(null, array_merge($internal->pluck('email')->unique()->toArray(), $external), 'Cadyst PRD App', "Rappel d'Invitation à la réunion.", $content, []);
        $newmail->send();
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
