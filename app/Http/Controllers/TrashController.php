<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TrashController extends Controller
{
    /**
     * Display a listing of trashed enterprises.
     */
    public function enterprise()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Enterprise::onlyTrashed()->get();
        return view('admin/trash/enterprise', compact('data'));
    }
}
