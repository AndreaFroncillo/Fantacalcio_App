<x-layout>
    <div class="container py-5">
        <h2>Crea una nuova lega</h2>

        <form action="{{ route('leagues.store') }}" method="POST" class="mt-4">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome lega</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="max_players" class="form-label">Numero massimo di partecipanti</label>
                <input type="number" class="form-control" id="max_players" name="max_players" min="2" max="20" value="10">
            </div>

            <div class="mb-3">
                <label for="initial_credits" class="form-label">Crediti iniziali per ciascun giocatore</label>
                <input type="number" class="form-control" id="initial_credits" name="initial_credits" value="500" min="100">
            </div>

            <button type="submit" class="btn btn-success">Crea lega</button>
        </form>
    </div>
</x-layout>
