<x-layout>
    <div class="container py-5">
        <h1 class="text-center mb-4">Unisciti a una Lega</h1>

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('leagues.join') }}" method="POST" class="w-50 mx-auto">
            @csrf

            <div class="mb-3">
                <label for="invite_code" class="form-label">Codice invito</label>
                <input type="text" name="invite_code" id="invite_code" class="form-control" value="{{ $league->invite_code ?? '' }}" @readonly(isset($league)) required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Unisciti</button>
        </form>
    </div>
</x-layout>