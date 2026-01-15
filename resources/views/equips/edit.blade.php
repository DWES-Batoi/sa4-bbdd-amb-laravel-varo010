@extends('layouts.equip')
@section('title', 'Editar equip')

@section('content')
<h1 class="text-2xl font-bold mb-4">Editar equip</h1>

@if ($errors->any())
  <div class="bg-red-100 text-red-700 p-2 mb-4">
    <ul>
      @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('equips.update', $equip) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
  @csrf
  @method('PUT')

  <div>
    <label for="nom" class="block font-bold">Nom:</label>
    <input type="text" name="nom" id="nom" value="{{ old('nom', $equip->nom) }}" class="border p-2 w-full">
  </div>

  <div>
    <label for="estadi_id" class="block font-bold">Estadi:</label>
    <select name="estadi_id" id="estadi_id" class="border p-2 w-full">
      @foreach ($estadis as $estadi)
        <option value="{{ $estadi->id }}" {{ old('estadi_id', $equip->estadi_id) == $estadi->id ? 'selected' : '' }}>
          {{ $estadi->nom }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="titols" class="block font-bold">Títols:</label>
    <input type="number" name="titols" id="titols" value="{{ old('titols', $equip->titols) }}" class="border p-2 w-full">
  </div>

  @if($equip->escut)
    <div class="flex items-center gap-3">
        <img src="{{ asset('storage/' . $equip->escut) }}" class="h-12 w-12 object-cover rounded-full" alt="Escut">
        <p class="text-sm text-gray-600">Escut actual</p>
    </div>
  @endif

  <div>
    <label for="escut" class="block font-bold">Nou escut (opcional):</label>
    <input type="file" name="escut" id="escut" class="border p-2 w-full">
  </div>

  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Actualitzar</button>
</form>
@endsection
