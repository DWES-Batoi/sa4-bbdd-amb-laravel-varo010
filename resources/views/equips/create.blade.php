@extends('layouts.equip')
@section('title', __('Afegir nou equip'))

@section('content')
<h1 class="text-2xl font-bold mb-4">{{ __('Afegir nou equip') }}</h1>

@if ($errors->any())
  <div class="bg-red-100 text-red-700 p-2 mb-4">
    <ul>
      @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('equips.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
  @csrf

  <div>
    <label for="nom" class="block font-bold">{{ __('Nom') }}:</label>
    <input type="text" name="nom" id="nom" value="{{ old('nom') }}" class="border p-2 w-full">
  </div>

  <div>
    <label for="estadi_id" class="block font-bold">{{ __('Estadi') }}:</label>
    <select name="estadi_id" id="estadi_id" class="border p-2 w-full">
      @foreach ($estadis as $estadi)
        <option value="{{ $estadi->id }}" {{ old('estadi_id') == $estadi->id ? 'selected' : '' }}>
          {{ $estadi->nom }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label for="titols" class="block font-bold">{{ __('Titols') }}:</label>
    <input type="number" name="titols" id="titols" value="{{ old('titols') }}" class="border p-2 w-full">
  </div>

  <div class="mb-4">
    <label for="escut" class="block font-bold mb-1">{{ __('Escut') }}:</label>
    <input type="file" name="escut" id="escut" class="w-full border p-2 rounded">
    @error('escut')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
  </div>

  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('Afegir') }}</button>
</form>
@endsection
