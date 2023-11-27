<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $produits = $productsRepository->findAll();

        return $this->render('admin/products/index.html.twig', compact('produits'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request, 
        EntityManagerInterface $em,
        PictureService $pictureService
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Products();

        $productForm = $this->createForm(ProductsFormType::class, $product);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){
            $image = $productForm->get('image')->getData();

            $folder = 'products';

            $fichier = $pictureService->add($image, $folder, 300, 300);

            $product->setImage($fichier);

            $prix = $product->getPrice();
            $product->setPrice($prix);

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Products $product,
        Request $request, 
        EntityManagerInterface $em
        ): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);

        $productForm = $this->createForm(ProductsFormType::class, $product);

        $productForm->handleRequest($request);

        if($productForm->isSubmitted() && $productForm->isValid()){
            $prix = $product->getPrice();
            $product->setPrice($prix);

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès');

            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Products $product): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        
        return $this->render('admin/products/index.html.twig');
    }
}
