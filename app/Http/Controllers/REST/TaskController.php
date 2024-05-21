<?php

namespace App\Http\Controllers\REST;

use App\Models\Dysfunction;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;

class TaskController extends RoutingController
{
    public function store(Request $request)
    {
        $task = new Task();
        $dyst = Dysfunction::find($request->dysfunction);
        if ($dyst == null) {
            throw new Exception('Ce dysfonctionnement est introuvable: ' . $request->dysfunction, 404);
        }
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
        $task->process = $request->has('process') ? $request->process : null;
        $task->sortorder = Task::max("sortorder") + 1;
        $task->description = $request->has('description') ? $request->description : null;
        $task->unscheduled = $request->unscheduled == "true" ? 1 : 0;
        $task->dysfunction = $request->has('dysfunction') ? $request->dysfunction : $task->dysfunction;
        $task->created_by = 'Demo User';

        $task->save();

        return response()->json([
            "action" => "inserted",
            "tid" => $task->id,
        ]);
    }

    public function update($id, Request $request)
    {
        $task = Task::find($id);
        $dyst = Dysfunction::find($request->dysfunction);
        if ($dyst == null) {
            throw new Exception('Ce dysfonctionnement est introuvable : ' . $request->dysfunction, 404);
        }
        $task->text = $request->text;
        $task->start_date = $request->start_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress : 0;
        $task->parent = $request->parent;
        $task->process = $request->has('process') ? $request->process : $task->process;
        $task->description = $request->has('description') ? $request->description : null;
        $task->unscheduled = $request->unscheduled == "true" ? 1 : 0;
        $task->dysfunction = $request->has('dysfunction') ? $request->dysfunction : $task->dysfunction;
        $task->open = $request->open == "true" ? 1 : 0;

        $task->save();

        if ($request->has("target")) {$this->updateOrder($id, $request->target);}

        return response()->json([
            "action" => "updated",
        ]);
    }

    private function updateOrder($taskId, $target)
    {
        $nextTask = false;
        $targetId = $target;

        if (strpos($target, "next:") === 0) {
            $targetId = substr($target, strlen("next:"));
            $nextTask = true;
        }

        if ($targetId == "null") {
            return;
        }

        $targetOrder = Task::find($targetId)->sortorder;
        if ($nextTask) {
            $targetOrder++;
        }

        Task::where("sortorder", ">=", $targetOrder)->increment("sortorder");

        $updatedTask = Task::find($taskId);
        $updatedTask->sortorder = $targetOrder;
        $updatedTask->save();
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted",
        ]);
    }

    public function proof(Request $request)
    {
        try {
            $task = Task::find($request->task_id);
            $url = null;
            $pj = $request->hasFile('file') ? $request->file : null;
            if ($pj == null) {
                throw new Exception("Nous ne trouvons pas le fichier joint", 500);
            }
            if ($pj->isValid()) {
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/tasks'), $filename);
                $url = asset('/uploads/tasks/' . $filename);
            }
            if ($url == null) {
                throw new Exception("IMpossible de récuperer l'URL de la ressource", 501);
            }
            $task->url = $url;
            $task->save();
            return response()->json([
                "action" => "updated",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "action" => 'Erreur ' . $th->getMessage(),
            ]);
        }
    }
}
