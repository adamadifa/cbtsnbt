<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Employee Dashboard</h1>
            <div class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>/</span>
                <span>Dashboard</span>
                <span>/</span>
                <span class="text-slate-600 font-medium">Employee Dashboard</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-slate-600 font-semibold text-sm flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Export
            </button>
            <button class="px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl text-slate-600 font-semibold text-sm flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zm0-4.5h.008v.008H9.75v-.008zm6.75 4.5h.008v.008h-.008v-.008zm0-2.25h.008v.008h-.008V15zm0-2.25h.008v.008h-.008v-.008z" />
                </svg>
                15/06/2025
            </button>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="mb-6 bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center justify-between text-blue-800" x-data="{ show: true }" x-show="show">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium">Your Leave Request on <span class="font-bold">24th April 2024</span> has been <span class="font-bold">Approved!!!</span></p>
        </div>
        <button @click="show = false" class="text-blue-500 hover:text-blue-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Top Stats / Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 relative overflow-hidden shadow-sm flex flex-col justify-between">
            <button class="absolute top-4 right-4 w-8 h-8 rounded-lg hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 20.013a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </button>
            <div class="flex items-start gap-4">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=150" alt="Profile" class="w-16 h-16 rounded-2xl object-cover ring-4 ring-slate-50">
                <div>
                    <h3 class="font-bold text-slate-800 text-lg">Stephan Peralt</h3>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">Senior Product Designer - UI/UX Design</p>
                </div>
            </div>
            <div class="border-t border-slate-100/80 my-5 pt-5 space-y-3.5">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400 font-semibold uppercase">Phone Number</span>
                    <span class="text-slate-700 font-bold">+1 324 3453 545</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400 font-semibold uppercase">Email Address</span>
                    <span class="text-slate-700 font-bold">steperde124@example.com</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400 font-semibold uppercase">Report Office</span>
                    <span class="text-slate-700 font-bold">Doglas Martini</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400 font-semibold uppercase">Joined on</span>
                    <span class="text-slate-700 font-bold">15 Jan 2024</span>
                </div>
            </div>
        </div>

        <!-- Leave Details Donut Chart Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Leave Details</h3>
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">2025</span>
            </div>
            
            <div class="flex items-center gap-6 py-3">
                <!-- Circular Chart simulation with SVG -->
                <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e2e8f0" stroke-width="3"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#10b981" stroke-width="3" stroke-dasharray="60 40" stroke-dashoffset="0"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f59e0b" stroke-width="3" stroke-dasharray="25 75" stroke-dashoffset="-60"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#ef4444" stroke-width="3" stroke-dasharray="15 85" stroke-dashoffset="-85"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-sm font-bold text-slate-700">85%</span>
                    </div>
                </div>
                <!-- Chart Legend -->
                <div class="flex-1 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                            <span>On Time</span>
                        </div>
                        <span class="font-bold text-slate-700">1254</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span>
                            <span>Late Attendance</span>
                        </div>
                        <span class="font-bold text-slate-700">32</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span>
                            <span>WFH</span>
                        </div>
                        <span class="font-bold text-slate-700">658</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5 font-semibold text-slate-500">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span>
                            <span>Absent</span>
                        </div>
                        <span class="font-bold text-slate-700">14</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Better than <span class="text-slate-800 font-bold">85%</span> of Employees
            </div>
        </div>

        <!-- Leave Details Stats Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Leave Stats</h3>
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">2025</span>
            </div>

            <div class="grid grid-cols-2 gap-4 py-4">
                <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Total Leaves</div>
                    <div class="text-lg font-black text-slate-800 mt-1">16</div>
                </div>
                <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Taken</div>
                    <div class="text-lg font-black text-slate-800 mt-1">10</div>
                </div>
                <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Absent</div>
                    <div class="text-lg font-black text-slate-800 mt-1">2</div>
                </div>
                <div class="bg-slate-50/60 rounded-2xl p-3 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase">Worked Days</div>
                    <div class="text-lg font-black text-slate-800 mt-1">240</div>
                </div>
            </div>

            <button class="w-full py-3 bg-[#111827] hover:bg-slate-800 text-white font-bold rounded-2xl transition-colors text-xs">
                Apply New Leave
            </button>
        </div>
    </div>

    <!-- Attendance & Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Attendance Card -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="text-center">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Attendance</span>
                <span class="text-base font-black text-slate-800 mt-1 block">08:35 AM, 11 Mar 2025</span>
            </div>

            <div class="flex justify-center py-6">
                <!-- Circular timer SVG -->
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f1f5f9" stroke-width="2.5"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#10b981" stroke-width="2.5" stroke-dasharray="70 30"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xs text-slate-400 font-semibold block">Total Hours</span>
                        <span class="text-lg font-black text-slate-800 mt-0.5">5:45:32</span>
                    </div>
                </div>
            </div>

            <div class="text-center space-y-4">
                <span class="inline-block px-3 py-1 bg-slate-50 border border-slate-100 rounded-full text-xs font-bold text-slate-600">Production: 3.45 hrs</span>
                <p class="text-xs font-semibold text-slate-400">Punch In at <span class="text-slate-700 font-bold">10:00 AM</span></p>
                <button class="w-full py-3.5 bg-[#f97316] hover:bg-orange-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-orange-500/20 text-xs">
                    Punch Out
                </button>
            </div>
        </div>

        <!-- Small Hours Stats and Visual progress -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="border border-slate-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span>
                        <span class="text-xs font-bold text-slate-700">8.36 <span class="text-slate-400">/ 9</span></span>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Total Hours Today</span>
                </div>
                <div class="border border-slate-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-slate-700">24.426 <span class="text-slate-400">/ 40</span></span>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Total Hours Week</span>
                </div>
                <div class="border border-slate-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-xs font-bold text-slate-700">126 <span class="text-slate-400">/ 160</span></span>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Total Hours Month</span>
                </div>
                <div class="border border-slate-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-slate-700">16 <span class="text-slate-400">/ 28</span></span>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400 mt-1 block">Overtime this Month</span>
                </div>
            </div>

            <!-- Dynamic Timings row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t border-b border-slate-100/80 py-5 my-5">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Total Working hours</span>
                    <span class="text-base font-black text-slate-700 mt-1 block">12h 36m</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Productive hours</span>
                    <span class="text-base font-black text-slate-700 mt-1 block">08h 36m</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Break hours</span>
                    <span class="text-base font-black text-slate-700 mt-1 block">22m 15s</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Overtime</span>
                    <span class="text-base font-black text-slate-700 mt-1 block">02h 15m</span>
                </div>
            </div>

            <!-- Visual timeline progress bar -->
            <div class="space-y-3">
                <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden flex">
                    <div class="h-full bg-orange-400" style="width: 25%"></div>
                    <div class="h-full bg-emerald-500" style="width: 45%"></div>
                    <div class="h-full bg-yellow-400" style="width: 15%"></div>
                    <div class="h-full bg-blue-500" style="width: 15%"></div>
                </div>
                <div class="flex justify-between text-[10px] text-slate-400 font-bold">
                    <span>00:00</span>
                    <span>06:00</span>
                    <span>12:00</span>
                    <span>18:00</span>
                    <span>24:00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects and Tasks Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Projects -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-800">Projects</h3>
                <span class="text-xs font-bold text-blue-600 hover:underline cursor-pointer">Ongoing Projects</span>
            </div>
            
            <div class="space-y-4">
                <!-- Project Item -->
                <div class="p-4 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Office Management</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=50" alt="Avatar" class="w-5 h-5 rounded-full object-cover">
                            <span class="text-[10px] font-semibold text-slate-400">Anthony Lewis (Lead)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Deadline</span>
                        <span class="text-[10px] font-medium text-slate-400 block mt-0.5">14/01/2026</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Time Spent</span>
                        <span class="text-sm font-bold text-blue-600 block mt-0.5">65/120 Hrs</span>
                    </div>
                </div>

                <!-- Project Item 2 -->
                <div class="p-4 border border-slate-100 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Client Portal App</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=50" alt="Avatar" class="w-5 h-5 rounded-full object-cover">
                            <span class="text-[10px] font-semibold text-slate-400">Harvey Smith (Lead)</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Deadline</span>
                        <span class="text-[10px] font-medium text-slate-400 block mt-0.5">28/02/2026</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-600 block">Time Spent</span>
                        <span class="text-sm font-bold text-blue-600 block mt-0.5">12/80 Hrs</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Checklist -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-800">Tasks</h3>
                <span class="text-xs font-bold text-blue-600 hover:underline cursor-pointer">All Projects</span>
            </div>

            <div class="space-y-3.5">
                <!-- Task item -->
                <div class="flex items-center justify-between p-3.5 hover:bg-slate-50/50 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span class="text-sm font-bold text-slate-700">Patient appointment booking</span>
                    </div>
                    <span class="px-2 py-0.5 bg-orange-50 border border-orange-100 rounded-lg text-[10px] font-bold text-orange-600">Onhold</span>
                </div>

                <!-- Task item 2 -->
                <div class="flex items-center justify-between p-3.5 hover:bg-slate-50/50 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span class="text-sm font-bold text-slate-700 line-through text-slate-400">Appointment booking with payment</span>
                    </div>
                    <span class="px-2 py-0.5 bg-purple-50 border border-purple-100 rounded-lg text-[10px] font-bold text-purple-600">Inprogress</span>
                </div>

                <!-- Task item 3 -->
                <div class="flex items-center justify-between p-3.5 hover:bg-slate-50/50 rounded-2xl transition-colors">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span class="text-sm font-bold text-slate-700">Patient and Doctor video-conferencing</span>
                    </div>
                    <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 rounded-lg text-[10px] font-bold text-emerald-600">Completed</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
