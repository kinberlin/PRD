<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TrashController extends Controller
{
    public function enterprise()
    {
        Gate::authorize('isAdmin', Auth::user());
        $data = Enterprise::onlyTrashed()->get();
        return view('admin/trash/enterprise', compact('data'));
    }
}
