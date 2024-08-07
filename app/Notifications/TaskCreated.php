<?php

namespace App\Notifications;

use App\Models\ApiMail;
use App\Models\ApiSms;
use App\Models\AuthorisationPilote;
use App\Models\Dysfunction;
use App\Models\Processes;
use App\Models\Task;
use App\Models\Users;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskCreated extends Notification
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
        //pilote emails
        if (is_null($this->dysfunction)) {
            throw new Exception("Dysfunction Ressource Not Found", 501);
        }
        $pltu = AuthorisationPilote::where('process', $this->task->process)->get();
        $plt = Users::whereIn('id', $pltu->pluck('user'))->where('role', '<>', 1)->get();
        $content = view('employees.task_created', ['task' => $this->task, 'dysfunction' => $this->dysfunction, 'processes' => Processes::find($this->task->process)])->render();
        $newmail = new ApiMail(null, $plt->pluck('email')->toArray(), 'Cadyst PRD App', "Nouvelle action corrective : " . $this->task->text . ".", $content, []);
        $newmail->send();
        $newmessage = new ApiSms(
            $plt->pluck('phone')->unique()->toArray(),
            'Cadyst PRD App',
            "Nouvelle Action. Vous avez été assigné une nouvelle tâche sur le projet PRD. Il s'agit de la n° : " . $this->task->id . " concernant le dysfonctionnement " . $this->dysfunction->code . ".");
        $newmessage->send();
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
