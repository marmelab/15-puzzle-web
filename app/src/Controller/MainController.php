<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller {
  private $twig;

  public function __construct(\Twig_Environment $twig) {
    $this->twig = $twig;
  }
 
  public function start() {
    return new Response($this->twig->render('main.html.twig'));
  }
}
