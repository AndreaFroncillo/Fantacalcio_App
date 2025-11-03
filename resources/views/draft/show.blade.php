<x-layout>
    <div class="container py-5">
        <h1 class="text-center mb-4">{{ $league->name }} - Asta Live</h1>

        <div class="text-center mb-4">
            <form action="{{ route('draft.start', $league) }}" method="POST">
                @csrf
                <button class="btn btn-success">Avvia Asta</button>
            </form>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div id="draft-area">
            <livewire:draft-live :league="$league" />

        </div>
    </div>
</x-layout>