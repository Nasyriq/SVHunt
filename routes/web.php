<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CoordinatorController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\QuotaController;
use App\Http\Controllers\TimeframeController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\LecturerAuthController;
use App\Http\Controllers\Student\TopicController;
use App\Http\Controllers\Student\AppointmentController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Lecturer\DashboardController;
use App\Http\Controllers\Lecturer\LecturerTopicController;
use App\Http\Controllers\Lecturer\LecturerAppointmentController;
use App\Http\Controllers\Lecturer\LecturerProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/coordinator/login', [CoordinatorController::class, 'showLoginForm'])->name('coordinator.login');
Route::post('/coordinator/login', [CoordinatorController::class, 'login']);
Route::post('/coordinator/logout', [CoordinatorController::class, 'logout'])->name('coordinator.logout');

// Protected coordinator routes
Route::middleware(['auth:coordinator'])->group(function () {
    Route::get('/coordinator/dashboard', [CoordinatorController::class, 'showDashboard'])->name('coordinator.dashboard');
});

// Student Management Routes
Route::middleware(['auth:coordinator'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('coordinator.students.index');
    Route::post('/students/import', [StudentController::class, 'importCSV'])->name('coordinator.students.import');
    Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('coordinator.students.template');
    Route::get('/students/report', [StudentController::class, 'generateReport'])->name('coordinator.students.report');
    Route::delete('/students/{student}/destroy', [StudentController::class, 'destroy'])->name('coordinator.students.destroy');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('coordinator.students.edit');
    Route::put('/students/{student}/update', [StudentController::class, 'update'])->name('coordinator.students.update');
});

// Lecturer Management Routes
Route::middleware(['auth:coordinator'])->group(function () {
    Route::get('/lecturers', [LecturerController::class, 'index'])->name('coordinator.lecturers.index');
    Route::post('/lecturers/import', [LecturerController::class, 'importCSV'])->name('coordinator.lecturers.import');
    Route::get('/lecturers/template', [LecturerController::class, 'downloadTemplate'])->name('coordinator.lecturers.template');
    Route::get('/lecturers/report', [LecturerController::class, 'generateReport'])->name('coordinator.lecturers.report');
    Route::delete('/lecturers/{lecturer}/destroy', [LecturerController::class, 'destroy'])->name('coordinator.lecturers.destroy');
    Route::get('/lecturers/{lecturer}/edit', [LecturerController::class, 'edit'])->name('coordinator.lecturers.edit');
    Route::put('/lecturers/{lecturer}/update', [LecturerController::class, 'update'])->name('coordinator.lecturers.update');
});

// Quota Management Routes
Route::middleware(['auth:coordinator'])->group(function () {
    Route::get('/quotas', [QuotaController::class, 'index'])->name('coordinator.quotas.index');
    Route::post('/quotas', [QuotaController::class, 'store'])->name('coordinator.quotas.store');
    Route::put('/quotas/{quota}/update', [QuotaController::class, 'update'])->name('coordinator.quotas.update');
    Route::delete('/quotas/{quota}/destroy', [QuotaController::class, 'destroy'])->name('coordinator.quotas.destroy');
});

// Timeframe Management Routes
Route::middleware(['auth:coordinator'])->group(function () {
    Route::get('/timeframe', [TimeframeController::class, 'index'])->name('coordinator.timeframe.index');
    Route::post('/timeframe', [TimeframeController::class, 'store'])->name('coordinator.timeframe.store');
    Route::get('/timeframe/{task}/edit', [TimeframeController::class, 'edit'])->name('coordinator.timeframe.edit');
    Route::put('/timeframe/{task}/update', [TimeframeController::class, 'update'])->name('coordinator.timeframe.update');
    Route::delete('/timeframe/{task}/destroy', [TimeframeController::class, 'destroy'])->name('coordinator.timeframe.destroy');
});

// Student Auth Routes
Route::get('/student/login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('/student/login', [StudentAuthController::class, 'login']);
Route::post('/student/logout', [StudentAuthController::class, 'logout'])->name('student.logout');
Route::get('/student/change-password', [StudentAuthController::class, 'showChangePasswordForm'])
    ->name('student.change-password')
    ->middleware('auth:student');
Route::post('/student/change-password', [StudentAuthController::class, 'changePassword'])
    ->middleware('auth:student');

// Lecturer Auth Routes
Route::get('/lecturer/login', [LecturerAuthController::class, 'showLoginForm'])->name('lecturer.login');
Route::post('/lecturer/login', [LecturerAuthController::class, 'login']);
Route::post('/lecturer/logout', [LecturerAuthController::class, 'logout'])->name('lecturer.logout');
Route::get('/lecturer/change-password', [LecturerAuthController::class, 'showChangePasswordForm'])
    ->name('lecturer.change-password')
    ->middleware('auth:lecturer');
Route::post('/lecturer/change-password', [LecturerAuthController::class, 'changePassword'])
    ->middleware('auth:lecturer');

// Student Routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', [StudentAuthController::class, 'dashboard'])->name('student.dashboard');
    // Add other student routes here later (topic, appointment, profile)
    
    // Topic Routes
    Route::get('/student/topics', [TopicController::class, 'index'])->name('student.topic.index');
    Route::post('/student/topics', [TopicController::class, 'store'])->name('student.topic.store');
    Route::get('/student/topics/{topic}/edit', [TopicController::class, 'edit'])->name('student.topic.edit');
    Route::put('/student/topics/{topic}/update', [TopicController::class, 'update'])->name('student.topic.update');
    Route::delete('/student/topics/{topic}/destroy', [TopicController::class, 'destroy'])->name('student.topic.destroy');

    // Appointment Routes
    Route::get('/student/appointments', [AppointmentController::class, 'index'])->name('student.appointment.index');
    Route::post('/student/appointments', [AppointmentController::class, 'store'])->name('student.appointment.store');
    Route::get('/student/appointment/{appointment}/edit', [AppointmentController::class, 'edit'])->name('student.appointment.edit');
    Route::put('/student/appointments/{appointment}/update', [AppointmentController::class, 'update'])->name('student.appointment.update');
    Route::delete('/student/appointments/{appointment}/destroy', [AppointmentController::class, 'destroy'])->name('student.appointment.destroy');

    // Profile Routes
    Route::get('/student/profile', [ProfileController::class, 'index'])->name('student.profile.index');
    Route::put('/student/profile', [ProfileController::class, 'update'])->name('student.profile.update');
});

// Lecturer Routes
Route::middleware(['auth:lecturer'])->group(function () {
    Route::get('/lecturer/dashboard', [DashboardController::class, 'index'])->name('lecturer.dashboard');
    
    // Topic Routes
    Route::get('/lecturer/topics', [LecturerTopicController::class, 'index'])->name('lecturer.topic.index');
    Route::get('/lecturer/topics/{topic}', [LecturerTopicController::class, 'show'])->name('lecturer.topic.show');
    Route::put('/lecturer/topics/{topic}/update', [LecturerTopicController::class, 'update'])->name('lecturer.topic.update');
    
    // Appointment Routes
    Route::get('/lecturer/appointments', [LecturerAppointmentController::class, 'index'])->name('lecturer.appointment.index');
    Route::put('/lecturer/appointments/{appointment}/update', [LecturerAppointmentController::class, 'update'])->name('lecturer.appointment.update');
    
    // Profile Routes
    Route::get('/lecturer/profile', [LecturerProfileController::class, 'index'])->name('lecturer.profile.index');
    Route::put('/lecturer/profile', [LecturerProfileController::class, 'update'])->name('lecturer.profile.update');
});
