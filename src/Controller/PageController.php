<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    // page d'accueil
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    // page de contact
    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    // page mentions légales
    #[Route('/mentions', name: 'app_mentions')]
    public function mentionslegales(): Response
    {
        return $this->render('page/mentions.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    // page de conditions générales de ventes
    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
