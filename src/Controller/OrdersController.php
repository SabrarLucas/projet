<?php

namespace App\Controller;

use App\Entity\Details;
use App\Entity\Orders;
use App\Entity\Users;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commandes', name: 'orders_')]
class OrdersController extends AbstractController
{
    #[Route('/{id}', name: 'index')]
    public function index(Users $user): Response
    {
        return $this->render('pages/orders/index.html.twig', compact('user'));
    }

    #[Route('/ajout/{id}', name: 'add')]
    public function add(
        SessionInterface $session,
        ProductsRepository $productsRepository,
        EntityManagerInterface $em,
        Users $user
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
            $detail = new Details();

            $product = $productsRepository->find($item);
            
            $price = $product->getPrice();

            $detail->setProducts($product)
                ->setTotal($price)
                ->setQuantity($quantity);

            $order->addDetail($detail)
                ->setStatus('');
        }

        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');

        return $this->redirectToRoute('main');
    }
}
