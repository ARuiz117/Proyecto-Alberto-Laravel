// Cursor personalizado con efectos

document.addEventListener('DOMContentLoaded', function() {
    // No mostrar cursor personalizado en dispositivos táctiles
    if ('ontouchstart' in window) {
        return;
    }
    
    // Crear elementos del cursor
    const cursor = document.createElement('div');
    cursor.className = 'cursor-custom';
    
    // SVG exacto como el de animate-ui.com - flecha personalizada
    cursor.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">
            <path
                fill="currentColor"
                d="M1.8 4.4 7 36.2c.3 1.8 2.6 2.3 3.6.8l3.9-5.7c1.7-2.5 4.5-4.1 7.5-4.3l6.9-.5c1.8-.1 2.5-2.4 1.1-3.5L5 2.5c-1.4-1.1-3.5 0-3.3 1.9Z"
            />
        </svg>
    `;
    
    document.body.appendChild(cursor);
    
    const cursorFollow = document.createElement('div');
    cursorFollow.className = 'cursor-follow';
    document.body.appendChild(cursorFollow);
    
    // Estado del cursor
    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;
    let isHovering = false;
    let followText = '';
    let isMouseDown = false;
    
    // Actualizar posición del cursor con suavizado
    function updateCursorPosition() {
        // Suavizado del movimiento
        cursorX += (mouseX - cursorX) * 0.15;
        cursorY += (mouseY - cursorY) * 0.15;
        
        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';
        
        // Actualizar posición del follow
        cursorFollow.style.left = mouseX + 'px';
        cursorFollow.style.top = mouseY + 'px';
        
        requestAnimationFrame(updateCursorPosition);
    }
    
    // Evento de movimiento del mouse
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
        
        // Asegurar que el cursor siempre sea visible
        if (cursor.style.display === 'none') {
            cursor.style.display = 'block';
        }
        
        // Forzar que no aparezca el cursor del sistema
        document.body.style.cursor = 'none';
        e.target.style.cursor = 'none';
        
        // Resetear estado de clic si el mouse se mueve
        if (isMouseDown) {
            isMouseDown = false;
            cursor.classList.remove('click');
        }
    });
    
    // Evento de entrada a elementos interactivos
    document.addEventListener('mouseover', function(e) {
        const target = e.target;
        
        // Resetear clases
        cursor.className = 'cursor-custom';
        followText = '';
        
        // Detectar tipo de elemento
        if (target.tagName === 'A' || target.closest('a')) {
            cursor.classList.add('link-hover');
            followText = 'Enlace';
        } else if (target.tagName === 'BUTTON' || target.closest('button')) {
            cursor.classList.add('button-hover');
            const btnText = target.textContent.trim() || target.closest('button').textContent.trim();
            followText = btnText || 'Botón';
        } else if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.closest('input') || target.closest('textarea')) {
            cursor.classList.add('input-hover');
            followText = 'Escribir';
        } else if (target.classList.contains('btn') || target.closest('.btn')) {
            cursor.classList.add('button-hover');
            const btnText = target.textContent.trim() || target.closest('.btn').textContent.trim();
            followText = btnText || 'Botón';
        } else if (target.classList.contains('juego-card') || target.closest('.juego-card')) {
            cursor.classList.add('juego-hover');
            followText = 'Juego';
        } else if (target.classList.contains('precio') || target.closest('.precio')) {
            cursor.classList.add('link-hover');
            followText = 'Precio';
        } else if (target.classList.contains('img-container') || target.closest('.img-container')) {
            cursor.classList.add('hover');
            followText = 'Imagen';
        }
        
        // Actualizar texto del follow
        if (followText) {
            cursorFollow.textContent = followText;
            cursorFollow.classList.add('visible');
        } else {
            cursorFollow.classList.remove('visible');
        }
        
        isHovering = true;
        
        // Forzar cursor personalizado
        target.style.cursor = 'none';
    });
    
    // Evento de salida de elementos
    document.addEventListener('mouseout', function(e) {
        const target = e.target;
        const relatedTarget = e.relatedTarget;
        
        // Si salimos del documento completo
        if (!relatedTarget || relatedTarget === document.documentElement) {
            cursor.style.display = 'none';
            cursorFollow.classList.remove('visible');
            return;
        }
        
        // Resetear cursor
        cursor.className = 'cursor-custom';
        cursorFollow.classList.remove('visible');
        isHovering = false;
    });
    
    // Evento de clic izquierdo (brillo)
    document.addEventListener('mousedown', function(e) {
        // Solo para clic izquierdo
        if (e.button === 0) {
            cursor.classList.add('click');
            isMouseDown = true;
            
            // Forzar que no aparezca el cursor del sistema al hacer clic
            e.target.style.cursor = 'none';
            document.body.style.cursor = 'none';
        }
    });
    
    document.addEventListener('mouseup', function(e) {
        // Solo para clic izquierdo
        if (e.button === 0) {
            cursor.classList.remove('click');
            isMouseDown = false;
            
            // Asegurar que el cursor personalizado siga activo
            e.target.style.cursor = 'none';
            document.body.style.cursor = 'none';
        }
    });
    
    // Evento de clic global para asegurar cursor personalizado
    document.addEventListener('click', function(e) {
        // Forzar cursor personalizado después de cualquier clic
        setTimeout(() => {
            document.body.style.cursor = 'none';
            e.target.style.cursor = 'none';
            
            // Asegurar que el cursor esté visible
            cursor.style.display = 'block';
            
            // Resetear clase click después de un tiempo
            cursor.classList.remove('click');
            isMouseDown = false;
        }, 50);
    });
    
    // Ocultar cursor cuando sale de la ventana
    document.addEventListener('mouseleave', function() {
        cursor.style.display = 'none';
        cursorFollow.classList.remove('visible');
        isMouseDown = false;
        cursor.classList.remove('click');
    });
    
    // Mostrar cursor cuando entra a la ventana
    document.addEventListener('mouseenter', function() {
        cursor.style.display = 'block';
        document.body.style.cursor = 'none';
        isMouseDown = false;
        cursor.classList.remove('click');
    });
    
    // Forzar cursor personalizado en focus events
    document.addEventListener('focus', function(e) {
        e.target.style.cursor = 'none';
        document.body.style.cursor = 'none';
    }, true);
    
    document.addEventListener('blur', function(e) {
        e.target.style.cursor = 'none';
        document.body.style.cursor = 'none';
        // Resetear cursor al perder focus
        cursor.classList.remove('click');
        isMouseDown = false;
    }, true);
    
    // Prevenir selección de texto que pueda causar problemas
    document.addEventListener('selectstart', function(e) {
        // Permitir selección normal pero mantener cursor personalizado
        setTimeout(() => {
            document.body.style.cursor = 'none';
            cursor.style.display = 'block';
        }, 10);
    });
    
    // Iniciar animación del cursor
    updateCursorPosition();
    
    // Forzar cursor personalizado cada 200ms (seguridad adicional)
    setInterval(() => {
        document.body.style.cursor = 'none';
        cursor.style.display = 'block';
        
        // Resetear estado de clic si está atascado
        if (isMouseDown) {
            cursor.classList.remove('click');
            isMouseDown = false;
        }
    }, 200);
});
