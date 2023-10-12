<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository, Request $request)
    {
        $type = $request->query->get('type'); // Récupère le paramètre 'type' de la requête

        $types = $productRepository->findDistinctTypes(); // Récupère la liste des types de produits

        // Utilisez ce paramètre pour filtrer les produits par type
        $products = $type ? $productRepository->findByType($type) : $productRepository->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'types' => $types, // Passez la variable 'types' au modèle
        ]);
    }
}
