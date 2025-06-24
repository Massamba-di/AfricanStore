<?php

namespace App\Controller;

use App\Form\ResetpasswordForm;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

     #[Route(path: '/reinitialisation', name:'app_reset_password')]
     public  function  resetPassword(Request $request,UsersRepository $usersRepository): Response
     {
         $form = $this->createForm(ResetpasswordForm::class);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             $user = $usersRepository->findOneBy(['email' => $form->get('email')->getData()]);
             if ($user) {

             }
             
         }


         return $this->render('login/reset_password.html.twig', [
             'ResetpasswordForm' => $form->createView(),


         ]);

     }










}
