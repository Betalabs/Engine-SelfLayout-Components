<?php

namespace Betalabs\EngineSelfLayoutComponentsListeners;


use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine;
use Illuminate\Auth\Authenticatable;
use Laravel\Passport\Passport;

abstract class AbstractEngineIntegratedListener
{
    protected function authenticate(Authenticatable $authenticatable)
    {
        Passport::actingAs($authenticatable);
        Engine::auth($authenticatable);
    }
}