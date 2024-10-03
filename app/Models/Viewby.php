<?php
namespace App\Models;

use Carbon\Carbon;

class Viewby
{
    public $matricule;
    public $view_at = null;

    public function __construct($matricule)
    {
        $this->matricule = $matricule;
        $this->view_at = Carbon::now();
    }
    public function getViews(Task $task): array
    {
        if ($task->view_by != null) {
            return json_decode($task->view_by, true);
        } else {
            return [];
        }
    }
    // Function to update an item of type Invite in the JSON array and save it
    public function updateViewByMatricule(Task $task, $viewObject): Task
    {
        $views = json_decode($task->view_by, true);

        foreach ($views as $key => &$viewData) {
            if ($viewData['matricule'] == $viewObject->matricule) {
                $views[$key] = $viewObject;
                break;
            }
        }
        // Use json_encode with JSON_UNESCAPED_UNICODE to prevent casting special characters
        $task->view_by = json_encode($views, JSON_UNESCAPED_UNICODE);
        return $task;
    }

    // Function to find an invite by matricule
    public function findViewByMatricule(Task $task, $matricule)
    {
        $views = $this->getViews($task);

        foreach ($views as $view) {
            if ($view->matricule == $matricule) {
                return $view;
            }
        }

        return null;
    }
}
