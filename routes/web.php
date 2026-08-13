<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $userAgent = request()->header('User-Agent', '');
    $isMobile = preg_match('/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i', $userAgent);

    if ($isMobile) {
        return view('welcome-mobile');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'redirect_role'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/student/targets', [\App\Http\Controllers\Student\DashboardController::class, 'saveTargets'])->name('student.targets.save');
    Route::get('/api/campuses-list', [\App\Http\Controllers\Student\DashboardController::class, 'getCampusesList'])->name('api.campuses-list');
    Route::get('/api/campus-prodis-list', [\App\Http\Controllers\Student\DashboardController::class, 'getCampusProdisList'])->name('api.campus-prodis-list');

    // Student Exam Flow
    Route::prefix('exam')->name('student.exam.')->group(function () {
        Route::post('/start', [\App\Http\Controllers\Student\ExamController::class, 'start'])->name('start');
        Route::get('/{examResult}', [\App\Http\Controllers\Student\ExamController::class, 'show'])->name('show');
        Route::get('/{examResult}/results', [\App\Http\Controllers\Student\ExamController::class, 'results'])->name('results');
        Route::get('/{examResult}/certificate', [\App\Http\Controllers\Student\ExamController::class, 'downloadCertificate'])->name('certificate');
        Route::get('/{examResult}/explanation', [\App\Http\Controllers\Student\ExamController::class, 'explanation'])->name('explanation');
        Route::post('/{examResult}/next-subtest', [\App\Http\Controllers\Student\ExamController::class, 'nextSubtest'])->name('next-subtest');
        Route::post('/{examResult}/save-answer', [\App\Http\Controllers\Student\ExamController::class, 'saveAnswer'])->name('save-answer');
        Route::post('/{examResult}/log-violation', [\App\Http\Controllers\Student\ExamController::class, 'logViolation'])->name('log-violation');
        Route::post('/{examResult}/finish', [\App\Http\Controllers\Student\ExamController::class, 'finish'])->name('finish');
    });

    // Admin Panel
    Route::middleware(['role:admin|super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('users/download-template', [\App\Http\Controllers\Admin\UserController::class, 'downloadTemplate'])->name('users.download-template');
        Route::post('users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        
        // Campus & Prodi management
        Route::get('campus-prodis', [\App\Http\Controllers\Admin\CampusProdiController::class, 'index'])->name('campus-prodis.index');
        Route::post('campus-prodis/upload', [\App\Http\Controllers\Admin\CampusProdiController::class, 'upload'])->name('campus-prodis.upload');
        Route::post('campus-prodis/import', [\App\Http\Controllers\Admin\CampusProdiController::class, 'import'])->name('campus-prodis.import');
        Route::get('campus-prodis/prodis', [\App\Http\Controllers\Admin\CampusProdiController::class, 'getProdisByCampus'])->name('campus-prodis.prodis');
        Route::delete('campus-prodis/destroy-all', [\App\Http\Controllers\Admin\CampusProdiController::class, 'destroyAll'])->name('campus-prodis.destroy-all');

        Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
        Route::post('subjects/import', [\App\Http\Controllers\Admin\SubjectController::class, 'import'])->name('subjects.import');

        Route::get('questions/download-template', [\App\Http\Controllers\Admin\QuestionController::class, 'downloadTemplate'])->name('questions.download-template');
        Route::post('questions/upload-image', [\App\Http\Controllers\Admin\QuestionImageController::class, 'upload'])->name('questions.upload-image');
        Route::post('questions/import-word', [\App\Http\Controllers\Admin\QuestionController::class, 'importWord'])->name('questions.import-word');
        Route::post('questions/bulk-delete', [\App\Http\Controllers\Admin\QuestionController::class, 'bulkDelete'])->name('questions.bulk-delete');
        Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
        
        // System Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

        // Exam Packages
        Route::resource('exam-packages', \App\Http\Controllers\Admin\ExamPackageController::class);
        Route::get('exam-packages/{examPackage}/subtests/{examSubtest}/manage-questions', [\App\Http\Controllers\Admin\ExamPackageController::class, 'manageQuestions'])->name('exam-packages.subtests.manage-questions');
        Route::post('exam-packages/{examPackage}/subtests/{examSubtest}/update-questions', [\App\Http\Controllers\Admin\ExamPackageController::class, 'updateQuestions'])->name('exam-packages.subtests.update-questions');

        // Exam Sessions
        Route::get('exam-sessions/{examSession}/export-excel', [\App\Http\Controllers\Admin\ExamSessionController::class, 'exportExcel'])->name('exam-sessions.export-excel');
        Route::get('exam-sessions/{examSession}/export-pdf', [\App\Http\Controllers\Admin\ExamSessionController::class, 'exportPdf'])->name('exam-sessions.export-pdf');
        Route::post('exam-sessions/{examSession}/reset-student/{examResult}', [\App\Http\Controllers\Admin\ExamSessionController::class, 'resetStudent'])->name('exam-sessions.reset-student');
        Route::resource('exam-sessions', \App\Http\Controllers\Admin\ExamSessionController::class);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\RegionApiController;

Route::get('/api/regions/provinces', [RegionApiController::class, 'getProvinces'])->name('api.provinces');
Route::get('/api/regions/cities', [RegionApiController::class, 'getCities'])->name('api.cities');
Route::get('/api/regions/campuses', [RegionApiController::class, 'getCampuses'])->name('api.campuses');

require __DIR__.'/auth.php';

