<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Competition;

class CompetitionController extends Controller
{
    // 📥 Liste toutes les compétitions disponibles
    public function index()
    {
        return response()->json(Competition::all());
    }
}

