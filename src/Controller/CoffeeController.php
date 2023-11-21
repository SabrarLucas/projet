<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Categories;
use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cafes', name: 'coffee_')]
class CoffeeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        CategoriesRepository $categoriesRepository
        ): Response
    {
        return $this->render('coffee/index.html.twig', [
            'categories' => $categoriesRepository->findBy(['parent' => 1])
        ]);
    }

    #[Route('/{name}', name: 'list')]
    public function list(
        Categories $category, 
        ProductsRepository $productsRepository,
        Request $request
        ): Response
    {
        $page = $request->query->getInt('page', 1);

        $products = $productsRepository->findProductPaginated($page, $category->getName(), 3);

        return $this->render('coffee/list.html.twig', compact('category', 'products'));
    }
}
