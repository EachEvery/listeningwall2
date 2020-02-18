<?php

namespace App\Http\Controllers;

use Illuminate\Session\SessionManager;

class CsrfTokenController extends Controller
{
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function __invoke()
    {
        return $this->session->token();
    }
}
