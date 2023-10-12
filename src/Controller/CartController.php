<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
        $panier = $session->get('panier', []);

        // Fabrique les données
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $product = $productRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite,
            ];
            $total += $product->getPrix() * $quantite;
        }

        return $this->render('cart/index.html.twig', compact('dataPanier', 'total'));
    }
    
    #[Route('/add/{id}', name: 'add_cart')]
    public function add($id, SessionInterface $session)
    {
        // Récupère le panier actuel
        $panier = $session->get("panier", []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // Sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/remove/{id}', name: 'remove_cart')]
    public function remove($id, SessionInterface $session)
    {
        // Récupère le panier actuel
        $panier = $session->get("panier", []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // Sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/delete/{id}', name: 'delete_cart')]
    public function delete($id, SessionInterface $session)
    {
        // Récupère le panier actuel
        $panier = $session->get("panier", []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // Sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }

    #[Route('/delete', name: 'delete_all_cart')]
    public function deleteAll(SessionInterface $session)
    {
        // Vide le panier
        $session->remove("panier");

        return $this->redirectToRoute("app_cart");
    }
}
