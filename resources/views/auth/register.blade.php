<x-guest-layout wide="true">
    <div class="flex flex-col md:flex-row min-h-[620px]">
        <!-- Left Side: Illustration and Branding -->
        <div class="w-full md:w-1/2 bg-[#1a5eff] p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Decorative Hexagon Outlines (mocking background hexagons) -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="hexagons-reg" width="50" height="43.3" patternUnits="userSpaceOnUse" patternTransform="scale(2)">
                        <path d="M25 0 L50 14.4 L50 43.3 L25 57.7 L0 43.3 L0 14.4 Z" fill="none" stroke="currentColor" stroke-width="1" />
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#hexagons-reg)" />
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

        <!-- Right Side: Register Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto space-y-6">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Sign up</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div class="space-y-1">
                        <label for="name" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Full Name</label>
                        <input id="name" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name" 
                               placeholder="Nama Lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <!-- Asal Sekolah -->
                    <div class="space-y-1">
                        <label for="school" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Asal Sekolah</label>
                        <input id="school" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none" 
                               type="text" 
                               name="school" 
                               value="{{ old('school') }}" 
                               required 
                               placeholder="Contoh: SMAN 1 Jakarta" />
                        <x-input-error :messages="$errors->get('school')" class="mt-1" />
                    </div>

                    <!-- Provinsi Kampus Tujuan -->
                    <div class="space-y-1">
                        <label for="target_province" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Provinsi Kampus Tujuan</label>
                        <select id="target_province" 
                                name="target_province"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 font-medium text-sm focus:outline-none"
                                required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                        <x-input-error :messages="$errors->get('target_province')" class="mt-1" />
                    </div>

                    <!-- Kota/Kabupaten Kampus Tujuan -->
                    <div class="space-y-1">
                        <label for="target_city" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kota/Kabupaten Kampus Tujuan</label>
                        <select id="target_city" 
                                name="target_city"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 font-medium text-sm focus:outline-none"
                                required disabled>
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                        <x-input-error :messages="$errors->get('target_city')" class="mt-1" />
                    </div>

                    <!-- Pilihan Kampus Tujuan -->
                    <div class="space-y-1">
                        <label for="target_campus" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pilihan Kampus Tujuan</label>
                        <select id="target_campus" 
                                name="target_campus"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 font-medium text-sm focus:outline-none"
                                required disabled>
                            <option value="">Pilih Kampus</option>
                        </select>
                        <x-input-error :messages="$errors->get('target_campus')" class="mt-1" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-1">
                        <label for="email" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address</label>
                        <input id="email" 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username" 
                               placeholder="Email Address" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Passwords Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-1">
                            <label for="password" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Password</label>
                            <input id="password" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none"
                                   type="password"
                                   name="password"
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1">
                            <label for="password_confirmation" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Confirm Password</label>
                            <input id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 rounded-2xl transition-all duration-200 text-slate-800 placeholder-slate-400 font-medium text-sm focus:outline-none"
                                   type="password"
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>
                    </div>

                    <!-- Legal agreement -->
                    <div class="text-xs text-slate-400 font-medium leading-relaxed pt-1">
                        You are agreeing to the <a href="#" class="text-blue-600 hover:underline">Terms of Services</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full py-4 bg-[#1a5eff] hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all duration-300 shadow-lg shadow-blue-500/25 text-sm tracking-wide">
                            Get Started
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-sm text-slate-500 font-medium">
                            Already a member? 
                            <a class="text-blue-600 hover:text-blue-700 font-bold hover:underline" href="{{ route('login') }}">
                                Sign in
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<!-- Select2 CSS and JS with jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Styling to make Select2 look beautiful and match the existing Tailwind form controls */
    .select2-container .select2-selection--single {
        height: 52px !important;
        background-color: #f8fafc !important; /* bg-slate-50 */
        border: 1px solid #f1f5f9 !important; /* border-slate-100 */
        border-radius: 1rem !important; /* rounded-2xl */
        transition: all 0.2s ease-in-out !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important; /* text-slate-800 */
        font-weight: 500 !important;
        font-size: 0.875rem !important; /* text-sm */
        padding-left: 1rem !important;
        padding-right: 2.5rem !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px !important;
        right: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #94a3b8 transparent transparent transparent !important; /* text-slate-400 */
        border-width: 5px 5px 0 5px !important;
        margin-left: -5px !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #94a3b8 transparent !important;
        border-width: 0 5px 5px 5px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #1a5eff !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 2px rgba(26, 94, 255, 0.15) !important;
    }
    .select2-dropdown {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden;
        z-index: 9999;
        margin-top: 4px;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        padding: 8px 12px !important;
        outline: none !important;
        font-size: 0.875rem !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1a5eff !important;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f1f5f9 !important;
        color: #1a5eff !important;
        font-weight: 600;
    }
    .select2-container--default .select2-selection--single[aria-disabled="true"] {
        background-color: #f1f5f9 !important;
        border-color: #f1f5f9 !important;
        opacity: 0.6;
        cursor: not-allowed;
    }
    .select2-results__option {
        padding: 10px 16px !important;
        font-size: 0.875rem !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize select2
    $('#target_province').select2({ placeholder: "Pilih Provinsi" });
    $('#target_city').select2({ placeholder: "Pilih Kota/Kabupaten" });
    $('#target_campus').select2({ placeholder: "Pilih Kampus" });

    // When province changes
    $('#target_province').on('change', function() {
        const provinceName = $(this).val();
        const provinceId = $(this).find(':selected').attr('data-id') || $(this).find(':selected').data('id');

        // Reset city & campus
        $('#target_city').html('<option value="">Pilih Kota/Kabupaten</option>').val('').trigger('change').prop('disabled', true);
        $('#target_campus').html('<option value="">Pilih Kampus</option>').val('').trigger('change').prop('disabled', true);

        if (!provinceId) return;

        // Fetch cities
        $.ajax({
            url: '{{ route('api.cities') }}',
            data: { province_id: provinceId },
            success: function(res) {
                if (res && res.data) {
                    $('#target_city').prop('disabled', false);
                    res.data.forEach(function(city) {
                        const opt = $('<option></option>')
                            .val(city.name)
                            .attr('data-id', city.id)
                            .text(city.name);
                        $('#target_city').append(opt);
                    });
                    $('#target_city').trigger('change');
                }
            }
        });
    });

    // When city changes
    $('#target_city').on('change', function() {
        const provinceId = $('#target_province').find(':selected').attr('data-id') || $('#target_province').find(':selected').data('id');
        const cityId = $(this).find(':selected').attr('data-id') || $(this).find(':selected').data('id');
        const cityName = $(this).val();

        $('#target_campus').html('<option value="">Pilih Kampus</option>').val('').trigger('change').prop('disabled', true);

        if (!cityId) return;

        // Fetch campuses
        $.ajax({
            url: '{{ route('api.campuses') }}',
            data: { province_id: provinceId, city_id: cityId, city_name: cityName },
            success: function(res) {
                if (res && res.data) {
                    $('#target_campus').prop('disabled', false);
                    res.data.forEach(function(campus) {
                        const opt = $('<option></option>')
                            .val(campus.name)
                            .text(campus.name);
                        $('#target_campus').append(opt);
                    });
                    $('#target_campus').trigger('change');
                }
            }
        });
    });

    // Initial Load Provinces
    $.ajax({
        url: '{{ route('api.provinces') }}',
        success: function(res) {
            if (res && res.data) {
                res.data.forEach(function(province) {
                    const opt = $('<option></option>')
                        .val(province.name)
                        .attr('data-id', province.id)
                        .text(province.name);
                    $('#target_province').append(opt);
                });
                $('#target_province').trigger('change');
            }
        }
    });
});
</script>

