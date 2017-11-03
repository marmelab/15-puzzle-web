<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class HelloController
{
    public function start()
    {
        $msg = 'Hello Puzzle!';

        return new Response(
            '<html><body>' . $msg . '</body></html>'
        );
    }
}
