@extends('layouts.app')

@section('title', 'Cargar Saldo - Steam HRG')

@section('content')

<div class="main" style="max-width: 900px; margin: 2rem auto;">
    <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); margin-bottom: 2rem;">
        <h1 style="color: #66c0f4; font-size: 2rem; margin: 0 0 1rem 0; font-weight: 700;">
            <i class='bx bx-wallet'></i> Mi Billetera
        </h1>
        <p style="color: #8F98A0; margin: 0; font-size: 0.95rem;">Carga dinero a tu cuenta para comprar juegos</p>
    </div>

    @if(session('error'))
        <div style="background: #3d1f1a; border: 1px solid #da3633; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; color: #f85149;">
            <i class='bx bx-error-circle'></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div style="background: #0d3817; border: 1px solid #238636; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; color: #3fb950;">
            <i class='bx bx-check-circle'></i> {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div style="background: #0d2d4a; border: 1px solid #1f6feb; border-radius: 8px; padding: 1rem; margin-bottom: 2rem; color: #58a6ff;">
            <i class='bx bx-info-circle'></i> {{ session('info') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- Saldo Actual -->
        <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
            <h2 style="color: #66c0f4; font-size: 1.4rem; margin: 0 0 1.5rem 0; font-weight: 700;">
                <i class='bx bx-money'></i> Saldo Actual
            </h2>
            
            <div style="background: linear-gradient(135deg, #1db954 0%, #238636 100%); border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 1.5rem;">
                <p style="color: rgba(255,255,255,0.8); margin: 0 0 0.5rem 0; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Saldo Disponible</p>
                <p style="color: white; margin: 0; font-size: 2.5rem; font-weight: bold;">{{ number_format($saldoActual, 2) }} €</p>
            </div>

            <div style="background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 1.5rem; color: #8b949e; font-size: 0.95rem; line-height: 1.6;">
                <h4 style="color: #c9d1d9; margin: 0 0 1rem 0;">Información</h4>
                <p style="margin: 0.5rem 0;">✓ Usa tu saldo para comprar juegos</p>
                <p style="margin: 0.5rem 0;">✓ Carga dinero de forma segura con Stripe</p>
                <p style="margin: 0.5rem 0;">✓ Sin comisiones adicionales</p>
                <p style="margin: 0.5rem 0;">✓ Mínimo: 5€ | Máximo: 1000€</p>
            </div>
        </div>

        <!-- Cargar Saldo -->
        <div style="background: #171a21; border: 1px solid #363c44; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
            <h2 style="color: #66c0f4; font-size: 1.4rem; margin: 0 0 1.5rem 0; font-weight: 700;">
                <i class='bx bx-plus-circle'></i> Cargar Saldo
            </h2>

            <form method="POST" action="{{ route('wallet.topup') }}" style="margin-bottom: 1.5rem;">
                @csrf
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: #c9d1d9; margin-bottom: 0.8rem; font-weight: 600; font-size: 1rem;">Cantidad (€)</label>
                    <input type="number" name="amount" step="0.01" min="5" max="1000" placeholder="Ej: 50.00" required style="width: 100%; padding: 0.8rem; border-radius: 6px; border: 1px solid #30363d; background: #0d1117; color: #c9d1d9; font-size: 1rem;">
                    @error('amount')
                        <p style="color: #f85149; margin: 0.5rem 0 0 0; font-size: 0.9rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="background: #0d1117; border: 1px solid #30363d; border-radius: 6px; padding: 1rem; margin-bottom: 1.5rem; color: #8b949e; font-size: 0.85rem;">
                    <p style="margin: 0 0 0.5rem 0; font-weight: 600; color: #c9d1d9;">Opciones rápidas:</p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <button type="button" onclick="document.querySelector('input[name=amount]').value='10'; document.querySelector('input[name=amount]').focus();" style="padding: 0.5rem; background: #238636; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">10€</button>
                        <button type="button" onclick="document.querySelector('input[name=amount]').value='25'; document.querySelector('input[name=amount]').focus();" style="padding: 0.5rem; background: #238636; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">25€</button>
                        <button type="button" onclick="document.querySelector('input[name=amount]').value='50'; document.querySelector('input[name=amount]').focus();" style="padding: 0.5rem; background: #238636; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">50€</button>
                        <button type="button" onclick="document.querySelector('input[name=amount]').value='100'; document.querySelector('input[name=amount]').focus();" style="padding: 0.5rem; background: #238636; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">100€</button>
                    </div>
                </div>

                <button type="submit" style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1rem; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: transform 0.2s;">
                    <i class='bx bx-credit-card'></i> Cargar con Stripe
                </button>
            </form>

            <a href="{{ route('biblioteca.index') }}" style="display: block; text-align: center; color: #8b949e; text-decoration: none; padding: 0.8rem; border: 1px solid #30363d; border-radius: 6px; transition: all 0.2s;">
                <i class='bx bx-arrow-back'></i> Volver a la Biblioteca
            </a>
        </div>
    </div>
</div>

@endsection
