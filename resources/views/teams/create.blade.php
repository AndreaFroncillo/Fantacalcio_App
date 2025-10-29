<x-layout>
    <div class="container py-5">
        <h2>Crea la tua squadra per la lega "{{ $league->name }}"</h2>

        <form action="{{ route('teams.store', $league) }}" method="POST" class="mt-4">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome squadra</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Crea squadra</button>
        </form>
    </div>
</x-layout>
