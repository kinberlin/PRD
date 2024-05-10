<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GanttController extends Controller
{
    public function get(){
 
        return response()->json([
            "data" => Task::all(),
            "links" => Link::all()
        ]);
    }
}
