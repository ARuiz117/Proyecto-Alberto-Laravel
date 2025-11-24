<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Carrito;
use App\Models\Biblioteca;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Procesar pago con Stripe (versión simplificada para desarrollo local)
     */
    public function checkout(Request $request)
    {
        try {
            $usuario = Auth::user();
            
            // Obtener items del carrito
            $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
                ->with('juego')
                ->get();

            if ($itemsCarrito->isEmpty()) {
                return back()->with('error', 'Tu carrito está vacío.');
            }

            // Calcular total y validar juegos
            $total = 0;
            $juegosAComprar = [];
            
            foreach ($itemsCarrito as $item) {
                if (!$usuario->juegos()->where('juego_id', $item->juego_id)->exists()) {
                    $total += $item->juego->precio * $item->cantidad;
                    $juegosAComprar[] = $item;
                }
            }

            if (empty($juegosAComprar)) {
                return back()->with('error', 'Todos los juegos del carrito ya están en tu biblioteca.');
            }

            // Configurar Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Crear Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($total * 100), // Convertir a centavos
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'usuario_id' => $usuario->id,
                    'total' => $total,
                ],
            ]);

            // Guardar el intent en sesión para validación posterior
            session(['stripe_payment_intent' => $paymentIntent->id]);
            session(['stripe_total' => $total]);

            // Redirigir a página de confirmación de pago
            return view('stripe.payment', [
                'clientSecret' => $paymentIntent->client_secret,
                'publicKey' => config('services.stripe.public'),
                'total' => $total,
                'items' => $juegosAComprar,
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Confirmar pago completado
     */
    public function confirm(Request $request)
    {
        try {
            $usuario = Auth::user();
            $paymentIntentId = $request->get('payment_intent');
            $total = session('stripe_total');

            if (!$paymentIntentId || !$total) {
                return redirect()->route('carrito.index')->with('error', 'Sesión de pago no válida.');
            }

            // Configurar Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Verificar el Payment Intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status !== 'succeeded') {
                return redirect()->route('carrito.index')->with('error', 'El pago no fue completado.');
            }

            // Procesar la compra
            DB::transaction(function () use ($usuario) {
                $itemsCarrito = Carrito::where('usuario_id', $usuario->id)
                    ->with('juego')
                    ->get();

                foreach ($itemsCarrito as $item) {
                    if (!$usuario->juegos()->where('juego_id', $item->juego_id)->exists()) {
                        Biblioteca::create([
                            'usuario_id' => $usuario->id,
                            'juego_id' => $item->juego_id,
                        ]);
                        $item->delete();
                    }
                }
            });

            // Limpiar sesión
            session()->forget(['stripe_payment_intent', 'stripe_total']);

            return redirect()->route('biblioteca.index')->with('success', '¡Compra realizada con éxito! Los juegos se han añadido a tu biblioteca.');

        } catch (\Exception $e) {
            return redirect()->route('carrito.index')->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar pago
     */
    public function cancel()
    {
        session()->forget(['stripe_payment_intent', 'stripe_total']);
        return redirect()->route('carrito.index')->with('info', 'Pago cancelado. Tu carrito se mantiene intacto.');
    }
}
