<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RsaAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah autentikasi RSA berhasil
        if ($request->session()->get('rsa_admin_authenticated') !== true) {
            abort(403, 'Akses ditolak. Autentikasi RSA diperlukan.');
        }

        return $next($request);
    }
}
