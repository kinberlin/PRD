<?php

namespace App\Http\Controllers;

use App\Models\Dysfunction;
use App\Models\Enterprise;
use App\Models\Site;
use App\Models\Status;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class EmployeeController extends Controller
{
    public function dysfunction()
    {
        $ents = Enterprise::all();
        $site = Site::all();
        return view('employees/dysfunction', compact('ents', 'site'));
    }
    public function listeSignalement()
    {
        $data = Dysfunction::all();
        $status = Status::all();
        return view('employees/listesignalement', compact('data', 'status'));
    }
}
