@props([
    'nom',
    'capacitat',
    'equips' => collect(),  // per si no es passa res
])

<div class="estadi border rounded-lg shadow-md p-4 bg-white">
  <h2 class="text-xl font-bold text-blue-800">{{ $nom }}</h2>

  <p><strong>Capacitat:</strong> {{ $capacitat }}</p>

  <p>
    <strong>Equips:</strong>
    {{ $equips->count() }}
  </p>
</div>
