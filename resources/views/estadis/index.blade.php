@extends('layouts.equip')
@section('title', __('Llistat d\'estadis'))

@section('content')
<h1 class="text-3xl font-bold text-blue-800 mb-6">{{ __('Llistat d\'estadis') }}</h1>

@if (session('success'))
  <div class="bg-green-100 text-green-700 p-2 mb-4">{{ session('success') }}</div>
@endif

<p class="mb-4">
  <a href="{{ route('estadis.create') }}" class="btn btn--primary">
    {{ __('Crear Estadi') }}
  </a>
</p>

<table class="w-full border-collapse border border-gray-300 dark:border-gray-700">
  <thead class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
    <tr>
      <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Nom') }}</th>
      <th class="border border-gray-300 dark:border-gray-600 p-2">{{ __('Capacitat') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($estadis as $estadi)
    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200">
      <td class="border border-gray-300 dark:border-gray-600 p-2">
        <a href="{{ route('estadis.show', $estadi->id) }}" class="text-blue-700 dark:text-blue-400 hover:underline">
          {{ $estadi->nom }}
        </a>
      </td>
      <td class="border border-gray-300 dark:border-gray-600 p-2">{{ $estadi->capacitat }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
