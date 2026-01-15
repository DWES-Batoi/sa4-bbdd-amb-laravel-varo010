@extends('layouts.equip')

@section('title', 'Detall del Partit')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
    <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ $partit->local->nom }} vs {{ $partit->visitant->nom }}</h1>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="font-bold text-gray-700 dark:text-gray-300">Local</h3>
                <p class="text-xl">{{ $partit->local->nom }}</p>
                <p class="text-3xl font-bold text-blue-600">{{ $partit->gols_local ?? '-' }}</p>
            </div>
            <div class="text-right">
                <h3 class="font-bold text-gray-700 dark:text-gray-300">Visitant</h3>
                <p class="text-xl">{{ $partit->visitant->nom }}</p>
                <p class="text-3xl font-bold text-blue-600">{{ $partit->gols_visitant ?? '-' }}</p>
            </div>
        </div>
        
        <p class="text-gray-700 dark:text-gray-300 mb-4 text-center">
            <strong>Data:</strong> {{ $partit->data_partit->format('d/m/Y H:i') }}
        </p>

        <div class="flex justify-end mt-4">
            <a href="{{ route('partits.index') }}" class="btn btn--ghost mr-2">Tornar</a>
            <a href="{{ route('partits.edit', $partit) }}" class="btn btn--primary">Editar</a>
        </div>
    </div>
</div>
@endsection
