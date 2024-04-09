<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
       
    }

    public function getSession()
    {
        return $this->requestStack->getSession();
    }

    public function addToCart(Product $product): void
    {
        // Get the current cart or initialize an empty array
        $cart = $this->getSession()->get('cart', []);
        // Get the id of the product
        $id = $product->getId();
        // If the product is already in the cart, increment the quantity
        if (!empty($cart[$id])) {
            $cart[$id]++;
        // Otherwise, add the product to the cart with quantity 1
        } else {
            $cart[$id] = 1;
        }

        // Save the cart back to the session
        $this->getSession()->set('cart', $cart);
    }

    public function getCartDetails(): array
    {
        // Get the current cart
        $cart = $this->getSession()->get('cart', []);
        // Initialize an empty array to hold the product details
        $cartWithData = [];
        $total = 0;
        
        // For each product in the cart, get the product details
        foreach ($cart as $id => $quantity) {
            // Find the product in the database
            $product = $this->productRepository->find($id);
            // If the product exists, add it to the cart with the quantity
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
            // Calculate the total price of the cart
            $total += $product->getPrice() * $quantity;
        }

        return [$cartWithData, $total];
    }

    public function removeFromCart(Product $product): void
    {
        // Get the current cart
        $cart = $this->getSession()->get('cart', []);
        // Get the id of the product
        $id = $product->getId();
        // If the product is in the cart, remove it
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        // Save the cart back to the session
        $this->getSession()->set('cart', $cart);
    }

    public function getCountCart()
    {
        return array_sum($this->getSession()->get('cart', []));
    }

    public function destroyCart()
    {
        $this->getSession()->remove('cart');
    }
}