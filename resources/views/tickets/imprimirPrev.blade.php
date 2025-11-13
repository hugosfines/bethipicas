<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión Ticket {{ $ticket->id }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }
            body {
                margin: 0;
                padding: 2mm;
                font-family: 'Courier New', monospace;
                font-size: 12px;
                background: white;
            }
            .no-print { display: none !important; }
        }
        
        /* Estilos para vista previa en navegador */
        @media screen {
            body {
                padding: 20px;
                background: #f5f5f5;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .ticket {
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                background: white;
                padding: 10px;
            }
        }
        
        .ticket {
            width: 76mm;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .semi-bold { font-weight: 500; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .border-top { border-top: 1px dashed #000; }
        .mt-1 { margin-top: 4px; }
        .mb-1 { margin-bottom: 4px; }
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .text-xs {font-size: 12px; }
        .text-sm {font-size: 14px; }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Mismo contenido que imprimir-simple -->
        <div class="border-bottom py-1 mb-1">
            <div class="bold">{{ $ticket->user->headquarters()->latest()->first()->company->name }}</div>
            <div><span class="semi-bold">Taq:</span> {{ strtolower(Str::ucfirst($ticket->user->headquarters()->latest()->first()->name)) }}</div>
            <div><span class="semi-bold">Ticket: #</span> {{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div>
                <span class="semi-bold">Código:</span> {{ str_pad($ticket->custom_code, 6, '0', STR_PAD_LEFT) }} - 
                <span class="semi-bold">{{ $ticket->created_at->format('d/m/Y') }}</span>
            </div>
            @php
                $carr = $ticket->racing->race;
            @endphp
            <div>Carr: {{ $carr }}</div>
        </div>

        <div class="border-bottom border-top mb-1">
            <div class="text-center bold py-1">APUESTAS REALIZADAS</div>
            @php
                $viewType = true;
                $picks = [];
            @endphp

            @foreach($ticket->betLines as $index => $apuesta)
                @php
                    $jugadas = [];
                    $stringJugadas = '';
                @endphp
                <div class="py-0 {{-- $loop->last ? 'border-bottom' : '' --}}">
                    @if (in_array($apuesta->bet_type_id, [1,2,3]))
                        @for ($i = 1; $i < 6; $i++)
                            @if ($apuesta->{"step_$i"})
                                <div>
                                    <span class="bold">{{ substr($apuesta->betType->name, 0, 3) }}</span>. => {{ $apuesta->{"step_$i"} }}
                                </div>
                            @endif
                        @endfor
                    @else
                        @if ($viewType == true)
                            <div class="bold">{{ $apuesta->betType->name }}</div>
                            @php
                                $viewType = false;
                            @endphp
                        @endif

                        @if ($apuesta->betType->category->type_follow == 'current')
                            @for ($i = 1; $i < 6; $i++)
                                @if ($apuesta->{"step_$i"})
                                    @php
                                        $jugadas[$index][] = $apuesta->{"step_$i"};
                                        $flattened = array_merge(...$jugadas);
                                        $stringJugadas = implode(', ', $flattened);
                                    @endphp
                                @endif
                            @endfor

                            <div class="semi-bold">
                                {{$stringJugadas}}
                            </div>
                        @elseif ($apuesta->betType->category->type_follow == 'next')
                            @for ($i = 1; $i <= 6; $i++)
                                @if (!empty($apuesta->{"step_$i"}))
                                    @php
                                        $picks[$i][$apuesta->{"step_$i"}] = $i;
                                    @endphp
                                @endif
                            @endfor
                        @endif
                    @endif
                    {{-- <div class="bold">Nro. {{ $apuesta->{"step_$i"} }}</div> --}}
                    {{-- <div class="bold">Carrera {{ $apuesta->carrera->numero }}</div>
                    <div>Caballo: {{ $apuesta->caballo->numero }} - {{ $apuesta->caballo->nombre }}</div> --}}
                    {{-- <div>Tipo: {{ strtoupper($apuesta->tipo_apuesta) }}</div>
                    <div>Monto: ${{ number_format($apuesta->monto, 2) }}</div> --}}
                    {{-- @if($apuesta->posible_ganancia)
                    <div class="bold">Posible Ganancia: ${{ number_format($apuesta->posible_ganancia, 2) }}</div>
                    @endif --}}
                </div>
            @endforeach
            
            <!-- mostrar picks -->
            @php
                $newCarr = $carr;
            @endphp
            @foreach ($picks as $keyStep => $jugadas)
                @php
                    $carrPicks = [];
                @endphp
                @foreach ($jugadas as $keyNro => $jugada)
                    @php
                        $carrPicks[$keyStep][] = $keyNro;
                        $flattened = array_merge(...$carrPicks);
                        $stringJugadas = implode(', ', $flattened);
                    @endphp
                @endforeach
                
                <div>
                    {{ 'Carr. ' . $newCarr.' => ' . $stringJugadas }}
                </div>

                @php
                    $newCarr++;
                @endphp
            @endforeach
            
        </div>

        {{-- <div class="mt-1">
            <div class="text-right bold">Total Apostado: ${{ number_format($ticket->total_apostado, 2) }}</div>
            @if($ticket->posible_ganancia_total)
            <div class="text-right bold">Posible Ganancia Total: ${{ number_format($ticket->posible_ganancia_total, 2) }}</div>
            @endif
        </div>

        <div class="text-center mt-1 border-top py-1">
            <div>*** {{ $ticket->codigo_verificacion }} ***</div>
            <div class="bold">¡Buena suerte!</div>
            <div>Conserve este ticket</div>
        </div> --}}
    </div>

    <!-- Controles para vista normal (no impresión) -->
    <!-- <div class="no-print text-center mt-4">
        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">🖨️ Imprimir</button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded">❌ Cerrar</button>
    </div> -->

<script>
    class PrintManager {
        constructor() {
            this.printInProgress = false;
            this.observer = null;
            this.init();
        }

        init() {
            if (window.opener || window.name === 'modal') {
                // Esperar a que la página cargue completamente
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', () => this.startPrint());
                } else {
                    this.startPrint();
                }
            }
        }

        startPrint() {
            setTimeout(() => {
                this.setupPrintDetection();
                window.print();
            }, 100);
        }

        setupPrintDetection() {
            this.printInProgress = true;

            // Método 1: onafterprint (si está disponible)
            if ('onafterprint' in window) {
                window.onafterprint = () => {
                    this.cleanupAndClose();
                };
            }

            // Método 2: Detección por Media Query (más confiable)
            const mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener((mql) => {
                if (!mql.matches && this.printInProgress) {
                    this.cleanupAndClose();
                }
            });

            // Método 3: Detección por cambios en el documento durante impresión
            this.setupDOMObserver();

            // Método 4: Para navegadores que no soportan bien los eventos anteriores
            this.setupFocusDetection();
        }

        setupDOMObserver() {
            // Observar cambios que ocurren durante la impresión
            this.observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'attributes' && 
                        mutation.attributeName === 'style' &&
                        document.documentElement.style.display === 'none') {
                        this.cleanupAndClose();
                    }
                });
            });

            this.observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['style']
            });
        }

        setupFocusDetection() {
            // Cuando la ventana recupera el foco (después del diálogo de impresión)
            window.addEventListener('focus', () => {
                setTimeout(() => {
                    if (this.printInProgress && document.hasFocus()) {
                        this.cleanupAndClose();
                    }
                }, 100);
            });
        }

        cleanupAndClose() {
            if (this.printInProgress) {
                this.printInProgress = false;
                
                if (this.observer) {
                    this.observer.disconnect();
                }

                // Limpiar eventos
                window.onafterprint = null;
                
                // Cerrar la ventana
                if (!window.closed) {
                    window.close();
                }
            }
        }
    }

    // Inicializar cuando la página cargue
    new PrintManager();
</script>
    <script>
        // Auto-imprimir si es una ventana emergente
        /* if (window.opener || window.name === 'modal') {
            window.print();
            window.close();
        } */
    </script>
</body>
</html>