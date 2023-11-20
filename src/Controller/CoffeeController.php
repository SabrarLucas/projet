<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cafes', name: 'coffee_')]
class CoffeeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        
        return $this->render('coffee/index.html.twig', [
            'categories' => $categoriesRepository->findBy(['parent' => 1])
        ]);
    }

    #[Route('/{name}', name: 'list')]
    public function list(Categories $category): Response
    {
        $products = $category->getProduct();

        return $this->render('coffee/list.html.twig', compact('category', 'products'));
    }
}
