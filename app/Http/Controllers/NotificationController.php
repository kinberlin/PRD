<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $rec = Notification::find($id);
            $rec->delete();
            DB::commit();
            return redirect()->back();
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Echec lors de la surpression. L'erreur indique : ".$th->getMessage());
        }
    }
}
