<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\ProductsOrders;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commandes', name: 'orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(
        SessionInterface $session,
        ProductsRepository $productsRepository,
        EntityManagerInterface $em
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('main');
        }

        $order = new Orders();

        $order->setUser($this->getUser());

        foreach($panier as $item => $quantity){
            $orderDetails = new ProductsOrders();

            $product = $productsRepository->find($item);
            
            $price = $product->getPrice();

            $orderDetails->setProducts($product)
                ->setPriceTot($price)
                ->setQuantity($quantity);

            $order->addProductsOrder($orderDetails)
                ->setStatus('');
        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');

        return $this->redirectToRoute('main');
    }
}
