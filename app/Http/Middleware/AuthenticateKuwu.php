<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

class AuthenticateKuwu extends FilamentAuthenticate
{
    protected function authenticate($request, array $guards): void
    {
        if (empty($guards)) {
            $guards = ['kuwu'];
        }

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                auth()->shouldUse($guard);
                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
