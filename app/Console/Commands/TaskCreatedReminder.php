<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskCreated;
use App\Scopes\YearScope;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TaskCreatedReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:task-created-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Mails When Task is created since almost 5 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current datetime
        $current = Carbon::now();
        $parentTasks = Task::withoutGlobalScope(YearScope::class)
            ->select('tasks.id', 'tasks.text')
            ->distinct()
            ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
            ->whereYear('tasks.created_at', session('currentYear'))
            ->get();

        $tasks = Task::withoutGlobalScope(YearScope::class)->whereNotIn('id', $parentTasks->pluck('id')->unique())
            ->get();
        foreach ($tasks as $task) {
            $diff = $current->diffInDays(Carbon::parse($task->start_date)->addDay($task->duration));
            if ($diff < 8 && $diff % 2 == 0) {
                $task->notify(new TaskCreated($task));
            }
            if ($diff == 1) {
                $task->notify(new TaskCreated($task));
            }
        }
    }
}
