@extends('layouts.equip')

@section('title', 'Editar Partit')

@section('content')
<h1 class="text-2xl font-bold mb-4">Editar Partit</h1>

@if ($errors->any())
  <div class="bg-red-100 text-red-700 p-2 mb-4">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('partits.update', $partit) }}" method="POST" class="space-y-4">
  @csrf
  @method('PUT')

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="local_id" class="block font-bold">Equip Local:</label>
        <select name="local_id" id="local_id" class="border p-2 w-full">
          @foreach ($equips as $equip)
            <option value="{{ $equip->id }}" {{ old('local_id', $partit->local_id) == $equip->id ? 'selected' : '' }}>
              {{ $equip->nom }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label for="visitant_id" class="block font-bold">Equip Visitant:</label>
        <select name="visitant_id" id="visitant_id" class="border p-2 w-full">
          @foreach ($equips as $equip)
            <option value="{{ $equip->id }}" {{ old('visitant_id', $partit->visitant_id) == $equip->id ? 'selected' : '' }}>
              {{ $equip->nom }}
            </option>
          @endforeach
        </select>
      </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label for="gols_local" class="block font-bold">Gols Local:</label>
        <input type="number" name="gols_local" id="gols_local" value="{{ old('gols_local', $partit->gols_local) }}" class="border p-2 w-full">
      </div>

      <div>
        <label for="gols_visitant" class="block font-bold">Gols Visitant:</label>
        <input type="number" name="gols_visitant" id="gols_visitant" value="{{ old('gols_visitant', $partit->gols_visitant) }}" class="border p-2 w-full">
      </div>
  </div>

  <div>
    <label for="data_partit" class="block font-bold">Data del Partit:</label>
    <input type="datetime-local" name="data_partit" id="data_partit" value="{{ old('data_partit', $partit->data_partit->format('Y-m-d\TH:i')) }}" class="border p-2 w-full">
  </div>

  <button type="submit" class="btn btn--primary">Actualitzar</button>
</form>
@endsection
