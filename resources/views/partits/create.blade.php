@extends('layouts.equip')

@section('title', __('Crear Partit'))

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ __('Crear Partit') }}</h1>

@if ($errors->any())
  <div class="bg-red-100 text-red-700 p-2 mb-4">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('partits.store') }}" method="POST" class="space-y-4">
  @csrf
  <div>
    <label for="local_id" class="block font-bold">{{ __('Equip Local') }}:</label>
    <select name="local_id" id="local_id" class="border p-2 w-full">
      @foreach ($equips as $equip)
        <option value="{{ $equip->id }}" {{ old('local_id') == $equip->id ? 'selected' : '' }}>
          {{ $equip->nom }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="visitant_id" class="block font-bold">{{ __('Equip Visitant') }}:</label>
    <select name="visitant_id" id="visitant_id" class="border p-2 w-full">
      @foreach ($equips as $equip)
        <option value="{{ $equip->id }}" {{ old('visitant_id') == $equip->id ? 'selected' : '' }}>
          {{ $equip->nom }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="data_partit" class="block font-bold">{{ __('Data del Partit') }}:</label>
    <input type="datetime-local" name="data_partit" id="data_partit" value="{{ old('data_partit') }}" class="border p-2 w-full">
  </div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label for="gols_local" class="block font-bold">{{ __('Gols Local') }}:</label>
      <input type="number" name="gols_local" id="gols_local" value="{{ old('gols_local') }}" class="border p-2 w-full">
    </div>
    <div>
      <label for="gols_visitant" class="block font-bold">{{ __('Gols Visitant') }}:</label>
      <input type="number" name="gols_visitant" id="gols_visitant" value="{{ old('gols_visitant') }}" class="border p-2 w-full">
    </div>
  </div>

  <button type="submit" class="btn btn--primary">{{ __('Crear') }}</button>
</form>
@endsection
