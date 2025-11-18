<?php
namespace App\Controller;

use App\Form\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AccountController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_account')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $adresse = $user->getAdresses();
        $commands = $user->getCommands();

        // Créer le formulaire de contact
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $email = (new Email())
                ->from('noreply@votre-site.com')
                ->to('africanStore@admin.fr')
                ->replyTo($contact['email'])
                ->subject($contact['subject'])
                ->html($this->renderView('emails/contact.html.twig', [
                    'contact' => $contact
                ]));

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'adresse' => $adresse,
            'commands' => $commands,
            'form' => $form, // Remettre la variable form
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $email = (new Email())
                ->from('noreply@votre-site.com')
                ->to('africanStore@admin.fr')
                ->replyTo($contact['email'])
                ->subject($contact['subject'])
                ->html($this->renderView('emails/contact.html.twig', [
                    'contact' => $contact
                ]));

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}