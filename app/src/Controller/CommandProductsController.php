<?php

namespace App\Controller;

use App\Entity\CommandProducts;
use App\Form\CommandProductsForm;
use App\Repository\CommandProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/command/products')]
final class CommandProductsController extends AbstractController
{
    #[Route(name: 'app_command_products_index', methods: ['GET'])]
    public function index(CommandProductsRepository $commandProductsRepository): Response
    {
        return $this->render('command_products/index.html.twig', [
            'command_products' => $commandProductsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_command_products_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandProduct = new CommandProducts();
        $form = $this->createForm(CommandProductsForm::class, $commandProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandProduct);
            $entityManager->flush();

            return $this->redirectToRoute('app_command_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('command_products/new.html.twig', [
            'command_product' => $commandProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_products_show', methods: ['GET'])]
    public function show(CommandProducts $commandProduct): Response
    {
        return $this->render('command_products/show.html.twig', [
            'command_product' => $commandProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_command_products_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandProducts $commandProduct, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandProductsForm::class, $commandProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_command_products_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('command_products/edit.html.twig', [
            'command_product' => $commandProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_products_delete', methods: ['POST'])]
    public function delete(Request $request, CommandProducts $commandProduct, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandProduct->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commandProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_command_products_index', [], Response::HTTP_SEE_OTHER);
    }
}
