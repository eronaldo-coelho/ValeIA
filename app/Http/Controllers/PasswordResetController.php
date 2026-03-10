<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Services\MailjetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class PasswordResetController extends Controller
{
    protected $mailjet;

    public function __construct(MailjetService $mailjet)
    {
        $this->mailjet = $mailjet;
    }

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Rate Limiting: Max 3 tentativas por minuto por IP para evitar spam
        $key = 'forgot-password:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Muitas tentativas. Aguarde um minuto.']);
        }
        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();

        // Security: Nunca revelar se o email existe ou não
        if (!$user) {
            return redirect()->route('password.verify', ['email' => $request->email]);
        }

        // Deletar códigos antigos
        PasswordResetCode::where('email', $request->email)->delete();

        // Gerar código numérico seguro de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(5) // Expira em 5 min
        ]);

        // Enviar via Mailjet
        $this->mailjet->sendVerificationCode($user->email, $user->name, $code);

        return redirect()->route('password.verify', ['email' => $request->email]);
    }

    public function showVerifyForm(Request $request)
    {
        return view('auth.verify-code', ['email' => $request->email]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        // Rate Limiting: Proteção contra Força Bruta (5 tentativas por IP)
        $key = 'verify-code:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['code' => 'Muitas tentativas incorretas. Bloqueado temporariamente.']);
        }

        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$resetCode) {
            RateLimiter::hit($key, 300); // 5 minutos de bloqueio se errar muito
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        // Código Válido: Autoriza a troca
        session(['password_reset_email' => $request->email]);
        $resetCode->delete(); // Invalida o código usado

        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::where('email', session('password_reset_email'))->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        session()->forget('password_reset_email');

        return redirect()->route('login')->with('success', 'Senha alterada com sucesso! Faça login.');
    }
}