@extends('layouts.equip')

@section('content')
<div class="container">
  <div class="flex justify-between items-center mb-4">
    <h1 class="title">{{ __('Llistat de partits') }}</h1>
    <a href="{{ route('partits.create') }}" class="btn btn--primary">{{ __('Crear Partit') }}</a>
  </div>
  <div class="grid-cards">
    @foreach ($partits as $partit)
      <article class="card">
        <header class="card__header">
          <h2 class="card__title">{{ $partit->local->nom }} vs {{ $partit->visitant->nom }}</h2>
        </header>
        <div class="card__body">
          <p><strong>{{ __('Resultat') }}:</strong> {{ $partit->gols_local ?? '-' }} - {{ $partit->gols_visitant ?? '-' }}</p>
          <p><strong>{{ __('Data') }}:</strong> {{ $partit->data_partit->format('d/m/Y H:i') }}</p>
        </div>

        <footer class="card__footer">
          <a class="btn btn--ghost" href="{{ route('partits.show', $partit) }}">{{ __('Veure') }}</a>
          <a class="btn btn--primary" href="{{ route('partits.edit', $partit) }}">{{ __('Editar') }}</a>

          <form method="POST" action="{{ route('partits.destroy', $partit) }}" class="inline">
            @csrf
            @method('DELETE')
            <button class="btn btn--danger" type="submit">{{ __('Eliminar') }}</button>
          </form>
        </footer>
      </article>
    @endforeach
  </div>
</div>
@endsection
