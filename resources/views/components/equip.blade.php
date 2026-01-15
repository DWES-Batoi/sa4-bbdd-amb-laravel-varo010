<div class="equip border rounded-lg shadow-md p-4 bg-white">
    @if ($equip->escut)
        <img src="{{ asset('storage/' . $equip->escut) }}"
             alt="Escut de {{ $equip->nom }}"
             class="h-12 w-12 object-cover rounded-full mb-2">
    @endif

    <h2 class="text-xl font-bold text-blue-800">{{ $equip->nom }}</h2>
    <p><strong>Estadi:</strong> {{ $equip->estadi->nom }}</p>
    <p><strong>Títols:</strong> {{ $equip->titols }}</p>
</div>
