<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Ticket;

class ClientController extends Controller
{
    // 🔐 Inscription client
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $client = Client::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Inscription réussie', 'client' => $client]);
    }

    // 🔐 Connexion client
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::guard('client')->attempt($credentials)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $client = Auth::guard('client')->user();
        $token = $client->createToken('client-token')->plainTextToken;

        return response()->json(['token' => $token, 'client' => $client]);
    }

    // 👤 Voir son profil
    public function showProfile()
    {
        return response()->json(Auth::guard('client')->user());
    }

    // ✏️ Modifier son profil
    public function updateProfile(Request $request)
    {
        $client = Auth::guard('client')->user();

        $request->validate([
            'nom' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:clients,email,' . $client->id,
        ]);

        $client->update($request->only(['nom', 'email']));

        return response()->json(['message' => 'Profil mis à jour', 'client' => $client]);
    }

    // 🎟️ Acheter un ticket
    public function purchaseTicket(Request $request)
    {
        $request->validate([
            'match_id' => 'required|exists:matches,id',
            'quantite' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $ticket = Ticket::create([
            'client_id' => Auth::id(),
            'match_id' => $request->match_id,
            'quantite' => $request->quantite,
            'total' => $request->total,
        ]);

        return response()->json(['message' => 'Billet acheté avec succès', 'ticket' => $ticket]);
    }

    // ✏️ Modifier un ticket
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('client_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'quantite' => 'sometimes|required|integer|min:1',
            'total' => 'sometimes|required|numeric|min:0',
        ]);

        $ticket->update($request->only(['quantite', 'total']));

        return response()->json(['message' => 'Billet mis à jour', 'ticket' => $ticket]);
    }

    // ❌ Annuler un ticket
    public function cancelTicket($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('client_id', Auth::id())
            ->firstOrFail();

        $ticket->delete();

        return response()->json(['message' => 'Billet annulé']);
    }
}
