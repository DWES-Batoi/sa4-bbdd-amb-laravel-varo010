@extends('layouts.equip')

@section('content')
<div class="container">
  <div class="flex justify-between items-center mb-4">
    <h1 class="title">{{ __('Llistat d\'equips') }}</h1>
    <a href="{{ route('equips.create') }}" class="btn btn--primary">{{ __('Crear Equip') }}</a>
  </div>

  <div class="grid-cards">
    @foreach ($equips as $equip)
      <article class="card">
        <header class="card__header">
          <h2 class="card__title">{{ $equip->nom }}</h2>
          <span class="card__badge">ID: {{ $equip->id }}</span>
        </header>

        <div class="card__body">
          <p><strong>{{ __('Ciutat') }}:</strong> {{ $equip->ciutat ?? '—' }}</p>
          <p><strong>{{ __('Estadi') }}:</strong> {{ $equip->estadi->nom ?? '—' }}</p>
        </div>

        <footer class="card__footer">
          <a class="btn btn--ghost" href="{{ route('equips.show', $equip) }}">{{ __('Veure') }}</a>
          <a class="btn btn--primary" href="{{ route('equips.edit', $equip) }}">{{ __('Editar') }}</a>

          <form method="POST" action="{{ route('equips.destroy', $equip) }}" class="inline">
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
