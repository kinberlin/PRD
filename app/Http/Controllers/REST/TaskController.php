<?php

namespace App\Http\Controllers\REST;

use App\Models\Dysfunction;
use App\Models\Task;
use App\Models\Viewby;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TaskController extends RoutingController
{
    public function store(Request $request)
    {
        $task = new Task();
        $dyst = Dysfunction::find($request->dysfunction);
        if (is_null($dyst)) {
            throw new Exception('Ce dysfonctionnement est introuvable: ' . $request->dysfunction, 404);
        }
        if ($dyst->status == 4) {
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
            $task->created_by = 'Responsable Qualité'; //Auth::user()->firstname . ' ' . Auth::user()->lastname;

            $task->save();

            return response()->json([
                "action" => "inserted",
                "tid" => $task->id,
            ]);
        } else {
            return response()->json([
                "action" => "Erreur ce dysfonctionnement n'est plus planifiable",
            ], 401);
        }
    }

    public function update($id, Request $request)
    {
        try {
            $task = Task::find($id);
            $dyst = Dysfunction::find($request->dysfunction);
            if (is_null($task)) {
                throw new Exception("Nous ne trouvons pas la ressource que vous demandez", 404);
            }
            if (is_null($dyst)) {
                throw new Exception('Ce dysfonctionnement est introuvable : ' . $request->dysfunction, 404);
            }
            if ($dyst->status == 4) {
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

                if ($request->has("target")) {
                    $this->updateOrder($id, $request->target);
                }

                return response()->json([
                    "action" => "updated",
                ]);
            } else {
                return response()->json([
                    "action" => "Erreur ce dysfonctionnement n'est plus planifiable",
                ], 401);
            }
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
            if (is_null($task)) {
                throw new Exception("Nous ne trouvons pas la ressource que vous demandez", 404);
            }
            $dyst = Dysfunction::find($task->dysfunction);
            if (is_null($dyst)) {
                throw new Exception('Ce dysfonctionnement est introuvable : ' . $task->dysfunction, 404);
            }
            if ($dyst->status == 4) {
                /*if ($task->proof != null) {
                $state = $this->deleteImage($task->proof, '/uploads/tasks/');
                if ($state->getStatusCode() != 200) {
                throw new Exception("Impossible de supprimer cette tâche.", 500);
                }
                }*/
                if ($task->proof != null) {
                    Storage::disk('public')->delete($task->proof);
                }
                $task->forcedelete();
                return response()->json([
                    "action" => "deleted",
                ]);
            } else {
                return response()->json([
                    "action" => "Erreur ce dysfonctionnement n'est plus planifiable",
                ], 401);
            }
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
                // Store the file and get the path
                $url = $pj->store('tasks', 'public'); // Save image to 'storage/app/public/tasks'
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

    public function viewBy_store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = new Viewby($request->input('matricule'));
            $task = Task::find($request->input('task_id'));
            if ($task == null) {
                throw new Exception("Nous ne trouvons pas la ressource que vous demandez", 404);
            }
            if ($data->findViewByMatricule($task, $request->input('matricule')) == null) {
                $views = $data->getViews($task);
                $views = $data;
                $task->view_by = json_encode($views, JSON_UNESCAPED_UNICODE);
                $task->save();
            }
            DB::commit();
            return redirect()->back()->with('error', "La réunion a été créer avec succes.");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
}
