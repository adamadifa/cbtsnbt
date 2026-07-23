<x-guest-layout wide="true">
    <div class="flex flex-col md:flex-row min-h-[620px]">
        <!-- Left Side: Illustration and Branding -->
        <div class="w-full md:w-1/2 bg-[#1a5eff] p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative Hexagon Outlines (mocking background hexagons) -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="hexagons" width="50" height="43.3" patternUnits="userSpaceOnUse" patternTransform="scale(2)">
                            <path d="M25 0 L50 14.4 L50 43.3 L25 57.7 L0 43.3 L0 14.4 Z" fill="none" stroke="currentColor" stroke-width="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#hexagons)" />
                </svg>
            </div>

            <!-- Logo -->
            <div class="z-10 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C12 2 6 8.5 6 13.5a6 6 0 0012 0C18 8.5 12 2 12 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Headline -->
            <div class="z-10 my-8">
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight max-w-sm">
                    One click to go<br>all digital.
                </h1>
            </div>

            <!-- Illustration -->
            <div class="relative z-10 flex justify-center items-end mt-auto">
                <img src="{{ asset('images/auth-illustration.png') }}" alt="Web Analytics Illustration" class="w-full max-w-[340px] md:max-w-[380px] h-auto object-contain">
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-8">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Sign in</h2>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1">
                        <label for="email" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address</label>
                        <input id="email" 
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username" 
                               placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <input id="password" 
                               class="w-full px-4 py-3.5 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none"
                               type="password"
                               name="password"
                               required 
                               autocomplete="current-password"
                               placeholder="Password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4 transition-all" name="remember">
                        <span class="ms-2.5 text-sm text-slate-500 font-medium">Remember me on this device</span>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-4 bg-[#1a5eff] hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg shadow-blue-500/25 text-sm tracking-wide">
                            Masuk Sekarang
                        </button>
                    </div>

                    @if (Route::has('register'))
                        <div class="text-center pt-2">
                            <p class="text-sm text-slate-500 font-medium">
                                Belum punya akun? 
                                <a class="text-blue-600 hover:text-blue-700 font-bold hover:underline" href="{{ route('register') }}">
                                    Daftar di sini
                                </a>
                            </p>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
