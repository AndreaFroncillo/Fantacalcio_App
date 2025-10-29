<x-layout>
    <div class="container py-5">
        <h1 class="text-center mb-4">{{ $league->name }}</h1>

        <div class="card mx-auto w-75 shadow">
            <div class="card-body text-center">
                <h4 class="card-title mb-3">Codice invito</h4>

                <div class="d-flex justify-content-center align-items-center">
                    <input type="text" id="inviteCode" class="form-control w-50 text-center"
                        value="{{ $league->invite_code }}" readonly>
                    <button class="btn btn-outline-primary ms-2" onclick="copyInviteCode()">Copia</button>
                </div>

                <p class="text-muted mt-2">Condividi questo codice con i tuoi amici per unirsi alla lega.</p>

                <hr>

                <h5>Partecipanti ({{ $league->users->count() }}/{{ $league->max_players }})</h5>
                <ul class="list-group">
                    @foreach ($league->users as $user)
                    <li class="list-group-item">{{ $user->name }}</li>
                    @endforeach
                </ul>
                @auth
                @php
                $userTeam = $league->teams->firstWhere('user_id', Auth::id());
                @endphp

                @if(!$userTeam)
                <a href="{{ route('teams.create', $league) }}" class="btn btn-primary mt-4">Crea la tua squadra</a>
                @else
                <div class="card mt-4 p-3 bg-light">
                    <h4>La tua squadra: {{ $userTeam->name }}</h4>
                    <p>Crediti rimanenti: {{ $userTeam->credits_remaining }}</p>
                    <!-- qui più avanti puoi anche mostrare i giocatori già acquistati -->
                </div>
                @endif
                @endauth

            </div>
        </div>
    </div>

    <script>
        function copyInviteCode() {
            const code = document.getElementById('inviteCode');
            code.select();
            code.setSelectionRange(0, 99999);
            document.execCommand('copy');
            alert('Codice copiato negli appunti!');
        }
    </script>
</x-layout>