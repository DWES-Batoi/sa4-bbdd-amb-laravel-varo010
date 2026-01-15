@extends('layouts.equip')

@section('title', 'Detall del Jugador')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ $jugador->nom }} {{ $jugador->cognoms }}</h1>
        <span class="bg-blue-800 text-xs px-2 py-1 rounded-full">#{{ $jugador->dorsal }}</span>
    </div>
    <div class="p-6">
        <p class="text-gray-700 dark:text-gray-300 mb-4">
            <strong>Equip:</strong> {{ $jugador->equip->nom ?? 'Sense equip' }}
        </p>
        <div class="flex justify-end mt-4">
            <a href="{{ route('jugadors.index') }}" class="btn btn--ghost mr-2">Tornar</a>
            <a href="{{ route('jugadors.edit', $jugador) }}" class="btn btn--primary">Editar</a>
        </div>
    </div>
</div>
@endsection
