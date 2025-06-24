<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DescriptionsController extends AbstractController
{
    #[Route('/descriptions', name: 'app_descriptions')]
    public function index(): Response
  
    {
        
        return $this->render('descriptions/index.html.twig', [
            'controller_name' => 'DescriptionsController',
        ]);
    }
}
