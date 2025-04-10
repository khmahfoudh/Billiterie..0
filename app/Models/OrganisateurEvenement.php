<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganisateurEvenement extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['nom', 'email', 'password'];
    protected $hidden = ['password'];

    public function competitions()
    {
        return $this->hasMany(Competition::class, 'organisateur_id');
    }
}
