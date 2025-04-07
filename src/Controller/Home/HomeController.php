<?php

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig');
    }

}