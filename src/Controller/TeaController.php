<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/thes', name: 'tea_')]
class TeaController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        CategoriesRepository $categoriesRepository
        ): Response
    {
        return $this->render('pages/coffee/index.html.twig', [
            'categories' => $categoriesRepository->findBy(['parent' => 2])
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

        return $this->render('pages/coffee/list.html.twig', compact('category', 'products'));
    }
}