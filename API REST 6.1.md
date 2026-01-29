# Pràctica 6.1 — Crear una API/REST (Laravel 12) (Jugadora / Jugadores)

> **Objectiu:** crear una **API REST** per a l’entitat **Jugadora** (plural **jugadores**) amb operacions CRUD en JSON i, opcionalment, protegir rutes amb **Laravel Sanctum** (tokens).

---

## 0) Recordatori ràpid: què és una API REST?

- **API**: interfície perquè un client extern (web, mòbil, desktop…) consumisca dades del teu servidor.
- **REST**: estil d’arquitectura basat en:
  - **Stateless**: cada petició porta tota la info necessària (no “sessió” com al web tradicional).
  - **Mètodes HTTP**: `GET`, `POST`, `PUT/PATCH`, `DELETE`.
  - **Format**: habitualment **JSON** (aquesta pràctica).

---

## 1) Instal·lar dependència (si falta)
Laravel 12 ja porta el client HTTP (basat en Guzzle). Laravel utilitza una llibreria molt coneguda de PHP que es diu Guzzle per fer realment la connexió HTTP.
Tu normalment no uses Guzzle directament, uses Http::get(), Http::post(), etc., i Laravel ja s’encarrega de Guzzle. Si l’has eliminat has de instalar:

Primer comprobar
```bash
make composer CMD="show guzzlehttp/guzzle"
```
Si no sale: name : guzzlehttp/guzzle Instalar:

```bash
make composer CMD="require guzzlehttp/guzzle"
```


## 2) Preparar Laravel per a API (Sanctum)

> En Laravel 12, `install:api` instala Sanctum i el middleware preparats. Sanctum és el sistema d’autenticació “lleuger” de Laravel pensat sobretot per a APIs. Middleware fa comprobacións abans i després de fer entrega dels datos.

```bash
make artisan CMD="install:api"
```

Això:
- crea/actualitza migracions de tokens,
- registra middleware per a autenticació via **Sanctum**.

---

## 3) Controlador API per a Jugadora (CRUD)

### 3.1 Crear controlador API
```bash
make artisan CMD="make:controller Api/JugadoraController --api --model=Jugadora"
```

### 3.2 Definir rutes (endpoints) afig a `routes/api.php`

- En una API la ruta es el “camí” que Laravel escolta dins del teu projecte.
Exemples:

/api/jugadores

/api/jugadores/1

/api/login

- I els Endpoint es una ruta + un mètode HTTP (perquè la mateixa ruta pot fer coses diferents segons el mètode).

Per exemple, la ruta /api/jugadores té dos endpoints diferents:

GET /api/jugadores → llista jugadores

POST /api/jugadores → crea una jugadora

```php
use App\Http\Controllers\Api\JugadoraController;

Route::apiResource('jugadores', JugadoraController::class)
    ->parameters(['jugadores' => 'jugadora']);
```

📌 Important:
- El fitxer `routes/api.php` ja porta el prefix `api/`.
- Per tant, els endpoints són:
  - `GET    /api/jugadores`
  - `POST   /api/jugadores`
  - `GET    /api/jugadores/{jugadora}`
  - `PUT    /api/jugadores/{jugadora}`
  - `PATCH  /api/jugadores/{jugadora}`
  - `DELETE /api/jugadores/{jugadora}`

### 3.3 Comprovar rutes

```bash
make artisan CMD="route:list --path=api"
```
---

## 4) Implementar serveis GET (index + show) solicitud d'informació

A `app/Http/Controllers/Api/JugadoraController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jugadora;
use Illuminate\Http\Request;

class JugadoraController extends Controller
{
    public function index()
    {
        return Jugadora::query()->get(); // JSON automàtic
    }

    public function show(Jugadora $jugadora)
    {
        return $jugadora; // JSON automàtic (Route Model Binding)
    }
}
```

✅ Prova ràpida en navegador:
- `http://localhost:8000/api/jugadores`
- `http://localhost:8000/api/jugadores/1`

---

## 5) Respostes JSON i codis d’estat (recomanat)

Encara que Laravel pot retornar models directament, és millor controlar:
- format JSON,
- codis d’estat (200, 201, 204…).

Exemple:

```php
public function show(Jugadora $jugadora)
{
    return response()->json($jugadora, 200);
}
```

Codis útils:
- `200` OK
- `201` Creat
- `204` Sense contingut (DELETE)
- `400` Petició incorrecta
- `401` No autoritzat
- `404` No trobat
- `500` Error servidor

---

## 6) Validació amb Form Request (POST/PUT/PATCH)

Un Form Request (com JugadoraRequest) servix per a validar les dades que entren a l’API en POST/PUT/PATCH per a guarda dades en la BBDD.

### 6.1 Crear el Request
```bash
make artisan CMD="make:request JugadoraRequest"
```

### 6.2 Regles a `app/Http/Requests/JugadoraRequest.php`
```php
public function rules(): array
{
    return [
        'nom' => ['required', 'string', 'max:255'],
        'equip_id' => ['required', 'exists:equips,id'],
        'posicio' => ['nullable', 'string', 'max:100'],
        'dorsal' => ['nullable', 'integer', 'min:0', 'max:99'],
        'edat' => ['nullable', 'integer', 'min:0', 'max:120'],
    ];
}
```

---

## 7) Implementar POST / PUT / DELETE (CRUD complet)

Afig a  `app/Http/Controllers/Api/JugadoraController.php`:

```php

public function store(JugadoraRequest $request)
{
    $jugadora = Jugadora::create($request->validated());

    return response()->json($jugadora, 201);
}

public function update(JugadoraRequest $request, Jugadora $jugadora)
{
    $jugadora->update($request->validated());

    return response()->json($jugadora, 200);
}

public function destroy(Jugadora $jugadora)
{
    $jugadora->delete();

    return response()->noContent(); // 204
}
```

### 7.1 IMPORTANT: Mass Assignment al model

Quan fem create() o update() en Laravel, estem dient-li: “agafa totes estes dades i posa-les dins del registre”.

Per seguretat, Laravel no ho permet si abans no li dius quins camps són “segurs” i es poden omplir així.

Asegurat que al model `app/Models/Jugadora.php` has de tindre:

```php
  protected $fillable = [
        'nom', 'equip_id', 'posicio', 'dorsal', 'edat',
    ];
```

---

## 8) API Resources (format JSON professional)

Els **Resources** separen el format de la resposta de la lògica del controlador.
Això és un API Resource: servix per a decidir exactament quins camps i amb quin format torna la teua API quan respons amb una jugadora.
En lloc de tornar el model “tal qual”, tu controles el JSON d’eixida dins de toArray().

### 8.1 Crear Resource
```bash
make artisan CMD="make:resource JugadoraResource"
```

### 8.2 Afig el format a `app/Http/Resources/JugadoraResource.php`
```php
public function toArray($request): array
{
    return [
        'id' => $this->id,
        'nom' => $this->nom,
        'equip_id' => $this->equip_id,
        'posicio' => $this->posicio,
        'dorsal' => $this->dorsal,
        'edat' => $this->edat,

        // Exemple si tens relació:
        // 'equip' => $this->whenLoaded('equip', fn () => $this->equip->nom),
    ];
}
```

### 8.3 Usar Resource al controlador en api/JugadoraController.php
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JugadoraRequest;
use App\Http\Resources\JugadoraResource;
use App\Models\Jugadora;

class JugadoraController extends Controller
{
    public function index()
    {
        return JugadoraResource::collection(Jugadora::query()->get());
    }

    public function show(Jugadora $jugadora)
    {
        return new JugadoraResource($jugadora);
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'nom' => ['required', 'string', 'max:255'],
        'equip_id' => ['required', 'exists:equips,id'],
        'posicio' => ['nullable', 'string', 'max:100'],
        'dorsal' => ['nullable', 'integer', 'min:0', 'max:99'],
        'edat' => ['nullable', 'integer', 'min:0', 'max:120'],
    ]);

    $jugadora = \App\Models\Jugadora::create($data);

    return response()->json($jugadora->load('equip'), 201);
    }

    public function update(JugadoraRequest $request, Jugadora $jugadora)
    {
        $jugadora->update($request->validated());

        return new JugadoraResource($jugadora);
    }

    public function destroy(Jugadora $jugadora)
    {
        $jugadora->delete();

        return response()->noContent(); // 204
    }
}

```

---

## 9) Fes paginació en api/JugadoraController.php(recomanat per llistats grans)
Has de camviar el index: 

```php

public function index()
{
    return JugadoraResource::collection(
        Jugadora::query()->paginate(10)
    );
}
```

Prova:
- `GET /api/jugadores?page=2` o lo que es lo mateis: `http://localhost:8000/api/jugadores?page=2`

---

## 10) Errors en JSON per a l’API (recomanat)

Objectiu: que qualsevol error en rutes `api/*` retorne **JSON** (no una vista HTML).

A `bootstrap/app.php` (zona `withExceptions`), canvia per asto:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', SetLocale::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $statusCode);
            }

            // Si no es /api/*, dejamos que Laravel renderice normal (web)
            return null;
        });

    })
    ->create();

```

---


## 11)Protegir l’API amb Sanctum (tokens)

### 11.1 Afegir `HasApiTokens` a `User`
A `app/Models/User.php`:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

### 11.2 BaseController (respostes consistents)
Crea `app/Http/Controllers/Api/BaseController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    protected function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $message,
        ], $code);
    }

    protected function sendError($error, $info = [], $code = 400)
    {
        $payload = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($info)) {
            $payload['info'] = $info;
        }

        return response()->json($payload, $code);
    }
}
```

### 11.3 Crear `AuthController`
```bash
make artisan CMD="make:controller Api/AuthController"
```

`app/Http/Controllers/Api/AuthController.php`:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Unauthorised', ['error' => 'Credencials incorrectes'], 401);
        }

        $user = $request->user();
        $result = [
            'token' => $user->createToken('api')->plainTextToken,
            'name' => $user->name,
        ];

        return $this->sendResponse($result, 'User signed in', 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required','min:6'],
            'confirm_password' => ['required','same:password'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors(), 422);
        }

        $data = $validator->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $result = [
            'token' => $user->createToken('api')->plainTextToken,
            'name' => $user->name,
        ];

        return $this->sendResponse($result, 'User created successfully', 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse(['name' => $request->user()->name], 'User successfully signed out', 200);
    }
}
```

### 11.4 Rutes d’autenticació a `routes/api.php`
```php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JugadoraController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Exemple: protegim els endpoints d'escriptura
    Route::apiResource('jugadores', JugadoraController::class)
        ->parameters(['jugadores' => 'jugadora'])
        ->except(['index','show']);
});

// Endpoints públics (lectura)
Route::apiResource('jugadores', JugadoraController::class)
    ->parameters(['jugadores' => 'jugadora'])
    ->only(['index','show']);
```

---



## 11) Provar l’API amb Bruno (Aquesta aplicación sustitueix a Postman que hi ha que resgistrarse)

Instalem BRUNO `flatpak install flathub com.usebruno.Bruno`

### 13.1 Login
Crea uno nou Collection i després neu Request amb:
- Method: **POST**
- URL: `http://localhost:8000/api/login`
- Body → raw → JSON:

```json
{ "email": "admin@example.com", "password": "password" }

```
Resposta → copia `token`.

### 11.1 GET (llistat)
- Method: **GET**
- URL: `http://localhost:8000/api/jugadores`

### 11.2 GET (fitxa)
- Method: **GET**
- URL: `http://localhost:8000/api/jugadores/1`

### 11.3 POST (crear) Ho fem am BRUNO. POST y PUT que modifica la BBDD necessita token

- Method: POST
- Method: **POST**
- URL: `http://localhost:8000/api/jugadores`
- Headers:
    Accept: application/json
    Content-Type: application/json
- Ves a Auth
    Selecciona Bearer Token
    En Token pega el token complet (id|token), per exemple:
    1|C1oYnPc1RE4x...
- Body → JSON:

```json
{
  "nom": "Aitana Bonmatí",
  "posicio": "Migcampista",
  "dorsal": 14,
  "edat": 26,
  "equip_id": 1
}
```
- Send

### 11.4 PUT (actualitzar)
- New Request
- Method: **PUT**
- URL: `http://localhost:8000/api/jugadores/1`
- Headers:
    Accept: application/json
    Content-Type: application/json
- Ves a Auth
    Selecciona Bearer Token
    En Token pega el token complet (id|token), per exemple:
    1|C1oYnPc1RE4x...
- Body → JSON:


```json
{
  "nom": "Aitana Bonmatí",
  "posicio": "Migcampista",
  "dorsal": 14,
   "edat": 28,
  "equip_id": 1
}
```

### 11.5 DELETE (esborrar)
- Method: **DELETE**
- URL: `http://localhost:8000/api/jugadores/1`

---

# Annex — Problemes típics i com resoldre’ls (debug)

Aquesta secció recull els errors més habituals que ens han eixit fent l’API de **Jugadora/Jugadores** i **quins canvis exactes** hem fet per solucionar-los.

> **Nota:** en cada punt indique si és **codi nou** o **modificació** d’un fitxer existent.

---

## A) 500 / errors “estranys” en API i Bruno no mostra res

**Símptoma**
- En Bruno o Postman: `500 Internal Server Error` i a voltes “0B”.
- En `storage/logs/laravel.log` apareix un error relacionat amb `bootstrap/app.php` i una `closure`.

**Causa**
- El render d’excepcions en `bootstrap/app.php` tenia imports/signatura incorrectes.

✅ **Solució (MODIFICACIÓ)** — `bootstrap/app.php` 
Assegura’t que `withExceptions` inclou aquest render per a `/api/*`:

```php
use Illuminate\Http\Request;

->withExceptions(function (Exceptions $exceptions): void {

    $exceptions->render(function (\Throwable $e, Request $request) {
        if ($request->is('api/*')) {
            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }

        // fora d'API, que Laravel renderitze normal
        return null;
    });

})
```

📌 Important:
- **NO** poses `use Throwable;` (no cal).
- Usa **`\Throwable`** (amb barra).

---

## B) 403 `This action is unauthorized.` fent PUT/DELETE (encara amb token)

**Símptoma**
- En Bruno:
```json
{ "success": false, "message": "This action is unauthorized." }
```

**Causes típiques**
1) Policy denega perquè comprova un camp incorrecte (`rol` vs `role`) 
2) S’està autoritzant amb `authorizeResource()` i la Policy retorna `false`

✅ **Solució (MODIFICACIÓ)** — `app/Policies/JugadoraPolicy.php` 
En el nostre projecte, el camp de l’usuari és **`role`** (es veu en `GET /api/user`), no `rol`. Per tant la Policy ha de mirar:

```php
return ($user->role ?? null) === 'administrador';
```

Policy completa recomanada (lectura pública i escriptura només admin):

```php
<?php

namespace App\Policies;

use App\Models\Jugadora;
use App\Models\User;

class JugadoraPolicy
{
    private function isAdmin(User $user): bool
    {
        return ($user->role ?? null) === 'administrador';
    }

    // Lectura pública (si vols que GET siga públic)
    public function viewAny(?User $user = null): bool { return true; }
    public function view(?User $user = null, ?Jugadora $jugadora = null): bool { return true; }

    // Escriptura només admin
    public function create(User $user): bool { return $this->isAdmin($user); }
    public function update(User $user, Jugadora $jugadora): bool { return $this->isAdmin($user); }
    public function delete(User $user, Jugadora $jugadora): bool { return $this->isAdmin($user); }

    public function restore(User $user, Jugadora $jugadora): bool { return $this->isAdmin($user); }
    public function forceDelete(User $user, Jugadora $jugadora): bool { return $this->isAdmin($user); }
}
```

> Si vols que **també** la lectura estiga protegida, canvia `viewAny/view` i retorna `$this->isAdmin($user)`.

---

## C) Error: `authorizeResource()` undefined o `middleware()` undefined

**Símptoma**
- Errors com:
  - `Call to undefined method ... authorizeResource()`
  - `Call to undefined method ... middleware()`

**Causa**
- El `Controller` base no hereta de `Illuminate\Routing\Controller` o no té el trait `AuthorizesRequests`.

✅ **Solució (MODIFICACIÓ)** — `app/Http/Controllers/Controller.php` 
Deixa’l així:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
```

---

## D) Token Sanctum: no funciona si no pegues `id|token`

**Símptoma**
- Fas login correctament però les rutes protegides tornen 401/403.

**Causa**
- Has pegat només la part llarga del token i no el format complet.

✅ **Solució (ÚS CORRECTE, no és codi)** 
En Bruno, el Bearer token ha de ser **complet**:

- ✅ `1|C1oYnPc1RE4xBpWrPMXIX...`
- ❌ `C1oYnPc1RE4xBpWrPMXIX...`

**Bruno → Auth → Bearer Token → pega el token complet**

---

## E) Rutes API duplicades (conflictes)

**Símptoma**
- Comportaments inconsistents: a vegades agafa rutes no protegides, o rutes repetides.

**Causa**
- `Route::apiResource('jugadores'...)` estava definit 2 o 3 vegades.

✅ **Solució (MODIFICACIÓ)** — `routes/api.php` 
Deixa **una sola definició** per lectura pública i una per escriptura protegida.

### Rutes recomanades (NETES)

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JugadoraController;

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protegides (escriptura)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('jugadores', JugadoraController::class)
        ->parameters(['jugadores' => 'jugadora'])
        ->except(['index', 'show']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Públiques (lectura)
Route::apiResource('jugadores', JugadoraController::class)
    ->parameters(['jugadores' => 'jugadora'])
    ->only(['index', 'show']);
```

### Endpoints resultants

- **Públics**
  - `GET    /api/jugadores`
  - `GET    /api/jugadores/{jugadora}`

- **Protegits (Bearer token)**
  - `POST   /api/jugadores`
  - `PUT    /api/jugadores/{jugadora}`
  - `PATCH  /api/jugadores/{jugadora}`
  - `DELETE /api/jugadores/{jugadora}`

- **Auth**
  - `POST /api/login`
  - `POST /api/register`
  - `POST /api/logout` *(protegit)*
  - `GET  /api/user` *(protegit)*

---

## F) Camp incorrecte: no tenim `data_naixement`, tenim `edat`

**Símptoma**
- Validació falla (422) o no guarda el camp.
- Estaves enviant `data_naixement` però la taula té `edat`.

✅ **Solució (MODIFICACIÓ)** 
1) En el Request (o validació del controller), canvia a `edat`:
```php
'edat' => ['nullable', 'integer', 'min:0', 'max:120'],
```

2) En el model `app/Models/Jugadora.php` revisa `$fillable`:
```php
protected $fillable = ['nom','equip_id','posicio','dorsal','edat'];
```

---

## G) Comandes útils de “neteja” quan canvies rutes/policies

Quan canvies rutes, policies o configuracions, és recomanat executar:

```bash
make artisan CMD="optimize:clear"
make artisan CMD="route:clear"
```




