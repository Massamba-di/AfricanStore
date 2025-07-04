<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\CommandProducts;
use App\Entity\Commands;
use App\Entity\Products;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $adresses = $user->getAdresses();

        return $this->render('order/index.html.twig', [
            'adresses' => $adresses,
            'user' => $user,
        ]);
    }

    #[Route('/order/confirm', name: 'app_order_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $security->getUser();
        if (!$user) {
            // Si pas connecté, rediriger vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'ID de l'adresse depuis le formulaire
        $adresseId = $request->request->get('adresse_id');
        if (!$adresseId) {
            return new Response("ID d'adresse manquant", 400);
        }

        // Trouver l'adresse en base avec cet ID
        $adresse = $entityManager->getRepository(Adresse::class)->find($adresseId);
        if (!$adresse) {
            return new Response("Adresse introuvable", 400);
        }

        // Récupérer le panier envoyé en JSON depuis le formulaire
        $panierJson = $request->request->get('panier');
        if (!$panierJson) {
            return new Response("Panier vide ou manquant", 400);
        }

        // Décoder le JSON en tableau PHP
        $panier = json_decode($panierJson, true);
        if (!is_array($panier) || empty($panier)) {
            return new Response("Panier invalide ou vide", 400);
        }

        // Créer une nouvelle commande
        $command = new Commands();
        $command->setUsers($user);
        $command->setAdresse($adresse);
        $command->setStatus('en attente');
        $command->setOrderDate(new \DateTime());

        $totalCommand = 0;
        $productRepo = $entityManager->getRepository(Products::class);

        // Pour chaque produit dans le panier
        foreach ($panier as $item) {
            // Vérifier que les données sont complètes
            if (!isset($item['id'], $item['prix'], $item['quantity'])) {
                return new Response("Produit incomplet dans le panier", 400);
            }

            $productId = (int)$item['id'];
            $prix = (float)$item['prix'];
            $quantity = (int)$item['quantity'];

            // Vérifier les valeurs valides
            if ($productId <= 0 || $prix <= 0 || $quantity <= 0) {
                return new Response("Produit avec données invalides", 400);
            }

            // Trouver le produit en base
            $product = $productRepo->find($productId);
            if (!$product) {
                return new Response("Produit ID $productId introuvable", 400);
            }

            // Calculer le total pour ce produit
            $totalProduit = round($prix * $quantity, 2);
            $totalCommand += $totalProduit;

            // Créer la ligne de commande (détail produit)
            $detailCommand = new CommandProducts();
            $detailCommand->setProducts($product);
            $detailCommand->setCommands($command);
            $detailCommand->setQuantity($quantity);
            $detailCommand->setTotalPrice($totalProduit);

            // Lier le détail à la commande
            $command->addCommandProduct($detailCommand);

            // Dire à Doctrine de sauvegarder ce détail
            $entityManager->persist($detailCommand);
        }

        // Mettre à jour le total de la commande
        $command->setTotalPrice(round($totalCommand, 2));

        // Sauvegarder la commande et ses détails en base
        $entityManager->persist($command);
        $entityManager->flush();

        // Rediriger vers la page de paiement avec l'ID de la commande
        return $this->redirectToRoute('app_payment_checkout', [
            'id' => $command->getId()

        ]);
    }

}

