<?php

use App\Models\LandingPage;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    $landing_page = LandingPage::first();
    return view('landing-page', compact('landing_page'));
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
});

Route::post('/trix/upload', function (Request $request) {
    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('uploads', 'public');
        return response()->json(['url' => Storage::url($path)]);
    }
    return response()->json(['error' => 'Upload gagal'], 400);
})->name('trix.upload');

Route::post('/trix/delete', function (Request $request) {
    $fileUrl = $request->input('fileUrl');
    return $fileUrl;
    if ($fileUrl) {
        $path = str_replace('/storage/', '', parse_url($fileUrl, PHP_URL_PATH));

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
            return response()->json(['message' => 'File deleted successfully']);
        }
    }
    return response()->json(['error' => 'File not found'], 400);
})->name('trix.delete');

require __DIR__ . '/auth.php';
