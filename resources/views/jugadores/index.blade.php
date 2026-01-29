@extends('layouts.equip')

@section('content')
<div class="container">
  <div class="flex justify-between items-center mb-4">
    <h1 class="title">{{ __('Llistat de jugadors') }}</h1>
    <a href="{{ route('jugadors.create') }}" class="btn btn--primary">{{ __('Crear Jugador') }}</a>
  </div>
  <div class="grid-cards">
    @foreach ($jugadors as $jugador)
      <article class="card">
        <header class="card__header">
          <h2 class="card__title">{{ $jugador->nom }} {{ $jugador->cognoms }}</h2>
          <span class="card__badge">#{{ $jugador->dorsal }}</span>
        </header>
        <div class="card__body">
          <p><strong>{{ __('Equip') }}:</strong> {{ $jugador->equip->nom ?? 'Sense equip' }}</p>
        </div>

        <footer class="card__footer">
          <a class="btn btn--ghost" href="{{ route('jugadors.show', $jugador) }}">{{ __('Veure') }}</a>
          <a class="btn btn--primary" href="{{ route('jugadors.edit', $jugador) }}">{{ __('Editar') }}</a>

          <form method="POST" action="{{ route('jugadors.destroy', $jugador) }}" class="inline">
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
