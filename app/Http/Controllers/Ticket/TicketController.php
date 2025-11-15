<?php

namespace App\Http\Controllers\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetType;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function imprimir(Request $request)
    {
        $request->validate([
            'ticketId' => 'required|integer'
        ]);

        $ticketId = $request->input('ticketId');

        $ticket = Bet::with('betLines','racing')
                    ->findOrFail($ticketId);

        $betTypeId = $ticket->betLines()->first()->bet_type_id;
        $betTypeData = BetType::find($betTypeId);
        
        // Si es una petición AJAX o desde iframe, no usar layout
        if (request()->ajax() || request()->has('iframe')) {
            return view('tickets.imprimir-simple', compact('ticket', 'betTypeData'));
        }
        
        return view('tickets.imprimir', compact('ticket', 'betTypeData'));
        
        //return view('tickets.imprimir', compact('ticket'));
    }

    public function print($ticketId)
    {
        $ticket = Bet::with('betLines','racing')
                    ->findOrFail($ticketId);

        $betTypeId = $ticket->betLines()->first()->bet_type_id;
        $betTypeData = BetType::find($betTypeId);
        
        // Si es una petición AJAX o desde iframe, no usar layout
        if (request()->ajax() || request()->has('iframe')) {
            return view('tickets.imprimir-simple', compact('ticket', 'betTypeData'));
        }
        
        return view('tickets.imprimir', compact('ticket', 'betTypeData'));
    }

    public function printWinner($ticketId)
    {
        $ticket = Bet::with('betLines','racing')
                    ->findOrFail($ticketId);
        
        $isPaid = false;
        $isWinner = false;
        $amountToPay = 0;
        foreach ($ticket->betLines as $key => $bet) {
            if ($bet->status == 'paid') {
                $isPaid = true;
                break;
            }

            if ($bet->type == 'win') {
                $amountToPay += $bet->amount_pay;
                $isWinner = true;
            }
        }

        if ($isPaid) {
            flash()->warning('Ticket ya pagado.');
            return;
        }

        if ($isWinner) {
            $betTypeId = $ticket->betLines()->first()->bet_type_id;
            $betTypeData = BetType::find($betTypeId);
            
            return view('tickets.print-winner', compact('ticket', 'betTypeData', 'amountToPay'));
        } else {
            flash()->warning('Ticket no ganador.');
            return;
        }
    }  
}
