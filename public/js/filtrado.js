// Filtrado de juegos con animaciones suaves

document.addEventListener('DOMContentLoaded', function() {
    const filtroInput = document.getElementById('filtroJuegos');
    const juegosCards = document.querySelectorAll('.juego-card');
    const juegosGrid = document.querySelector('.juegos-grid');
    
    let debounceTimer = null;
    let animationFrameId = null;
    let isProcessing = false;
    
    // Función principal de filtrado
    function filtrarJuegos(query) {
        // Cancelar animación anterior si existe
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }
        
        const juegosCards = document.querySelectorAll('.juego-card');
        
        // Si no hay búsqueda, mostrar todos
        if (!query) {
            juegosCards.forEach(card => {
                card.style.display = 'block';
            });
            return;
        }
        
        // Filtrar juegos
        juegosCards.forEach((card) => {
            const titulo = card.getAttribute('data-titulo') || '';
            const genero = card.getAttribute('data-genero') || '';
            const coincide = titulo.toLowerCase().includes(query) || 
                           genero.toLowerCase().includes(query);
            
            if (coincide) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Evento de búsqueda
    filtroInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        // Cancelar búsqueda anterior
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }
        
        // Esperar a que deje de escribir
        debounceTimer = setTimeout(() => {
            requestAnimationFrame(() => {
                filtrarJuegos(query);
            });
        }, 300);
    });
    
    // Limpiar búsqueda con Escape
    filtroInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }
            requestAnimationFrame(() => {
                filtrarJuegos('');
            });
        }
    });
});
