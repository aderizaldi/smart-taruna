<?php

use Livewire\Volt\Volt;
use App\Models\LandingPage;
use Illuminate\Http\Request;
use App\Models\LandingPageImage;
use Illuminate\Support\Facades\Route;
use App\Models\LandingPageAchievement;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $landing_page = LandingPage::first();
    $landing_page_images = LandingPageImage::get();
    $landing_page_achievements = LandingPageAchievement::get();
    $total_image = count($landing_page_images);
    return view('landing-page', compact('landing_page', 'landing_page_images', 'landing_page_achievements', 'total_image'));
})->name('home')->middleware('guest');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Volt::route('dashboard/user-management', 'dashboard.user-management')->name('dashboard.user-management');
    Volt::route('dashboard/landing-page-management', 'dashboard.landing-page-management')->name('dashboard.landing-page-management');
    Volt::route('dashboard/gallery', 'dashboard.gallery-management')->name('dashboard.gallery');
    Volt::route('dashboard/achievement', 'dashboard.achievement-management')->name('dashboard.achievement');
    Volt::route('dashboard/exam-type', 'dashboard.exam-type')->name('dashboard.exam-type');
    Volt::route('dashboard/exam-type/{type}', 'dashboard.detail-exam-type')->name('dashboard.detail-exam-type');
    Volt::route('dashboard/exam-package', 'dashboard.exam-package')->name('dashboard.exam-package');
    Volt::route('dashboard/exam', 'dashboard.exam')->name('dashboard.exam');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Volt::route('user/package', 'user.package')->name('user.package');
    Volt::route('user/{packageId}/exam', 'user.package-exam')->name('user.package-exam');
});

require __DIR__ . '/auth.php';
