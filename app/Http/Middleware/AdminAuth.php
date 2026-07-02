<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        $password = config('app.admin_password');

        // Vérifier le mot de passe dans le header ou la session
        if (session('admin_auth') !== true) {
            $authHeader = $request->header('X-Admin-Password');

            if ($authHeader !== $password) {
                return response()->json([
                    'error' => 'Non autorisé'
                ], 401);
            }

            // Marquer comme authentifié pour cette session
            session(['admin_auth' => true]);
        }

        return $next($request);
    }
}
