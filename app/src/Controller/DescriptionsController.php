<?php

namespace App\Controller;

use App\Entity\Reviews;
use App\Form\ReviewsForm;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DescriptionsController extends AbstractController
{
    #[Route('/descriptions/{id}', name: 'app_descriptions')]
    public function description(int $id,Request $request, ProductsRepository $productsRepository, EntityManagerInterface $entityManager): Response
    {
        $product = $productsRepository->find($id);
        $review = new Reviews();
        $form = $this->createForm(ReviewsForm::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setProducts($product);


            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Avis ajouté avec succès');
            return $this->redirectToRoute('app_descriptions', ['id' => $product->getId()]);
        }




        return $this->render('descriptions/index.html.twig', [
            'product' => $product,
            'reviews' => $review,
            'form' => $form
        ]);
    }
}
