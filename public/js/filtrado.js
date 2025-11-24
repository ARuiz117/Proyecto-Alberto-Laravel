// Filtrado de juegos con animaciones suaves

document.addEventListener('DOMContentLoaded', function() {
    const filtroInput = document.getElementById('filtroJuegos');
    const juegosCards = document.querySelectorAll('.juego-card');
    const juegosGrid = document.querySelector('.juegos-grid');
    
    let debounceTimer = null;
    let animationFrameId = null;
    
    // Función principal de filtrado
    function filtrarJuegos(query) {
        // Cancelar animación anterior si existe
        if (animationFrameId) {
            cancelAnimationFrame(animationFrameId);
        }
        
        const juegosCards = document.querySelectorAll('.juego-card');
        let hayResultados = false;
        
        // Limpiar mensajes anteriores
        const mensajesAnteriores = juegosGrid.querySelectorAll('.no-resultados');
        mensajesAnteriores.forEach(msg => msg.remove());
        
        // Si no hay búsqueda, mostrar todos
        if (!query) {
            animationFrameId = requestAnimationFrame(() => {
                juegosCards.forEach((card, index) => {
                    // Mostrar con animación escalonada
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            card.style.transform = 'translateY(0)';
                            card.style.opacity = '1';
                            card.style.pointerEvents = 'auto';
                            card.style.display = 'block';
                        });
                    }, index * 16);
                });
            });
            return;
        }
        
        // Filtrar juegos
        animationFrameId = requestAnimationFrame(() => {
            juegosCards.forEach((card, index) => {
                const titulo = card.getAttribute('data-titulo') || '';
                const genero = card.getAttribute('data-genero') || '';
                const coincide = titulo.toLowerCase().includes(query) || 
                               genero.toLowerCase().includes(query);
                
                if (coincide) {
                    // Mostrar con animación
                    hayResultados = true;
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            card.style.transform = 'translateY(0)';
                            card.style.opacity = '1';
                            card.style.pointerEvents = 'auto';
                            card.style.display = 'block';
                        });
                    }, index * 16);
                    
                    // Efecto especial si coincide exactamente
                    if (titulo.toLowerCase() === query.toLowerCase()) {
                        setTimeout(() => {
                            requestAnimationFrame(() => {
                                card.style.boxShadow = '0 0 30px rgba(102, 192, 244, 0.6)';
                                card.style.borderColor = '#66c0f4';
                                
                                // Quitar efecto después
                                setTimeout(() => {
                                    requestAnimationFrame(() => {
                                        card.style.boxShadow = '';
                                        card.style.borderColor = '';
                                    });
                                }, 1000);
                            });
                        }, 500 + (index * 16));
                    }
                } else {
                    // Ocultar con animación
                    setTimeout(() => {
                        requestAnimationFrame(() => {
                            card.style.transform = 'translateY(20px)';
                            card.style.opacity = '0';
                            card.style.pointerEvents = 'none';
                            
                            // Ocultar completamente
                            setTimeout(() => {
                                requestAnimationFrame(() => {
                                    card.style.display = 'none';
                                });
                            }, 400);
                        });
                    }, index * 8);
                }
            });
            
            // Mensaje si no hay resultados
            if (!hayResultados) {
                setTimeout(() => {
                    requestAnimationFrame(() => {
                        const mensajeNoResultados = document.createElement('div');
                        mensajeNoResultados.innerHTML = `
                            <div style="text-align: center; padding: 3rem; color: #66c0f4;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
                                <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">No se encontraron juegos</h3>
                                <p style="font-size: 1rem; opacity: 0.8;">Prueba buscando por título o género</p>
                            </div>
                        `;
                        juegosGrid.appendChild(mensajeNoResultados);
                    });
                }, 300);
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
