<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->check() || Auth::guard('company')->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth-page');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            if (empty(Auth::guard('web')->user()->document)) {
                return redirect()->route('auth.complete');
            }

            return redirect()->intended('/admin');
        }

        if (Auth::guard('company')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'As credenciais informadas não conferem.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $request->merge([
            'document' => preg_replace('/[^0-9]/', '', $request->document),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:PF,PJ',
            'document' => 'required|unique:users',
            'birth_date' => 'required_if:type,PF|nullable|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'document' => $request->document,
            'birth_date' => $request->birth_date,
        ]);

        Auth::guard('web')->login($user);

        return redirect('/escolher-plano');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar ?? $user->avatar
                ]);
                Auth::guard('web')->login($user);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(uniqid()), 
                    'type' => 'PF', 
                    'avatar' => $googleUser->avatar,
                ]);
                
                session(['google_new_user' => true]);
                Auth::guard('web')->login($user);
            }

            if (empty($user->document) || session('google_new_user')) {
                return redirect()->route('auth.complete');
            }

            return redirect('/admin');

        } catch (\Exception $e) {
            return redirect('/auth')->withErrors(['google' => 'Erro ao entrar com Google.']);
        }
    }

    public function showCompleteRegistration()
    {
        return view('complete-registration');
    }

    public function storeCompleteRegistration(Request $request)
    {
        $request->merge([
            'document' => preg_replace('/[^0-9]/', '', $request->document),
        ]);

        $rules = [
            'type' => 'required|in:PF,PJ',
            'document' => 'required|unique:users,document,' . Auth::id(),
            'birth_date' => 'required_if:type,PF|nullable|date',
        ];

        if (session('google_new_user')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $data = [
            'type' => $request->type,
            'document' => $request->document,
            'birth_date' => $request->birth_date,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user = User::find(Auth::id());
        $user->update($data);

        session()->forget('google_new_user');

        return redirect('/escolher-plano');
    }
    
    public function logout(Request $request) {
        Auth::guard('web')->logout();
        Auth::guard('company')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}