<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class TicketController extends Controller
{
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

    public function cancelTicket($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('client_id', Auth::id())
            ->firstOrFail();

        $ticket->delete();

        return response()->json(['message' => 'Billet annulé']);
    }
}
