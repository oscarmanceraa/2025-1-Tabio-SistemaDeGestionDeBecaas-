<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulacion;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $personaId = $user->id_persona;
        $postulacion = Postulacion::with('resultado')
            ->where('id_persona', $personaId)
            ->orderBy('created_at', 'desc')
            ->first();
        return view('user.dashboard', compact('postulacion'));
    }
}
