// Control de sesión y cookies
class SessionManager {
    constructor() {
        this.timeoutDuration = 2.5 * 60 * 1000; // 2:30 minutos (150 segundos)
        this.warningTime = 30 * 1000; // Advertencia 30 segundos antes
        this.timeoutId = null;
        this.warningId = null;
        this.cookiesAccepted = this.getCookie('cookies_accepted') === 'true';
        
        this.init();
    }
    
    init() {
        // Mostrar banner de cookies si no se han aceptado
        if (!this.cookiesAccepted) {
            this.showCookieBanner();
        }
        
        // Iniciar timeout si hay sesión activa
        if (this.hasActiveSession()) {
            this.startSessionTimeout();
            this.bindActivityEvents();
        }
    }
    
    hasActiveSession() {
        // Verificar si hay sesión activa
        return document.body.classList.contains('logged-in');
    }
    
    showCookieBanner() {
        const banner = document.createElement('div');
        banner.id = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-content">
                <div class="cookie-text">
                    <h3>🍪 Uso de Cookies</h3>
                    <p>Utilizamos cookies para mejorar tu experiencia y gestionar tu sesión de forma segura. 
                    Al aceptar, tu sesión se cerrará automáticamente después de 2:30 minutos de inactividad por seguridad.</p>
                </div>
                <div class="cookie-buttons">
                    <button onclick="sessionManager.acceptCookies()" class="btn btn-primary">Aceptar Cookies</button>
                    <button onclick="sessionManager.rejectCookies()" class="btn btn-secondary">Rechazar</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(banner);
        
        // Estilos del banner
        const style = document.createElement('style');
        style.textContent = `
            #cookie-banner {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to right, #1b2838, #2a475e);
                color: white;
                padding: 1rem;
                box-shadow: 0 -4px 20px rgba(0,0,0,0.5);
                z-index: 1000;
                border-top: 2px solid #66c0f4;
                animation: slideUp 0.5s ease-out;
            }
            
            .cookie-content {
                max-width: 1200px;
                margin: 0 auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 2rem;
                flex-wrap: wrap;
            }
            
            .cookie-text h3 {
                margin: 0 0 0.5rem 0;
                color: #66c0f4;
                font-size: 1.2rem;
            }
            
            .cookie-text p {
                margin: 0;
                font-size: 0.9rem;
                line-height: 1.4;
                color: #c7d5e0;
            }
            
            .cookie-buttons {
                display: flex;
                gap: 1rem;
                flex-shrink: 0;
            }
            
            .cookie-buttons .btn {
                margin: 0;
                padding: 0.7rem 1.5rem;
                font-size: 0.9rem;
                white-space: nowrap;
            }
            
            @keyframes slideUp {
                from { transform: translateY(100%); }
                to { transform: translateY(0); }
            }
            
            @media (max-width: 768px) {
                .cookie-content {
                    flex-direction: column;
                    text-align: center;
                    gap: 1rem;
                }
                
                .cookie-buttons {
                    width: 100%;
                    justify-content: center;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    acceptCookies() {
        this.setCookie('cookies_accepted', 'true', 365);
        this.cookiesAccepted = true;
        this.removeCookieBanner();
        
        // Iniciar timeout si hay sesión activa
        if (this.hasActiveSession()) {
            this.startSessionTimeout();
            this.bindActivityEvents();
        }
        
        // Mostrar mensaje de confirmación
        this.showNotification('Cookies aceptadas. Tu sesión se cerrará automáticamente después de 2:30 minutos de inactividad.', 'success');
    }
    
    rejectCookies() {
        this.removeCookieBanner();
        this.showNotification('Cookies rechazadas. Algunas funcionalidades pueden estar limitadas.', 'warning');
        
        // Si hay sesión activa, cerrarla inmediatamente
        if (this.hasActiveSession()) {
            this.performLogout();
        }
    }
    
    removeCookieBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.style.animation = 'slideDown 0.3s ease-in';
            setTimeout(() => banner.remove(), 300);
        }
    }
    
    startSessionTimeout() {
        this.clearTimeouts();
        
        // Advertencia a los 2 minutos (2:30 - 0:30 = 2:00)
        this.warningId = setTimeout(() => {
            this.showSessionWarning();
        }, this.timeoutDuration - this.warningTime);
        
        // Cierre automático a los 2:30 minutos
        this.timeoutId = setTimeout(() => {
            this.logoutUser();
        }, this.timeoutDuration);
    }
    
    resetSessionTimeout() {
        if (this.hasActiveSession()) {
            this.startSessionTimeout();
        }
    }
    
    clearTimeouts() {
        if (this.timeoutId) {
            clearTimeout(this.timeoutId);
            this.timeoutId = null;
        }
        if (this.warningId) {
            clearTimeout(this.warningId);
            this.warningId = null;
        }
    }
    
    bindActivityEvents() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.resetSessionTimeout();
            }, true);
        });
    }
    
    showSessionWarning() {
        const warning = document.createElement('div');
        warning.id = 'session-warning';
        warning.innerHTML = `
            <div class="warning-content">
                <h3>⚠️ Sesión por expirar</h3>
                <p>Tu sesión se cerrará en <span id="countdown-timer" style="font-weight: bold; color: #ff6b6b; font-size: 1.5rem;">30</span> segundos por inactividad.</p>
                <button onclick="sessionManager.extendSession()" class="btn btn-primary">Mantener sesión activa</button>
            </div>
        `;
        
        warning.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(to bottom, #32383e, #1b2838);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 30px rgba(0,0,0,0.7);
            z-index: 1001;
            text-align: center;
            border: 2px solid #e63946;
            animation: pulse 1s infinite;
        `;
        
        document.body.appendChild(warning);
        
        // Iniciar contador regresivo
        this.startCountdown();
    }
    
    startCountdown() {
        let secondsLeft = 30;
        const timerElement = document.getElementById('countdown-timer');
        const self = this; // Guardar referencia a 'this'
        
        const countdownInterval = setInterval(() => {
            secondsLeft--;
            if (timerElement) {
                timerElement.textContent = secondsLeft;
                
                // Cambiar color según el tiempo restante
                if (secondsLeft <= 10) {
                    timerElement.style.color = '#ff3333';
                } else if (secondsLeft <= 20) {
                    timerElement.style.color = '#ff8800';
                }
            }
            
            if (secondsLeft <= 0) {
                clearInterval(countdownInterval);
                // Cerrar sesión cuando el contador llega a 0
                self.performLogout();
            }
        }, 1000);
    }
    
    extendSession() {
        const warning = document.getElementById('session-warning');
        if (warning) warning.remove();
        
        this.resetSessionTimeout();
        this.showNotification('Sesión extendida por 1 minuto más.', 'success');
    }
    
    logoutUser() {
        this.showNotification('Sesión cerrada por inactividad.', 'info');
        setTimeout(() => {
            this.performLogout();
        }, 2000);
    }
    
    performLogout() {
        // Usar el formulario oculto que ya está en el HTML
        const logoutForm = document.getElementById('auto-logout-form');
        
        if (logoutForm) {
            logoutForm.submit();
        } else {
            // Fallback: redirigir a login usando la ruta base
            window.location.href = window.location.origin + '/ProyectoAlberto-Steam-Laravel/public/login';
        }
    }
    
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1002;
            animation: fadeInDown 0.5s ease-out;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        
        // Colores según el tipo
        const colors = {
            success: '#06d6a0',
            warning: '#ffd60a',
            error: '#e63946',
            info: '#66c0f4'
        };
        
        notification.style.background = colors[type] || colors.info;
        if (type === 'warning') notification.style.color = '#1b2838';
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'fadeOutUp 0.4s ease-in';
            setTimeout(() => notification.remove(), 400);
        }, 3000);
    }
    
    // Utilidades para cookies
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }
    
    getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}

// Inicializar el gestor de sesiones cuando se carga la página
let sessionManager;
document.addEventListener('DOMContentLoaded', function() {
    sessionManager = new SessionManager();
});

// Agregar estilos adicionales para las animaciones
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    @keyframes slideDown {
        from { transform: translateY(0); }
        to { transform: translateY(100%); }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes fadeInDown {
        from { 
            opacity: 0;
            transform: translateY(-20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOutUp {
        from { 
            opacity: 1;
            transform: translateY(0);
        }
        to { 
            opacity: 0;
            transform: translateY(-20px);
        }
    }
    
    @keyframes pulse {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.05); }
    }
`;
document.head.appendChild(additionalStyles);
