<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use App\Entity\Categories;
use App\Form\ProductsFormType;
use App\Service\PictureService;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/categories', name: 'admin_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $categories = $categoriesRepository->findby([]);

        return $this->render('admin/categories/index.html.twig', compact('categories'));
    }

    #[Route('/ajout', name: 'add')]
    public function add(
        Request $request, 
        EntityManagerInterface $em,
        PictureService $pictureService
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categorie = new Categories();

        $categorieForm = $this->createForm(CategoriesFormType::class, $categorie);

        $categorieForm->handleRequest($request);

        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
            $image = $categorieForm->get('image')->getData();

            $folder = 'categories';

            $fichier = $pictureService->add($image, $folder, 300, 300);

            $categorie->setImage($fichier);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès');

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/add.html.twig', [
            'categorieForm' => $categorieForm->createView()
        ]);
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Categories $categorie,
        Request $request, 
        EntityManagerInterface $em
        ): Response
    {
        $this->denyAccessUnlessGranted('CATEGORIE_EDIT', $categorie);

        $categorieForm = $this->createForm(categoriesFormType::class, $categorie);

        $categorieForm->handleRequest($request);

        if($categorieForm->isSubmitted() && $categorieForm->isValid()){
            

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Produit modifié avec succès');

            return $this->redirectToRoute('admin_categories_index');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'categorieForm' => $categorieForm->createView()
        ]);
    }

    #[Route('/suppression/{id}', name: 'delete')]
    public function delete(Categories $categorie): Response
    {
        $this->denyAccessUnlessGranted('CATEGORIE_DELETE', $categorie);
        
        return $this->render('admin/categories/index.html.twig');
    }
}