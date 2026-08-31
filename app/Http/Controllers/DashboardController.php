<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless($request->user()?->is_admin, 403);

        $reservations = Reservation::with('watch')
            ->latest()
            ->get();

        return inertia('Dashboard', [
            'reservations' => $reservations,
        ]);
    }

    public function updateStatus(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'Nouvelle demande',
                    'Confirmée',
                    'Commandée',
                    'Disponible',
                    'Terminée',
                    'Annulée',
                ]),
            ],
        ]);

        $reservation->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back();
    }
}
