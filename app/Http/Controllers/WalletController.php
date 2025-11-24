<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class WalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar página para cargar saldo
     */
    public function show()
    {
        $usuario = Auth::user();
        return view('wallet.index', [
            'saldoActual' => $usuario->saldo,
        ]);
    }

    /**
     * Crear Payment Intent para cargar saldo
     */
    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:5|max:1000',
        ], [
            'amount.required' => 'El monto es obligatorio',
            'amount.numeric' => 'El monto debe ser un número',
            'amount.min' => 'El monto mínimo es 5€',
            'amount.max' => 'El monto máximo es 1000€',
        ]);

        try {
            $usuario = Auth::user();
            $amount = (float) $request->amount;

            // Configurar Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Crear Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // Convertir a centavos
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'usuario_id' => $usuario->id,
                    'type' => 'wallet_topup',
                    'amount' => $amount,
                ],
            ]);

            // Guardar el intent en sesión
            session(['stripe_payment_intent' => $paymentIntent->id]);
            session(['stripe_amount' => $amount]);
            session(['stripe_type' => 'wallet']);

            // Redirigir a página de confirmación de pago
            return view('wallet.payment', [
                'clientSecret' => $paymentIntent->client_secret,
                'publicKey' => config('services.stripe.public'),
                'amount' => $amount,
                'saldoActual' => $usuario->saldo,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar carga de saldo
     */
    public function confirm(Request $request)
    {
        try {
            $usuario = Auth::user();
            $paymentIntentId = $request->get('payment_intent');
            $amount = session('stripe_amount');

            if (!$paymentIntentId || !$amount) {
                return redirect()->route('wallet.show')->with('error', 'Sesión de pago no válida.');
            }

            // Configurar Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Verificar el Payment Intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                return redirect()->route('wallet.show')->with('error', 'El pago no fue completado.');
            }

            // Agregar saldo a la cuenta
            DB::transaction(function () use ($usuario, $amount) {
                $usuario->saldo += $amount;
                $usuario->save();
            });

            // Limpiar sesión
            session()->forget(['stripe_payment_intent', 'stripe_amount', 'stripe_type']);

            return redirect()->route('wallet.show')->with('success', '¡Saldo cargado exitosamente! Se han agregado ' . number_format($amount, 2) . ' € a tu cuenta.');

        } catch (\Exception $e) {
            return redirect()->route('wallet.show')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar carga de saldo
     */
    public function cancel()
    {
        session()->forget(['stripe_payment_intent', 'stripe_amount', 'stripe_type']);
        return redirect()->route('wallet.show')->with('info', 'Carga de saldo cancelada.');
    }
}
