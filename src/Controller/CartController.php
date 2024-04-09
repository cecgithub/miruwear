<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    private $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/', name: 'show')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $this->cartService->getCartDetails()[0],
            'total' => $this->cartService->getCartDetails()[1]
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function addToCart(Product $product): Response
    {
        $this->cartService->addToCart($product);
        return $this->redirectToRoute('product');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromCart(Product $product): Response
    {
        $this->cartService->removeFromCart($product);
        return $this->redirectToRoute('app_cart_show');
    }
}
