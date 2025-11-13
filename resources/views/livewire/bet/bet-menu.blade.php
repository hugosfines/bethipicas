<div x-data="{ loading: @entangle('loading') }">

    <!-- Loading Indicator -->
    <x-loading/>

    @include('livewire.bet.partials.header-bet')

    @include('livewire.bet.partials.body-bet')

    @push('js')
        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('abrir-impresion', (event) => {
                    // Abrir ventana de impresión - GET
                    /*const url = `/tickets/imprimir/${event.ticketId}`;
                    const ventana = window.open(url, '_blank', 'width=300,height=400');*/

                    // Abrir ventana de impresión - POST
                    // Crear un formulario temporal
                    // Crear un formulario temporal
                    // Obtener CSRF token
                    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Nombre único para la ventana para apuntar el form
                    const winName = 'ticket_print_' + Date.now();

                    // Abrir la ventana (si el popup es bloqueado, newWin será null)
                    const newWin = window.open('', winName, 'width=400,height=600');

                    if (!newWin) {
                        alert('El navegador bloqueó la ventana emergente. Permite ventanas emergentes para esta página.');
                        return;
                    }

                    // Crear formulario en el documento actual
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/tickets/imprimir'; // ruta sin id
                    form.target = winName;
                    form.style.display = 'none';

                    // CSRF
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrf;
                    form.appendChild(tokenInput);

                    // ticketId (va por POST)
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'ticketId';
                    idInput.value = event.ticketId;
                    form.appendChild(idInput);

                    // Agregar el form, submit y limpiar
                    document.body.appendChild(form);
                    form.submit();

                    // opcional: limpiar el form después de un pequeño delay
                    setTimeout(() => {
                        document.body.removeChild(form);
                        // Nota: no cerramos newWin aquí porque la vista del servidor puede hacer window.print() y/o cerrar la ventana cuando termine.
                    }, 1000);
                });

                Livewire.on('desmarcar-calendarios', (data) => {
                    // Desmarcar todos los checkboxes con name="check-calendar"
                    /* document.querySelectorAll('input[name="check-calendar-'+roleId+'"]').forEach(checkbox => {
                        checkbox.checked = false;
                    }); */
                    //console.log(data[0]['a']);
                    /* let roleId = data[0]['a'];
                    let time = data[0]['b'];
                    const targetId = `slot-${roleId}-${time}`;
                    
                    document.querySelectorAll(`input[name="check-calendar-${roleId}"]`).forEach(checkbox => {
                        if (checkbox.id !== targetId) {
                            checkbox.checked = false;
                        }
                    }); */
                });

                Livewire.on('marcar-numeros', (data) => {
                    let nro = data[0]['nro'];
                    let step = data[0]['step'];
                    const targetId = `nro-${nro}-${step}`;
                    
                    document.querySelectorAll(`input[name="check-apuestas"]`).forEach(checkbox => {
                        if (checkbox.id == targetId) {
                            checkbox.checked = true;
                        }
                    });
                });

                Livewire.on('desmarcar-numeros', (data) => {
                    let nro = data[0]['nro'];
                    let step = data[0]['step'];
                    const targetId = `nro-${nro}-${step}`;
                    
                    document.querySelectorAll(`input[name="check-apuestas"]`).forEach(checkbox => {
                        if (checkbox.id == targetId) {
                            checkbox.checked = false;
                        }
                    });
                });

                Livewire.on('clear-selectAll', () => {
                    document.querySelectorAll('input[name="check-selectAll"]').forEach(checkbox => {
                        checkbox.checked = false; // Desmarcarlos
                    });
                });

                Livewire.on('desmarcar-select-all', (nro) => {
                    const targetId = `check-selectAll-${nro}`;
                    
                    document.querySelectorAll(`input[name="check-selectAll"]`).forEach(checkbox => {
                        if (checkbox.id == targetId) {
                            checkbox.checked = false;
                        }
                    });
                });

                Livewire.on('clearBets', () => {
                    document.querySelectorAll('input[name="check-apuestas"]').forEach(checkbox => {
                        checkbox.checked = false; // Desmarcarlos
                    });
                });

                Livewire.on('clearAmounts', () => {
                    document.querySelectorAll('input[name="amount-bet-radio"]').forEach(checkbox => {
                        checkbox.checked = false; // Desmarcarlos
                    });
                });
                
            });
        </script>

        <script>
            // mutation
            document.addEventListener("DOMContentLoaded", function () {
                let observer = new MutationObserver((mutations) => {
                    let hasPrelineElements = false;

                    mutations.forEach((mutation) => {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1) { // Verifica que sea un nodo HTML válido
                                if (node.matches('[data-hs-input], [data-hs-select]') || 
                                    node.querySelector('[data-hs-input], [data-hs-select]')) {
                                    hasPrelineElements = true;
                                }
                            }
                        });
                    });

                    if (hasPrelineElements) {
                        setTimeout(() => {
                            // Guardamos los valores actuales antes de reiniciar Preline
                            document.querySelectorAll('[wire\\:model\\.live]').forEach((input) => {
                                input.dataset.currentValue = input.value; // Guardamos el valor en un atributo data
                            });

                            window.HSStaticMethods.autoInit(); // Reinicializamos Preline

                            // Restauramos los valores guardados después de reiniciar Preline
                            document.querySelectorAll('[wire\\:model\\.live]').forEach((input) => {
                                if (input.dataset.currentValue) {
                                    input.value = input.dataset.currentValue; // Restauramos el valor original
                                }
                            });
                        }, 10);
                        
                    }
                });

                // Observa solo los cambios dentro del contenedor de Livewire
                const targetNode = document.querySelector('main') || document.body;
                if (targetNode) {
                    observer.observe(targetNode, {
                        childList: true,
                        subtree: true,
                        attributes: true,
                    });
                }

                // Inicializa Preline en la carga inicial
                window.HSStaticMethods.autoInit();
            });
        </script>
    @endpush

</div>
