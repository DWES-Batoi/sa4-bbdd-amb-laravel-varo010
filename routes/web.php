<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EquipController;
use App\Http\Controllers\EstadiController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartitController;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
// Language switcher route
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['ca', 'es', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.switch');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// ✅ Públicos: SOLO index (para evitar conflicto con /create)
Route::resource('equips', EquipController::class)->only(['index']);
Route::resource('estadis', EstadiController::class)->only(['index']);
Route::resource('jugadors', JugadorController::class)->only(['index']);
Route::resource('partits', PartitController::class)->only(['index']);
// 🔒 Protegidos: crear/editar/borrar (y store/update/destroy)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('equips', EquipController::class)->except(['index', 'show']);
    Route::resource('estadis', EstadiController::class)->except(['index', 'show']);
    Route::resource('jugadors', JugadorController::class)->except(['index', 'show']);
    Route::resource('partits', PartitController::class)->except(['index', 'show']);
});
// ✅ Públicos: show AL FINAL (así /create no lo captura {id})
Route::resource('equips', EquipController::class)->only(['show']);
Route::resource('estadis', EstadiController::class)->only(['show']);
Route::resource('jugadors', JugadorController::class)->only(['show']);
Route::resource('partits', PartitController::class)->only(['show']);
require __DIR__.'/auth.php';