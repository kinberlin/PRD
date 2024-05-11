<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use App\Models\Link;
use App\Models\Processes;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class GanttController extends Controller
{
    public function get(){
 
        return response()->json([
            "data" => Task::orderBy('sortorder')->get(),
            "links" => Link::all(),
            "processes" => Processes::all()
        ]);
    }

    public function planner($id){
        try {
            DB::beginTransaction();
            $dys = Dysfunction::find($id);
            if ($dys == null) {
                throw new Exception("La ressource spécifié est introuvable.", 1);
            }
            if ($dys->status  == 3) {
                throw new Exception("Erreur de traitement. Ce dysfonctionnement est déja annulé. Il ne peut donc plus être traiter.", 1);
            }
            return view('rq/planner', compact('id'));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
        
    }
}
