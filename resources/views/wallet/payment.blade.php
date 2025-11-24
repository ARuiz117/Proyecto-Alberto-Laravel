@extends('layouts.app')

@section('title', 'Confirmar Carga de Saldo - Steam HRG')

@section('content')

<div class="main" style="max-width: 1000px; margin: 2rem auto;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
        
        <!-- Resumen -->
        <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
            <h2 style="color: #66c0f4; font-size: 1.6rem; margin: 0 0 1.5rem 0; font-weight: 700;">
                <i class='bx bx-receipt'></i> Resumen
            </h2>
            
            <div style="background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #30363d; color: #8b949e;">
                    <span>Cantidad a cargar:</span>
                    <span style="color: #1db954; font-weight: bold; font-size: 1.2rem;">{{ number_format($amount, 2) }} €</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-bottom: 1px solid #30363d; color: #8b949e;">
                    <span>Saldo actual:</span>
                    <span>{{ number_format($saldoActual, 2) }} €</span>
                </div>

                <div style="display: flex; justify-content: space-between; padding: 1.5rem 0 0 0; border-top: 2px solid #30363d; margin-top: 1rem; font-size: 1.4rem; font-weight: bold; color: #1db954;">
                    <span>Nuevo saldo:</span>
                    <span>{{ number_format($saldoActual + $amount, 2) }} €</span>
                </div>
            </div>

            <div style="background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 1.5rem; color: #8b949e; font-size: 0.95rem; line-height: 1.6;">
                <h4 style="color: #c9d1d9; margin: 0 0 1rem 0;">Información de Pago</h4>
                <p style="margin: 0.5rem 0;">✓ Pago seguro con Stripe</p>
                <p style="margin: 0.5rem 0;">✓ Tus datos están encriptados</p>
                <p style="margin: 0.5rem 0;">✓ El saldo se agregará inmediatamente</p>
                <p style="margin: 0.5rem 0;">✓ Sin comisiones adicionales</p>
            </div>
        </div>

        <!-- Formulario de Pago -->
        <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
            <h2 style="color: #66c0f4; font-size: 1.6rem; margin: 0 0 1.5rem 0; font-weight: 700;">
                <i class='bx bx-credit-card'></i> Datos de Pago
            </h2>

            <form id="payment-form">
                @csrf
                
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">Número de Tarjeta</label>
                    <div id="card-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">Vencimiento</label>
                        <div id="expiry-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
                    </div>
                    <div>
                        <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1.1rem;">CVC</label>
                        <div id="cvc-element" style="background: white; padding: 1.2rem; border-radius: 6px; border: 2px solid #30363d; font-size: 1.1rem; min-height: 50px;"></div>
                    </div>
                </div>

                <div id="card-errors" style="color: #f85149; margin-bottom: 1.5rem; font-size: 1rem; min-height: 25px; font-weight: 600;"></div>

                <button type="submit" id="submit-btn" style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1.2rem; border-radius: 6px; font-weight: 600; font-size: 1.1rem; cursor: pointer; transition: transform 0.2s;">
                    <i class='bx bx-check-circle'></i> Cargar {{ number_format($amount, 2) }} €
                </button>
            </form>

            <a href="{{ route('wallet.show') }}" style="display: block; text-align: center; margin-top: 1rem; color: #8b949e; text-decoration: none; padding: 1rem; border: 1px solid #30363d; border-radius: 6px; transition: all 0.2s;">
                <i class='bx bx-arrow-back'></i> Volver
            </a>

            <div style="background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 1rem; margin-top: 1.5rem; color: #8b949e; font-size: 0.85rem;">
                <p style="margin: 0; font-weight: 600; color: #c9d1d9; margin-bottom: 0.5rem;">🧪 Tarjeta de Prueba:</p>
                <p style="margin: 0;">Número: 4242 4242 4242 4242</p>
                <p style="margin: 0;">Vencimiento: 12/25 (cualquiera futura)</p>
                <p style="margin: 0;">CVC: 123</p>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<script>
const stripe = Stripe('{{ $publicKey }}');
const elements = stripe.elements({
    locale: 'es'
});

// Crear elementos separados - SIN Link
const cardElement = elements.create('cardNumber', {
    disabled: false
});
const expiryElement = elements.create('cardExpiry');
const cvcElement = elements.create('cardCvc');

cardElement.mount('#card-element');
expiryElement.mount('#expiry-element');
cvcElement.mount('#cvc-element');

const cardErrors = document.getElementById('card-errors');
const form = document.getElementById('payment-form');
const submitBtn = document.getElementById('submit-btn');

// Manejar errores
[cardElement, expiryElement, cvcElement].forEach(element => {
    element.on('change', (event) => {
        if (event.error) {
            cardErrors.textContent = event.error.message;
        } else {
            cardErrors.textContent = '';
        }
    });
});

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    submitBtn.disabled = true;
    submitBtn.textContent = 'Procesando pago...';

    try {
        // Crear payment method directamente
        const { paymentMethod, error: pmError } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: {
                name: '{{ Auth::user()->nombre ?? 'Customer' }}'
            }
        });

        if (pmError) {
            cardErrors.textContent = pmError.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cargar {{ number_format($amount, 2) }} €';
            return;
        }

        // Confirmar el pago con el payment method
        const { paymentIntent, error } = await stripe.confirmCardPayment('{{ $clientSecret }}', {
            payment_method: paymentMethod.id
        });

        if (error) {
            cardErrors.textContent = error.message;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cargar {{ number_format($amount, 2) }} €';
        } else if (paymentIntent && paymentIntent.status === 'succeeded') {
            // Pago exitoso, confirmar en el servidor
            const confirmForm = document.createElement('form');
            confirmForm.method = 'POST';
            confirmForm.action = '{{ route("wallet.confirm") }}';
            
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = '{{ csrf_token() }}';
            
            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment_intent';
            paymentInput.value = paymentIntent.id;
            
            confirmForm.appendChild(tokenInput);
            confirmForm.appendChild(paymentInput);
            document.body.appendChild(confirmForm);
            confirmForm.submit();
        }
    } catch (err) {
        cardErrors.textContent = 'Error al procesar el pago: ' + err.message;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Cargar {{ number_format($amount, 2) }} €';
    }
});
</script>

@endsection
