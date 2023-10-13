<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function index(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $panier = $session->get('panier', []);

        // panier vide redirection
        if(($panier) === []){
            $this->addFlash('message', "Votre panier est vide");
            return $this->redirectToRoute('app_home');
        }

        // panier n'est pas vide, passe la commande
        $order = new Orders();

        // on remplit la commande
        $order->setUser($this->getUser());
        // crée la référence pour un exemple (non recommandé)
        $order->setReference(uniqid());

        // on parcourt le panier pour créer les détails de commande
        foreach($panier as $item => $quantity) {
            $orderDetails = new OrdersDetails();

            // recherche le produit
            $product = $productRepository->find($item);

            $price = $product->getPrix();

            // crée le détail de la commande
            $orderDetails->setProduct($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }

        // persiste et flush
        $em->persist($order);
        $em->flush($order);

        $session->remove('panier');

        $this->addFlash('message', "Commande crée");
        return $this->redirectToRoute('app_home');
    }
}
