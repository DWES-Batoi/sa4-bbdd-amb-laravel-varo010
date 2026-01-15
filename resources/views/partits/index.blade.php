@extends('layouts.equip')

@section('content')
<div class="container">
  <div class="flex justify-between items-center mb-4">
    <h1 class="title">Llistat de Partits</h1>
    <a href="{{ route('partits.create') }}" class="btn btn--primary">Nou Partit</a>
  </div>
  <div class="grid-cards">
    @foreach ($partits as $partit)
      <article class="card">
        <header class="card__header">
          <h2 class="card__title">{{ $partit->local->nom }} vs {{ $partit->visitant->nom }}</h2>
        </header>
        <div class="card__body">
          <p><strong>Resultat:</strong> {{ $partit->gols_local ?? '-' }} - {{ $partit->gols_visitant ?? '-' }}</p>
          <p><strong>Data:</strong> {{ $partit->data_partit->format('d/m/Y H:i') }}</p>
        </div>

        <footer class="card__footer">
          <a class="btn btn--ghost" href="{{ route('partits.show', $partit) }}">Ver</a>
          <a class="btn btn--primary" href="{{ route('partits.edit', $partit) }}">Editar</a>

          <form method="POST" action="{{ route('partits.destroy', $partit) }}" class="inline">
            @csrf
            @method('DELETE')
            <button class="btn btn--danger" type="submit">Eliminar</button>
          </form>
        </footer>
      </article>
    @endforeach
  </div>
</div>
@endsection
