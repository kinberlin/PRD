<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Processes;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GanttController extends Controller
{
    public function get(){
 
        return response()->json([
            "data" => Task::orderBy('sortorder')->get(),
            "links" => Link::all(),
            "processes" => Processes::all()
        ]);
    }
}
