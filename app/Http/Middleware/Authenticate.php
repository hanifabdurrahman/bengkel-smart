<?php


namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Redirect ketika user belum login.
     */
    protected function redirectTo(Request $request): ?string
    {
        return route('login');
    }
}