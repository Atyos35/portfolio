<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home')]
    public function home(Security $security): Response
    {
        if($security->getUser() && $security->getUser()->isVerified()){
            return $this->render('home/home.html.twig');
        }
        return $this->render('security/login.html.twig');
    }

}