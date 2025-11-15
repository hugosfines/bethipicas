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
        .mt-2 { margin-top: 8px; }
        .mb-1 { margin-bottom: 4px; }
        .mb-2 { margin-bottom: 8px; }
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .text-xs {font-size: 12px; }
        .text-sm {font-size: 14px; }
    </style>
</head>
<body>
    <div class="ticket" style="padding-left: 15px !important;">
        <!-- Mismo contenido que imprimir-simple -->
        <div class="border-bottom py-1 mb-1">
            <div class="bold">{{ $ticket->user->headquarters()->latest()->first()->company->name }}</div>
            <div><span class="bold">Taq: {{ strtolower(Str::ucfirst($ticket->user->headquarters()->latest()->first()->name)) }}</span></div>
            <div><span class="bold">Ticket: # {{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</span></div>
            <div>
                <span class="bold">Código: {{ str_pad($ticket->custom_code, 6, '0', STR_PAD_LEFT) }}</span> - 
                <span class="bold">{{ $ticket->created_at->format('d/m/Y') }}</span>
            </div>
            @php
                $carr = $ticket->racing->race;
            @endphp
            <div class="bold">Carr: {{ $carr }}</div>
            {{-- @php
                $combinations = $ticket->betLines->count();
            @endphp --}}
        </div>

        <div class="border-bottom border-top mb-1">
            <span class="bold">
                Total = {{ number_format($amountToPay, 2) }}
            </span>
        </div>

        {{-- <div class="border-bottom border-top mb-1">
            <div class="text-center bold py-1">{{ $ticket->racing->calendar->track->name }}</div>
            @php
                $viewType = true;
                $picks = [];
                $betTypeId = $betTypeData->id;
            @endphp

            <!-- ganador, place, show -->
            @if (in_array($betTypeId, [1,2,3]))
                @php
                    $apuestas = createGPS($ticket, $carr);
                @endphp
                @foreach ($apuestas as $key => $apuesta)
                    <div>
                        <span class="bold">{{ substr($apuesta['apuesta'], 0, 3) . '. => ' . $apuesta['nro'] }}</span>
                        <span class="bold" style="margin-left:6px;">x &nbsp;${{ number_format($ticket->amount, 2) }}</span>
                    </div>
                @endforeach
            @else
                @if ($viewType == true)
                    <div class="bold">{{ $betTypeData->name }} x ${{ number_format($ticket->amount, 2) }}</div>
                    @php
                        $viewType = false;
                    @endphp
                @endif
            @endif

            <!-- exacta, trifecta, superfecta -->
            @if (in_array($betTypeId, [4,5,6]))
                @php
                    $apuestas = createETSStep($ticket, $carr);
                    $withStep = true;
                @endphp
                @foreach ($apuestas as $key => $apuesta)
                    <div class="bold">
                        @if ($withStep)
                            {{ $apuesta['carr'].' => ' . $apuesta['nro'] }}
                        @else
                            {{ $apuesta['nro'] }}
                        @endif
                    </div>
                @endforeach
            @endif

            <!-- picks -->
            @if (in_array($betTypeId, [8,9,10,11,12]))
                @php
                    $apuestas = createPICK($ticket, $carr);
                @endphp
                @foreach ($apuestas as $key => $apuesta)
                    <div class="bold">
                        {{ 'Carr. ' . $apuesta['carr'].' => ' . $apuesta['nro'] }}
                    </div>
                @endforeach
            @endif
        </div> --}}

        {{-- <div class="mt-1">
            @php
                $total = ($combinations * $ticket->amount);
            @endphp

            <div class="bold">Total: = $ {{ number_format($total, 2) }}</div>
        </div> --}}

        {{-- <div class="border-top text-center py-2 mt-2">
            <div class="bold">Sin Ticket no se paga</div>
            <div class="bold">El ticket caduca a los 7 días</div>
            <div class="bold">¡Buena Suerte!</div>
        </div> --}}
    </div>

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
                window.close();
                //window.onafterprint = () => window.close();
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
</body>
</html>