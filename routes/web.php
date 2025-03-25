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
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Volt::route('user/exam', 'user.exam')->name('user.exam');
});

require __DIR__ . '/auth.php';
