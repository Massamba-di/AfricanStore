<?php

namespace App\Controller;

use App\Form\ContactForm;
use Doctrine\Tests\Models\TypedProperties\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_account')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $adresse = $user->getAdresses();
        $commands=$user->getCommands();
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->addFlash('success', 'Votre message a bien été envoyé.');


            return $this->redirectToRoute('app_profile');


        }
        return $this->render('account/index.html.twig', [
            'user' => $user,
            'adresse' => $adresse,
            'commands' => $commands,
            'form' => $form
        ]);
    }


    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
                ->from($data['email'])
                ->to('africanStore@admin.fr')
                ->subject($data['sujet'])
                ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}