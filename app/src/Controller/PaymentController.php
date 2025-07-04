<?php
namespace App\Controller;

use App\Entity\Commands;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class PaymentController extends AbstractController
{
    #[Route('/payment/{id}', name: 'app_payment_checkout')]
    public function index(int $id, EntityManagerInterface $entityManager, ParameterBagInterface $params): Response
    {
        try {
            // Configure la clé secrète Stripe
            $stripeSecretKey = $params->get('stripe_secret_key');
            if (!$stripeSecretKey) {
                throw new \Exception('Clé Stripe non configurée');
            }

            Stripe::setApiKey($stripeSecretKey);

            // Récupère la commande depuis la base
            $command = $entityManager->getRepository(Commands::class)->find($id);
            if (!$command) {
                $this->addFlash('error', 'Commande non trouvée');
                return $this->redirectToRoute('app_main'); // Adaptez selon votre route
            }

            // Vérification que la commande a des produits
            if ($command->getCommandProducts()->isEmpty()) {
                $this->addFlash('error', 'La commande ne contient aucun produit');
                return $this->redirectToRoute('app_main');
            }

            // Prépare les produits pour Stripe
            $lineItems = [];
            foreach ($command->getCommandProducts() as $commandProduct) {
                $product = $commandProduct->getProducts();
                $totalPrice = $commandProduct->getTotalPrice();
                $quantity = $commandProduct->getQuantity();

                // Vérification des données
                if (!$product || $totalPrice <= 0 || $quantity <= 0) {
                    $this->addFlash('error', 'Données de produit invalides');
                    return $this->redirectToRoute('app_main');
                }

                // Calcul du prix unitaire en centimes
                $unitPriceInCents = (int) round(($totalPrice / $quantity) * 100);

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $product->getName(),
                            'description' => $product->getDescription() ?? '', // Si vous avez une description
                        ],
                        'unit_amount' => $unitPriceInCents,
                    ],
                    'quantity' => $quantity,
                ];
            }

            // Crée la session Stripe Checkout
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', ['id' => $command->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', ['id' => $command->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'metadata' => [
                    'order_id' => $command->getId(),
                ],
                'customer_email' => $command->getUsers()->getEmail(), // Si disponible
            ]);



            // Redirige vers la page de paiement Stripe
            return $this->redirect($checkoutSession->url, 303);

        } catch (ApiErrorException $e) {
            // Erreur Stripe spécifique
            $this->addFlash('error', 'Erreur de paiement : ' . $e->getMessage());
            error_log('Erreur Stripe : ' . $e->getMessage());
            return $this->redirectToRoute('app_main');

        } catch (\Exception $e) {
            // Autres erreurs
            $this->addFlash('error', 'Une erreur est survenue lors du traitement de votre commande');
            error_log('Erreur PaymentController : ' . $e->getMessage());
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('/payment/success/{id}', name: 'payment_success')]
    public function success(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupère la commande pour afficher les détails
        $command = $entityManager->getRepository(Commands::class)->find($id);

        if ($command) {
            // Optionnel : Mettre à jour le statut de la commande
            $command->setStatus('payée');
            $entityManager->flush();
        }

        return $this->render('payment/success.html.twig', [
            'command' => $command
        ]);
    }

    #[Route('/payment/cancel/{id}', name: 'payment_cancel')]
    public function cancel(int $id, EntityManagerInterface $entityManager): Response
    {
        $command = $entityManager->getRepository(Commands::class)->find($id);

        return $this->render('payment/cancel.html.twig', [
            'command' => $command
        ]);
    }
}