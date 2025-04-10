<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // 📥 Affiche tous les matches d'une compétition
    public function indexByCompetition($competitionId)
    {
        $matches = Game::with('organisateur')
            ->where('competition_id', $competitionId)
            ->get();

        return response()->json($matches);
    }
}
