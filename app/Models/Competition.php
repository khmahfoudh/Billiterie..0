<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = ['nom', 'lieu', 'date_debut', 'date_fin', 'organisateur_id'];

    public function matches()
    {
        return $this->hasMany(Game::class);
    }

    public function organisateur()
    {
        return $this->belongsTo(OrganisateurEvenement::class, 'organisateur_id');
    }
}
