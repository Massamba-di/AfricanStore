<?php

namespace App\Controller;

use App\Entity\Commands;
use App\Form\CommandsForm;
use App\Repository\CommandsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commands')]
final class CommandsController extends AbstractController
{
    #[Route(name: 'app_commands_index', methods: ['GET'])]
    public function index(CommandsRepository $commandsRepository): Response
    {
        return $this->render('commands/index.html.twig', [
            'commands' => $commandsRepository->findAll(),
        ]);
    }

    #[Route('/paiement', name: 'app_commands_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $command = new Commands();
        $form = $this->createForm(CommandsForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('app_commands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commands/new.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commands_show', methods: ['GET'])]
    public function show(Commands $command): Response
    {
        return $this->render('commands/show.html.twig', [
            'command' => $command,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commands_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commands $command, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandsForm::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commands_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commands/edit.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commands_delete', methods: ['POST'])]
    public function delete(Request $request, Commands $command, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$command->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commands_index', [], Response::HTTP_SEE_OTHER);
    }
}
