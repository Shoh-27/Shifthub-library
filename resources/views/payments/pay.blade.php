<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<h2>To‘lov sahifasi</h2>
<button id="checkout-button">To‘lovni amalga oshirish</button>

<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');

    document.getElementById('checkout-button').addEventListener('click', function () {
        fetch('{{ route('stripe.create.session') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                amount: 1000 // bu yerda $10 yoki sahifaga qarab dinamik hisoblab yozasiz
            })
        })
            .then(response => response.json())
            .then(session => {
                return stripe.redirectToCheckout({ sessionId: session.id });
            });
    });
</script>
</body>
</html>
