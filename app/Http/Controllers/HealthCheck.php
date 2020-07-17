<?php

namespace App\Http\Controllers;

class HealthCheckController extends Controller
{
    /**
     * Health check
     *
     * @return string
     */
    public function check()
    {
        return 'Application up and running';
    }

}
