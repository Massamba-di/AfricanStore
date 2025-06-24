<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
    #[Route('/products/ajouter', name: 'app_products_add')]
public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $products = new Products();
    $form = $this->createForm(ProductsForm::class, $products);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('pictures')->getData();
        
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            $imageFile->move(
                $this->getParameter('uploads_directory'),
                $newFilename
            );

            $products->setPictures($newFilename);
        }

     
        $entityManager->persist($products);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_products_add');
    }

    
    return $this->render('products/add.html.twig', [
        'form' => $form,
    ]);
}
#[Route('/products/{id}/edit', name: 'app_products_edit')]

public function editProducts(Products $products, Request $request, EntityManagerInterface $entityManager): Response
{
  
    

    // Créer un formulaire pré-rempli avec les données de l'article
    $form = $this->createForm(ProductsForm::class, $products);
    $form->handleRequest($request);

    // Si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush(); // Pas besoin de persist(), car $produits est déjà géré
        return $this->redirectToRoute('app_products'); // Redirige vers la page des produits
    }

    // Afficher le formulaire dans la vue
    return $this->render('products/editProducts.html.twig', [
        'form' => $form, //  
        
    ]);
}
  #[Route('/products/{id}/delete', name: 'app_products_delete')]
    public function deleteProducts(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        #recuperer le produit par ID
        $products = $entityManager->getRepository(Products::class)->find($id);
        #on verifie si produit existe
        if (!$products) {
            throw $this->createNotFoundException('produit non trouve');
        }
        #on securise la suppression
        $entityManager->remove($products);
        $entityManager->flush();
        $this->addFlash('succes', 'produit a bien ete supprime !');
        #on redirige vers la liste des produits
        return $this->redirectToRoute('app_products');
    }

}
