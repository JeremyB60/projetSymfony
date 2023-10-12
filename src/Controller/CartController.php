<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


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
    
    #[Route('/add-quantity', name: 'add_quantity_cart')]
    public function addQuantity(Request $request, SessionInterface $session)
    {
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity');

        // Vérifie que la quantité est un nombre entier positif
        if (!ctype_digit($quantity) || $quantity <= 0) {
            // Gérer ici le cas où la quantité est invalide, par exemple rediriger vers une page d'erreur
        }

        // Récupère le panier actuel
        $panier = $session->get("panier", []);

        if (!empty($panier[$id])) {
            $panier[$id] += (int)$quantity;
        } else {
            $panier[$id] = (int)$quantity;
        }

        // Sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
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
