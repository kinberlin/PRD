<?php

namespace App\Http\Controllers\REST;

use App\Models\Dysfunction;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;
use Throwable;

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
        try {
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
        } catch (Throwable $th) {
            return response()->json([
                "action" => 'Erreur ' . $th->getMessage(),
            ], $th->getCode());
        }
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
        try {
            $task = Task::find($id);
            if ($task == null) {
                throw new Exception("Nous ne trouvons pas la ressource que vous demandez", 404);
            }
            if ($task->proof != null) {
                $state = $this->deleteImage($task->proof, '/uploads/tasks/');
                if ($state->getStatusCode() != 200) {
                    throw new Exception("Impossible de supprimer cette tâche.", 500);
                }
            }
            $task->delete();

            return response()->json([
                "action" => "deleted",
            ]);
        } catch (Throwable $th) {
            return response()->json([
                "action" => 'Erreur ' . $th->getMessage(),
            ], $th->getCode());
        }
    }

    public function proof(Request $request)
    {
        try {
            $task = Task::find($request->task_id);
            $url = null;
            $pj = $request->hasFile('file') ? $request->file : null;
            if ($task == null) {
                throw new Exception("Nous ne trouvons pas la ressource que vous demandez", 404);
            }
            if ($pj == null) {
                throw new Exception("Nous ne trouvons pas le fichier joint", 500);
            }
            if ($pj->isValid()) {
                $filename = time() . '_' . $pj->getClientOriginalName();
                $pj->move(public_path('/uploads/tasks'), $filename);
                $url = asset('/uploads/tasks/' . $filename);
            }
            if ($url == null) {
                throw new Exception("Impossible de récuperer l'URL de la ressource", 501);
            }
            $task->proof = $url;
            $task->progress = 1;
            $task->save();

            return response()->json([
                "action" => "updated",
                "status" => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "action" => 'Erreur ' . $th->getMessage(),
                "status" => 500,
            ]);
        }
    }
    public function deleteImage($url, $paths)
    {
        /// Get the filename from the URL
        $filename = basename($url);

        // Get the full path to the file
        $path = public_path($paths . $filename);

        // Check if the file exists
        if (file_exists($path)) {
            // Delete the file
            unlink($path);

            // Return a success response
            return response()->json(['message' => 'File deleted successfully.'], 200);
        } else {
            // Return an error response
            return response()->json(['message' => 'File not found.'], 404);
        }
    }
}
