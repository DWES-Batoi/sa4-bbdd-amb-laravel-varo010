@extends('layouts.equip')

@section('content')
<div class="container">
  <div class="flex justify-between items-center mb-4">
    <h1 class="title">Listado de equipos</h1>
    <a href="{{ route('equips.create') }}" class="btn btn--primary">Nou Equip</a>
  </div>

  <div class="grid-cards">
    @foreach ($equips as $equip)
      <article class="card">
        <header class="card__header">
          <h2 class="card__title">{{ $equip->nom }}</h2>
          <span class="card__badge">ID: {{ $equip->id }}</span>
        </header>

        <div class="card__body">
          <p><strong>Ciudad:</strong> {{ $equip->ciutat ?? '—' }}</p>
          <p><strong>Estadio:</strong> {{ $equip->estadi->nom ?? '—' }}</p>
        </div>

        <footer class="card__footer">
          <a class="btn btn--ghost" href="{{ route('equips.show', $equip) }}">Ver</a>
          <a class="btn btn--primary" href="{{ route('equips.edit', $equip) }}">Editar</a>

          <form method="POST" action="{{ route('equips.destroy', $equip) }}" class="inline">
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
