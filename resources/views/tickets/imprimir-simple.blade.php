<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $ticket->id }}</title>
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
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        .ticket {
            width: 76mm;
            margin: 0 auto;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .border-bottom { border-bottom: 1px dashed #000; }
        .border-top { border-top: 1px dashed #000; }
        .mt-1 { margin-top: 4px; }
        .mb-1 { margin-bottom: 4px; }
        .py-1 { padding-top: 4px; padding-bottom: 4px; }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 500);">
    <div class="ticket">
        <!-- Encabezado -->
        <div class="text-center border-bottom py-1 mb-1">
            <div class="bold">HIPÓDROMO NACIONAL</div>
            <div>SISTEMA DE APUESTAS</div>
            <div>Ticket: #{{ str_pad($ticket->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div>{{ $ticket->created_at->format('d/m/Y H:i:s') }}</div>
        </div>

        <!-- Información del cliente -->
        <div class="mb-1">
            <div><span class="bold">Cliente:</span> {{ $ticket->cliente->nombre }}</div>
            <div><span class="bold">Cédula:</span> {{ $ticket->cliente->cedula }}</div>
        </div>

        <!-- Detalles de apuestas -->
        <div class="border-bottom border-top mb-1">
            <div class="text-center bold py-1">APUESTAS REALIZADAS</div>
            @foreach($ticket->apuestas as $index => $apuesta)
            <div class="py-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="bold">Carrera {{ $apuesta->carrera->numero }}</div>
                <div>Caballo: {{ $apuesta->caballo->numero }} - {{ $apuesta->caballo->nombre }}</div>
                <div>Tipo: {{ strtoupper($apuesta->tipo_apuesta) }}</div>
                <div>Monto: ${{ number_format($apuesta->monto, 2) }}</div>
                @if($apuesta->posible_ganancia)
                <div class="bold">Posible Ganancia: ${{ number_format($apuesta->posible_ganancia, 2) }}</div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Totales -->
        <div class="mt-1">
            <div class="text-right bold">Total Apostado: ${{ number_format($ticket->total_apostado, 2) }}</div>
            @if($ticket->posible_ganancia_total)
            <div class="text-right bold">Posible Ganancia Total: ${{ number_format($ticket->posible_ganancia_total, 2) }}</div>
            @endif
        </div>

        <!-- Pie del ticket -->
        <div class="text-center mt-1 border-top py-1">
            <div>*** {{ $ticket->codigo_verificacion }} ***</div>
            <div class="bold">¡Buena suerte!</div>
            <div>Conserve este ticket</div>
        </div>
    </div>

    <!-- Script de respaldo en caso de que falle el auto-close -->
    <script>
        // Forzar impresión y cierre
        window.onafterprint = function() {
            setTimeout(() => window.close(), 100);
        };
        
        // Respaldo: si después de 3 segundos no se cerró, ofrecer botón
        setTimeout(() => {
            if (!window.closed) {
                const btn = document.createElement('button');
                btn.textContent = 'Cerrar Ventana';
                btn.style.cssText = 'position:fixed; top:10px; right:10px; background:red; color:white; padding:5px;';
                btn.onclick = () => window.close();
                document.body.appendChild(btn);
            }
        }, 3000);
    </script>
</body>
</html>