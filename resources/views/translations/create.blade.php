{{--@extends('layouts.app')--}}
{{--@section('content')--}}
{{--<h2>PDF faylni tarjima qilish</h2>--}}

{{--@if(session('error'))--}}
{{--    <p style="color:red;">{{ session('error') }}</p>--}}
{{--@endif--}}

{{--<form action="{{ route('translations.translate') }}" method="POST" enctype="multipart/form-data">--}}
{{--    @csrf--}}
{{--    <label for="file">PDF fayl yuklang:</label>--}}
{{--    <input type="file" name="file" accept=".pdf" required><br><br>--}}

{{--    <label for="lang">Tarjima tili:</label>--}}
{{--    <select name="lang" required>--}}
{{--        <option value="uz">O'zbek</option>--}}
{{--        <option value="en">Ingliz</option>--}}
{{--    </select><br><br>--}}

{{--    <button type="submit">Tarjima qilish</button>--}}
{{--</form>--}}
{{--@endsection--}}

@extends('layouts.app')
@section('content')
<h2>PDF tarjima qilish</h2>

<form action="{{ route('translations.preview') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">PDF fayl yuklang:</label>
    <input type="file" name="file" accept=".pdf" required><br><br>

    <label for="lang">Tarjima tili:</label>
    <select name="lang" required>
        <option value="uz">O‘zbek</option>
        <option value="en">Ingliz</option>
    </select><br><br>

    <button type="submit">Tarjima qilish</button>
</form>


<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    document.getElementById('payment-form').addEventListener('submit', function (e) {
        e.preventDefault();

        let form = e.target;
        let data = new FormData(form);

        fetch("{{ route('stripe.pay') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: data
        }).then(res => res.json())
            .then(data => stripe.redirectToCheckout({ sessionId: data.id }));
    });
</script>
@endsection

