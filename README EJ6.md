# 5.2 🔑 Configurar l’autenticació amb Laravel Breeze (Blade + Alpine + PHPUnit)

## Objectiu
Afegir autenticació al projecte (login, registre, recuperació de contrasenya, etc.) amb **Laravel Breeze** i integrar-ho amb el layout de l’app.

> 📌 En esta pràctica, per seguir les instruccions originals, farem servir un layout anomenat `layouts.equip`.  
> Encara que el projecte tinga més mòduls, **és només un nom de fitxer/layout**: el pots usar com a layout “global” igualment.

---

## 1) Renombrar el layout

### 1.1 Canviar el nom del fitxer
Renombra el fitxer:

- **De:** `resources/views/layouts/app.blade.php` 
- **A:** `resources/views/layouts/equip.blade.php`

📌 Opcional (recomanat): abans de renombrar, guarda una còpia per si cal tornar arrere.

---

## 2) Guardar les rutes abans d’instal·lar Breeze

### 2.1 Per què ho fem
Breeze afegeix rutes noves com `/login`, `/register`, `/forgot-password`, `/dashboard`, etc. 
Guardar un “snapshot” ajuda a detectar canvis i assegurar que les rutes del projecte continuen bé.

### 2.2 Guardar rutes en un fitxer
Crea la carpeta `docs/` si no existeix i guarda les rutes:

```bash
mkdir -p docs
make artisan CMD="route:list > docs/routes_abans_breeze.txt"
```

---

## 3) Instal·lar Laravel Breeze

Así podrás hacer lo mismo que con artisan pero para composer.

Añade esto e Makefile:
```
composer:
	@docker compose run --rm app composer $(CMD)
	@true
```

Executa:

```bash
make composer CMD="require laravel/breeze --dev"
make artisan CMD="breeze:install"
npm install && npm run build
make artisan CMD="migrate"
```

Quan l’assistent (wizard) et pregunte, tria:
- **Blade with Alpine**
- **with dark**
- **PHPUnit**

---

## 4) Canviar totes les vistes per a que extenguen de `layouts.equip`

Ara que el layout s’ha renombrat, cal actualitzar totes les vistes.

En la part superior abans (exemple típic):
```blade
@extends('layouts.app')
```

Després:
```blade
@extends('layouts.equip')
```

On mirar:
- `resources/views/equips/*.blade.php`
- i qualsevol altra vista del projecte que estiga usant el layout anterior.

📌 Recorda:
- `@section('content') ... @endsection` continua igual.
- Si tens `@section('title')`, també continua igual.

---

## 5) Reutilitzar el layout de Breeze dins de `layouts.equip`

Breeze fa servir un component base (`<x-app-layout>`) en el `dashboard`. 
Ara actualitzarem el nostre layout `layouts.equip` perquè reutilitze eixe mateix “marc” (estils, header, etc.).

### 5.1 Actualitzar `resources/views/layouts/equip.blade.php`
Substitueix el contingut de `resources/views/layouts/equip.blade.php` per:

```blade
{{-- resources/views/layouts/equip.blade.php --}}
<x-app-layout>
   <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @yield('title')
        </h2>
    </x-slot>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
```

✅ Ara, totes les vistes que facen `@extends('layouts.equip')`:
- tindran el mateix disseny que el `dashboard` de Breeze
- tindran el header amb el títol (`@yield('title')`)
- mostraran missatges `success` de session

📌 Recomanació:
En les vistes, assegura’t de tindre:
```blade
@section('title', 'Títol de la pàgina')
```
o bé:
```blade
@section('title')
    Títol de la pàgina
@endsection
```

---

## 6) Guardar rutes després d’instal·lar Breeze i comparar

Guardar rutes després:

```bash
php artisan route:list > docs/routes_despres_breeze.txt
```

Comparar (Linux/Mac/Git Bash):

```bash
diff docs/routes_abans_breeze.txt docs/routes_despres_breeze.txt
```

---

Si volem les rutes protegides per login per a edit/update/destroy i publiques per a index i show en routes/web.php:

```
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EquipController;
use App\Http\Controllers\EstadiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ✅ Públicos: SOLO index (para evitar conflicto con /create)
Route::resource('equips', EquipController::class)->only(['index']);
Route::resource('estadis', EstadiController::class)->only(['index']);


// 🔒 Protegidos: crear/editar/borrar (y store/update/destroy)
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('equips', EquipController::class)->except(['index', 'show']);
    Route::resource('estadis', EstadiController::class)->except(['index', 'show']);
});


// ✅ Públicos: show AL FINAL (así /create no lo captura {id})
Route::resource('equips', EquipController::class)->only(['show']);
Route::resource('estadis', EstadiController::class)->only(['show']);

require __DIR__.'/auth.php';

```
Per últim hem de modificar view/layaouts/navigation.blade.php per a que aparega el menú de de acceder, register i deshboard quan toque:

```
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('equips.index')" :active="request()->routeIs('equips.*')">
                        {{ __('Equips') }}
                    </x-nav-link>

                    <x-nav-link :href="route('estadis.index')" :active="request()->routeIs('estadis.*')">
                        {{ __('Estadis') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Log in') }}
                    </a>

                    <a href="{{ route('register') }}" class="text-sm text-gray-700 dark:text-gray-300">
                        {{ __('Register') }}
                    </a>
                </div>
            @endauth

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('equips.index')" :active="request()->routeIs('equips.*')">
                {{ __('Equips') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('estadis.index')" :active="request()->routeIs('estadis.*')">
                {{ __('Estadis') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('register') ">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        @endauth
    </div>
</nav>

```
 
## 7) Comprovar que funciona

1) Limpia cache:
```bash
make artisan CMD="route:clear"
```

2) Provar rutes d’autenticació:
- `/register`
- `/login`
- `/dashboard`

3) Provar les rutes del projecte (les que tingues):
- `/equips` `/estadis`
- (i la resta de mòduls del projecte)

---

## Errors típics i solucions

### No es veuen estils (Tailwind)
Reconstrueix el front:
```bash
npm run build
```

### Error “View [layouts.equip] not found”
Comprova:
- Existeix `resources/views/layouts/equip.blade.php`
- Les vistes tenen `@extends('layouts.equip')` (i no `layouts.app`)

### Error de `@yield('title')` buit
Si el header ix buit, és perquè la vista no defineix la secció `title`. 
Solució: afegeix en la vista:
```blade
@section('title', 'Títol')
```

---

## Checklist final
- [ ] He renombrat `app.blade.php` a `equip.blade.php`
- [ ] He guardat `docs/routes_abans_breeze.txt`
- [ ] He instal·lat Breeze (Blade + Alpine + PHPUnit)
- [ ] He passat migracions (`php artisan migrate`)
- [ ] He canviat les vistes a `@extends('layouts.equip')`
- [ ] He actualitzat `layouts/equip.blade.php` per reutilitzar `<x-app-layout>`
- [ ] He guardat `docs/routes_despres_breeze.txt` i he comparat
- [ ] Login / Register / Dashboard funcionen
- [ ] Les rutes del projecte continuen funcionant
