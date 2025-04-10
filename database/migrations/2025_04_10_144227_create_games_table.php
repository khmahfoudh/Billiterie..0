<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('equipe1');
            $table->string('equipe2');
            $table->dateTime('date_game');
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->foreignId('organisateur_evenement_id')->constrained('organisateur_evenements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
}

