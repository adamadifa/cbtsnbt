<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'school' => ['required', 'string', 'max:255'],
            'target_province' => ['required', 'string', 'max:255'],
            'target_city' => ['required', 'string', 'max:255'],
            'target_campus' => ['required', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'school' => $request->school,
            'target_province' => $request->target_province,
            'target_city' => $request->target_city,
            'target_campus' => $request->target_campus,
        ]);

        $user->assignRole('siswa');

        event(new Registered($user));

        Auth::login($user);

        $url = $user->hasAnyRole(['admin', 'super_admin']) ? route('admin.dashboard') : route('dashboard');
        return redirect($url);
    }
}
