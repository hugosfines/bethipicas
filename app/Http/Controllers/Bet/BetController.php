<?php

namespace App\Http\Controllers\Bet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BetController extends Controller
{
    public function viewFormBetMenu()
    {
        return view('bets.bet-menu');
    }
}
