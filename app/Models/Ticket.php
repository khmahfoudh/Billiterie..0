<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['client_id', 'match_id', 'quantite'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function match()
    {
        return $this->belongsTo(Game::class);
    }
}


