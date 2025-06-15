<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Tarjima preview</title>
</head>
<body>
<h2>📖 Birinchi 5 sahifa tarjimasi:</h2>
<div style="white-space: pre-wrap; background: #f0f0f0; padding: 1rem; border: 1px solid #ccc;">
    {{ $translatedText }}
</div>

<form method="POST" action="{{ route('stripe.pay') }}" id="stripe-form">
    @csrf
    <button type="submit">✅ Davom ettirish va to‘lash</button>
</form>


<form method="GET" action="{{ route('translations.create') }}">
    <button type="submit" style="margin-top: 0.5rem;">❌ Rad etish</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    document.getElementById('stripe-form').addEventListener('submit', function (e) {
        e.preventDefault();

        fetch("{{ route('stripe.pay') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => stripe.redirectToCheckout({ sessionId: data.id }));
    });
</script>

</body>
</html>
