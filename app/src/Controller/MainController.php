<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    public function start()
    {
        return new Response(
            '<html><body></body></html>'
        );
    }
}
