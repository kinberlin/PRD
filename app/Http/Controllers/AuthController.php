<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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
                    'password' => $request->password,
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
            return redirect()->back()->with(['error' => 'Nous ne trouvons pas votre compte']);
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
    public function updatePassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:newPassword',
        ], [
            'newPassword.required' => 'Please enter a new password.',
            'newPassword.min' => 'Password must be more than 8 characters.',
            'confirmPassword.required' => 'Please confirm your new password.',
            'confirmPassword.same' => 'The password and its confirm are not the same.',
        ]);
        try {
            if ($validator->fails()) {
                throw new Exception($validator->errors()->toJson(), 501);
            }

            // Find the user by ID
            $user = Auth::user();

            // Update the user's password
            $user->password = bcrypt($request->newPassword);
            $user->save();

            return redirect()->back()->with('error', "Mot de Passe mis a jour avec succes");
        } catch (Throwable $th) {
            return redirect()->back()->with('error', "Erreur : " . $th->getMessage());
        }
    }
    public function setyear($year)
    {
        if ($year > 1999 && $year < 9999) {Session::put('currentYear', $year);}
        return back()->with(['error' => 'Données de '.session('currentYear')]);
    }
}
