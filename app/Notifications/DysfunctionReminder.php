<?php

namespace App\Notifications;

use App\Models\ApiMail;
use App\Models\AuthorisationRq;
use App\Models\Dysfunction;
use App\Models\Task;
use App\Models\Users;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class DysfunctionReminder extends Notification
{
    use Queueable;
    protected Dysfunction $dys;
    /**
     * Create a new notification instance.
     */
    public function __construct(Dysfunction $dysfunction)
    {
        $this->dys = $dysfunction;
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
        // Get the longest task
        $task = Task::select(
            'tasks.dysfunction',
            'tasks.start_date',
            'tasks.duration',
            DB::raw('DATE_ADD(tasks.start_date, INTERVAL tasks.duration DAY) AS end')
        )
            ->join('dysfunction', 'dysfunction.id', '=', 'tasks.dysfunction') // Adjust 'dysfunction_id' as necessary
            ->where('dysfunction.id' , $this->dys->id)
            ->orderByDesc('end')
            ->first();
        $content = view('employees.dysfunction_reminder', ['task' => $task, 'dysfunction' => $this->dys])->render();
        //rq emails
        $rqU = AuthorisationRq::where('enterprise', $this->dys->enterprise_id)->get();
        $rq = Users::whereIn('id', $rqU->pluck('user'))->where('role', '<>', 1)->get();
        $newmail = new ApiMail(null, $rq->pluck('email')->unique()->toArray(), 'Cadyst PRD App', "Rappel d'Evaluation de Dysfonctionnement No. " . $this->dys->code, $content, []);
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
