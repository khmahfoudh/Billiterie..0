<?php
namespace App\Http\Controllers\Organisateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\OrganisateurEvenement;
use App\Models\Game;

class OrganisateurController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email|unique:organisateur_evenements,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $organisateur = OrganisateurEvenement::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Inscription réussie', 'organisateur' => $organisateur]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::guard('organisateur')->attempt($credentials)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $organisateur = Auth::guard('organisateur')->user();
        $token = $organisateur->createToken('organisateur-token')->plainTextToken;

        return response()->json(['token' => $token, 'organisateur' => $organisateur]);
    }

    public function showProfile()
    {
        return response()->json(Auth::guard('organisateur')->user());
    }

    public function updateProfile(Request $request)
    {
        $organisateur = Auth::guard('organisateur')->user();
        $organisateur->update($request->only(['nom', 'email']));

        return response()->json(['message' => 'Profil mis à jour', 'organisateur' => $organisateur]);
    }

    // ✅ Lister tous les matches créés par cet organisateur
    public function listMyMatches()
    {
        $matches = Game::with('competition')
            ->where('organisateur_id', Auth::id())
            ->get();

        return response()->json($matches);
    }

    // ✅ Créer un match dans une compétition déjà existante
    public function createMatch(Request $request)
    {
        $request->validate([
            'equipe1' => 'required|string',
            'equipe2' => 'required|string',
            'date_match' => 'required|date',
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $match = Game::create([
            'equipe1' => $request->equipe1,
            'equipe2' => $request->equipe2,
            'date_match' => $request->date_match,
            'competition_id' => $request->competition_id,
            'organisateur_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Match créé', 'match' => $match]);
    }

    // ✅ Supprimer un de ses matches
    public function deleteMatch($id)
    {
        $match = Game::where('id', $id)
            ->where('organisateur_id', Auth::id())
            ->firstOrFail();

        $match->delete();
        return response()->json(['message' => 'Match supprimé']);
    }
}
