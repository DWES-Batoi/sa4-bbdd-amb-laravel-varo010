@extends('layouts.equip')

@section('title', __('Crear Jugador'))

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ __('Crear Jugador') }}</h1>

@if ($errors->any())
  <div class="bg-red-100 text-red-700 p-2 mb-4">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('jugadors.store') }}" method="POST" class="space-y-4">
  @csrf
  <div>
    <label for="nom" class="block font-bold">{{ __('Nom') }}:</label>
    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="border p-2 w-full">
  </div>

  <div>
    <label for="cognoms" class="block font-bold">{{ __('Cognoms') }}:</label>
    <input type="text" name="cognoms" id="cognoms" value="{{ old('cognoms') }}" class="border p-2 w-full">
  </div>

  <div>
    <label for="dorsal" class="block font-bold">{{ __('Dorsal') }}:</label>
    <input type="number" name="dorsal" id="dorsal" value="{{ old('dorsal') }}" class="border p-2 w-full">
  </div>

  <div>
    <label for="equip_id" class="block font-bold">{{ __('Equip') }}:</label>
    <select name="equip_id" id="equip_id" class="border p-2 w-full">
      @foreach ($equips as $equip)
        <option value="{{ $equip->id }}" {{ old('equip_id') == $equip->id ? 'selected' : '' }}>
          {{ $equip->nom }}
        </option>
      @endforeach
    </select>
  </div>

  <button type="submit" class="btn btn--primary">{{ __('Crear') }}</button>
</form>
@endsection
