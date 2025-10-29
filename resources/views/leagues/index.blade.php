<x-layout>
    <div class="container py-5">
        <h1 class="text-center mb-4">Le mie leghe</h1>

        <div class="text-center mb-4">
            <a href="{{ route('leagues.create') }}" class="btn btn-success">
                + Crea una nuova lega
            </a>
        </div>

        @if ($leagues->isEmpty())
            <div class="alert alert-info text-center">
                Non hai ancora creato o partecipato a nessuna lega.
            </div>
        @else
            <div class="row justify-content-center">
                @foreach ($leagues as $league)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h4 class="card-title">{{ $league->name }}</h4>
                                <p class="card-text">
                                    Giocatori: {{ $league->users->count() }}/{{ $league->max_players }}
                                </p>
                                <p class="text-muted small">Codice invito: {{ $league->invite_code }}</p>
                                <a href="{{ route('leagues.show', $league->id) }}" class="btn btn-outline-primary">
                                    Vai alla lega
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layout>
