<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentReportController;
use App\Http\Controllers\UserController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect(route('login'));
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    
    Route::get('member/index',[MemberController::class,'index'])->name('members.index');
    Route::post('member/store',[MemberController::class,'store'])->name('members.store');
    Route::put('member/{id}',[MemberController::class,'update'])->name('members.update');
    Route::delete('member/{id}',[MemberController::class,'destroy'])->name('members.destroy');
    Route::post('member/{id}/forgive-penalty',[MemberController::class,'forgivePenalty'])->name('members.forgive-penalty');
    

    Route::get('collections/index',[CollectionController::class,'index'])->name('collections.index');
    Route::get('collections/{member}',[CollectionController::class,'show'])->name('collections.show');
    Route::post('collections/store-payment',[CollectionController::class,'storePayment'])->name('collections.storePayment');
    
    Route::get('payments/report', [PaymentReportController::class, 'index'])->name('payments.report');
    Route::get('payments/download-pdf', [PaymentReportController::class, 'downloadPdf'])->name('payments.download-pdf');
    Route::delete('payments/{paymentId}', [PaymentReportController::class, 'deletePayment'])->name('payments.delete');
    
    Route::get('penalties/report', [PaymentReportController::class, 'penaltyReport'])->name('penalties.report');
    Route::get('penalties/download-pdf', [PaymentReportController::class, 'penaltyDownloadPdf'])->name('penalties.download-pdf');
    
    Route::get('unpaid/report', [PaymentReportController::class, 'unpaidReport'])->name('unpaid.report');
    Route::get('unpaid/download-pdf', [PaymentReportController::class, 'unpaidDownloadPdf'])->name('unpaid.download-pdf');
    
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // User Management Routes - Only Admin
    Route::middleware('admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{user}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
    });
});
