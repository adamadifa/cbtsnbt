<!-- jQuery & Select2 CDNs (Required for search features) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Premium styling overrides for Select2 to match the Tailwind dashboard theme */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 14px !important;
        background-color: #f8fafc !important;
        display: flex !important;
        align-items: center !important;
        outline: none !important;
        transition: all 0.2s !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        padding-left: 14px !important;
        line-height: normal !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 10px !important;
    }
    .select2-dropdown {
        border-radius: 14px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden !important;
        background: #ffffff !important;
        z-index: 999999 !important;
    }
    .select2-results__option {
        font-size: 11px !important;
        padding: 10px 14px !important;
        color: #334155 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #4f46e5 !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #818cf8 !important;
        box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.1) !important;
        background-color: #ffffff !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        font-size: 11px !important;
        padding: 8px 12px !important;
        outline: none !important;
    }
</style>

<!-- Targets Modal -->
<div id="targetsModal" class="{{ $mustSelectTargets ? '' : 'hidden' }} fixed inset-0 z-[100] overflow-y-auto animate-in fade-in duration-200" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
             @if(!$mustSelectTargets) onclick="closeTargetsModal()" @endif></div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <!-- Modal Content (Wider: max-w-2xl) -->
        <div class="inline-block align-middle bg-white rounded-3xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200/80 p-6 sm:p-8 relative">
            
            @if(!$mustSelectTargets)
                <button onclick="closeTargetsModal()" class="absolute top-6 right-6 w-9 h-9 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif

            <div class="flex items-start gap-4 pb-5 border-b border-slate-100">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-indigo-100/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.685 0-5.3.233-7.875.682V21A2.25 2.25 0 006.375 23.25h11.25A2.25 2.25 0 0019.5 21z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Pilih Kampus & Prodi Impian</h3>
                    <p class="text-xs text-slate-450 mt-0.5">Pilih maksimal 4 kampus tujuan untuk memantau simulasi kelulusan Anda secara real-time.</p>
                </div>
            </div>

            @if($mustSelectTargets)
                <div class="mt-5 p-4 bg-amber-50 border border-amber-100 rounded-2xl flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-xs font-semibold text-amber-850 leading-normal">Anda wajib memilih minimal 1 kampus tujuan terlebih dahulu untuk dapat mengakses dashboard portal ujian Lulus SNBT.</p>
                </div>
            @endif

            <!-- Form -->
            <form id="targetsForm" onsubmit="submitTargets(event)" class="mt-6 space-y-5">
                @csrf
                
                <!-- Target 1 (Mandatory) -->
                <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-100 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-indigo-750 uppercase tracking-wider">Pilihan 1 (Wajib)</span>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Kampus</label>
                            <select id="campus_1" class="w-full">
                                <option value="">Pilih Kampus</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Program Studi</label>
                            <select id="prodi_1" class="w-full" disabled>
                                <option value="">Pilih Program Studi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Target 2 (Optional) -->
                <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-100 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-550 uppercase tracking-wider">Pilihan 2 (Opsional)</span>
                        <button type="button" id="clear_btn_2" onclick="clearChoice(2)" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 transition-colors hidden">Hapus Pilihan</button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Kampus</label>
                            <select id="campus_2" class="w-full">
                                <option value="">Pilih Kampus</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Program Studi</label>
                            <select id="prodi_2" class="w-full" disabled>
                                <option value="">Pilih Program Studi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Target 3 (Optional) -->
                <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-100 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-550 uppercase tracking-wider">Pilihan 3 (Opsional)</span>
                        <button type="button" id="clear_btn_3" onclick="clearChoice(3)" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 transition-colors hidden">Hapus Pilihan</button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Kampus</label>
                            <select id="campus_3" class="w-full">
                                <option value="">Pilih Kampus</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Program Studi</label>
                            <select id="prodi_3" class="w-full" disabled>
                                <option value="">Pilih Program Studi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Target 4 (Optional) -->
                <div class="bg-slate-50/60 p-5 rounded-2xl border border-slate-100 space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-550 uppercase tracking-wider">Pilihan 4 (Opsional)</span>
                        <button type="button" id="clear_btn_4" onclick="clearChoice(4)" class="text-[10px] font-bold text-rose-500 hover:text-rose-600 transition-colors hidden">Hapus Pilihan</button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Kampus</label>
                            <select id="campus_4" class="w-full">
                                <option value="">Pilih Kampus</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-0.5">Program Studi</label>
                            <select id="prodi_4" class="w-full" disabled>
                                <option value="">Pilih Program Studi</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer / Action Buttons -->
                <div class="pt-5 border-t border-slate-100 flex gap-4">
                    @if(!$mustSelectTargets)
                        <button type="button" onclick="closeTargetsModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs rounded-xl transition-all">
                            Batal
                        </button>
                    @endif
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-indigo-600 to-blue-700 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-indigo-500/10 hover:shadow-lg hover:shadow-indigo-500/20 active:scale-[0.99]">
                        Simpan Kampus Impian
                    </button>
                </div>
            </form>

            <!-- Loading overlay inside modal -->
            <div id="targets-modal-loader" class="absolute inset-0 bg-white/95 rounded-3xl z-20 flex flex-col items-center justify-center space-y-4 hidden animate-in fade-in">
                <div class="w-10 h-10 border-4 border-slate-200 border-t-indigo-600 rounded-full animate-spin"></div>
                <div class="text-xs font-bold text-slate-700" id="targets-loader-text">Memuat pilihan kampus...</div>
            </div>
        </div>
    </div>
</div>

<script>
    let isInitialized = false;
    let globalCampuses = [];
    let savedTargets = @json($targets ?? []);

    function openTargetsModal() {
        document.getElementById('targetsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        if (!isInitialized) {
            initTargetsModal();
        }
    }

    function closeTargetsModal() {
        document.getElementById('targetsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function showModalLoader(text) {
        document.getElementById('targets-loader-text').innerText = text;
        document.getElementById('targets-modal-loader').classList.remove('hidden');
    }

    function hideModalLoader() {
        document.getElementById('targets-modal-loader').classList.add('hidden');
    }

    function initTargetsModal() {
        isInitialized = true;
        initializeSelect2();

        // Load saved targets if exists
        if (savedTargets && savedTargets.length > 0) {
            showModalLoader('Memuat data kampus tujuan...');
            applySavedTargets();
        } else {
            hideModalLoader();
        }
    }

    function initializeSelect2() {
        for (let i = 1; i <= 4; i++) {
            $(`#campus_${i}`).select2({
                placeholder: "Ketik nama kampus (min. 1 huruf)",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('api.campuses-list') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.campuses.map(function(name) {
                                return { id: name, text: name };
                            })
                        };
                    },
                    cache: true
                },
                width: '100%'
            }).on('change', function() {
                loadProdis(i, this.value);
            });

            $(`#prodi_${i}`).select2({
                placeholder: "Pilih Program Studi",
                allowClear: false,
                width: '100%'
            });
        }
    }

    function loadProdis(choiceIdx, campusName, callback = null) {
        const prodiSelect = $(`#prodi_${choiceIdx}`);
        prodiSelect.html('<option value="">Pilih Program Studi</option>').trigger('change');
        
        if (!campusName) {
            prodiSelect.prop('disabled', true).trigger('change');
            $(`#clear_btn_${choiceIdx}`).addClass('hidden');
            if (callback) callback();
            return;
        }

        prodiSelect.prop('disabled', false).trigger('change');
        if (choiceIdx > 1) {
            $(`#clear_btn_${choiceIdx}`).removeClass('hidden');
        }

        $.ajax({
            url: "{{ route('api.campus-prodis-list') }}",
            type: 'GET',
            data: { campus: campusName },
            success: function(res) {
                if (res.success && res.prodis.length > 0) {
                    res.prodis.forEach(prodi => {
                        const opt = $('<option></option>').val(prodi.id).text(`${prodi.prodi_name} (${prodi.jenjang})`);
                        prodiSelect.append(opt);
                    });
                }
                prodiSelect.trigger('change');
                if (callback) callback();
            },
            error: function() {
                if (callback) callback();
            }
        });
    }

    function clearChoice(choiceIdx) {
        $(`#campus_${choiceIdx}`).val(null).trigger('change');
        $(`#prodi_${choiceIdx}`).html('<option value="">Pilih Program Studi</option>').prop('disabled', true).trigger('change');
        $(`#clear_btn_${choiceIdx}`).addClass('hidden');
    }

    function applySavedTargets() {
        let loadedCount = 0;
        const totalToLoad = savedTargets.length;

        savedTargets.forEach((target, index) => {
            const idx = index + 1;
            const campusName = target.campus_prodi.campus_name;
            const targetProdiId = target.campus_prodi_id;

            const campusSelect = $(`#campus_${idx}`);
            if (campusSelect.find("option[value='" + campusName + "']").length === 0) {
                var newOption = new Option(campusName, campusName, true, true);
                campusSelect.append(newOption).trigger('change');
            } else {
                campusSelect.val(campusName).trigger('change');
            }
            
            loadProdis(idx, campusName, () => {
                $(`#prodi_${idx}`).val(targetProdiId).trigger('change');
                loadedCount++;
                if (loadedCount === totalToLoad) {
                    hideModalLoader();
                }
            });
        });
    }

    function submitTargets(e) {
        e.preventDefault();

        const targets = [];
        
        const prodi1 = document.getElementById('prodi_1').value;
        if (!prodi1) {
            alert('Pilihan 1 wajib diisi.');
            return;
        }
        targets.push({ campus_prodi_id: prodi1 });

        for (let i = 2; i <= 4; i++) {
            const prodiVal = document.getElementById(`prodi_${i}`).value;
            if (prodiVal) {
                targets.push({ campus_prodi_id: prodiVal });
            }
        }

        showModalLoader('Menyimpan pilihan kampus...');

        $.ajax({
            url: "{{ route('student.targets.save') }}",
            type: 'POST',
            data: JSON.stringify({ targets: targets }),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                hideModalLoader();
                if (res.success) {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message || 'Gagal menyimpan pilihan.');
                }
            },
            error: function(xhr) {
                hideModalLoader();
                let msg = 'Terjadi kesalahan saat menyimpan pilihan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            }
        });
    }

    $(document).ready(function() {
        if (@json($mustSelectTargets)) {
            openTargetsModal();
        }
    });
</script>
