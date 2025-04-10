<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['competition_id', 'equipe1', 'equipe2', 'date_match'];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
    public function OrganisateurEvenement()
    {
        return $this->belongsTo(OrganisateurEvenement::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}

