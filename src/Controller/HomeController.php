<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\SizeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
        /**
     * Page d'accueil côté utilisateur
     */
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * Collection de tous les produits côté utilisateur
     */
    #[Route('/product', name: 'product')]
    public function product(ProductRepository $repository): Response
    {
        return $this->render('home/product.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $repository->findAll(),
        ]);
    }

     /**
     * Détail d'un produit côté utilisateur
     */
    #[Route('/product/{id}', name: 'product_display')]
    public function product_display(ProductRepository $repository, int $id): Response
    {
        $product = new Product;
        $product = $repository->find($id);

        return $this->render('home/product_display.html.twig', [
            'controller_name' => 'HomeController',
            'product' => $product
        ]);
    }
}
