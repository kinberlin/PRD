<?php

namespace App\Http\Controllers;

use App\Models\Enterprise;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Throwable;

class AuthController extends Controller
{
    public function auth(Request $request)
    {
        Session::put('currentYear', now()->year);
        try {
            $credentials = $request->only('matricule', 'password');

            if (Auth::attempt($credentials)) {
                if (Auth::user()->access == 1) {
                    if (Auth::user()->role == 1) {
                        return redirect()->intended('/admin');
                    } else {
                        if (Gate::allows('isRq', Auth::user())) {
                            return redirect()->intended('/rq')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                        } elseif (Gate::allows('isPilote', Auth::user())) {
                            return redirect()->intended('/employee')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                        } else {
                            return redirect()->intended('/employee')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                        }
                    }
                } else {
                    Auth::logout();
                    throw new Exception("Il se peut que l'accès à votre compte ait été révoqué.", 400);
                }
            } else {
                $credentials = [
                    'email' => $request->matricule,
                    'password' => $request->password
                ];
                if (Auth::attempt($credentials)) {
                    if (Auth::user()->access == 1) {
                        if (Auth::user()->role == 1) {
                            return redirect()->intended('/admin');
                        } else {
                            if (Gate::allows('isRq', Auth::user())) {
                                return redirect()->intended('/rq')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                            } elseif (Gate::allows('isPilote', Auth::user())) {
                                return redirect()->intended('/employee')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                            } else {
                                return redirect()->intended('/employee')->with('error', 'Bienvenue ' . Auth::user()->firstname);
                            }
                        }
                    } else {
                        Auth::logout();
                        throw new Exception("Il se peut que l'accès à votre compte ait été révoqué.", 400);
                    }
                }
            }
            return back()->with(['error' => 'Nous ne trouvons pas votre compte']);
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function logout()
    {
        Session::put('currentYear', now()->year);
        Auth::logout();
        return redirect('/login');
    }
    public function login()
    {
        Session::put('currentYear', now()->year);
        return view('login');
    }
    public function NotFound404()
    {
        try {

            return view('404');
        } catch (Throwable $th) {
            return ['message' => 'Erreur : ' . $th->getMessage(), 'code' => 500];
        }
    }
    public function NotFound404P(Request $request)
    {
        try {

            if (Auth::check()) {
                if (Auth::user()->role == 2) {
                    return redirect('/employee/dashboard');
                } else {
                    return redirect('/admin/dashboard');
                }
            } else {
                return redirect('/login');
            }
        } catch (Throwable $th) {
            return ['message' => 'Erreur : ' . $th->getMessage(), 'code' => 500];
        }
    }
}
