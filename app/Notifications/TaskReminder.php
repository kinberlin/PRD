<?php

namespace App\Notifications;

use App\Models\ApiMail;
use App\Models\AuthorisationRq;
use App\Models\Dysfunction;
use App\Models\Processes;
use App\Models\Task;
use App\Models\Users;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminder extends Notification
{
    use Queueable;
    protected Task $task;
    protected Dysfunction $dysfunction;
    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->dysfunction = Dysfunction::find($task->dysfunction);
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
                //rq emails
        if(is_null($this->dysfunction)){
            throw new Exception("Dysfunction Ressource Not Found", 501);
        }
        $rqU = AuthorisationRq::where('enterprise', $this->dysfunction->enterprise_id)->get();
        $rq = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
        $content = view('employees.task_reminder', ['invitation' => $this->task, 'dysfunction' => $this->dysfunction, 'process' => Processes::find($this->task->process)])->render();

        $newmail = new ApiMail(null, $rq->pluck('email')->toArray(), 'Cadyst PRD App', "Rappel sur le délai de livraison d'action corrective intitulée : ".$this->task->text. ".", $content, []);
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
