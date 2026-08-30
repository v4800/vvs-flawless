<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function index()
    {
        $watches = Watch::latest()->get();

        return inertia('Watches/Index', [
            'watches' => $watches,
        ]);
    }

    public function show(
        Request $request,
        Watch $watch
    ) {
        $selectedMovement =
            $request->query('movement') === 'Suisse'
                ? 'Suisse'
                : 'Japonais';

        return inertia('Watches/Show', [
            'watch' => $watch,
            'selectedMovement' => $selectedMovement,
        ]);
    }
}