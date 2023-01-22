<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Produit;
use App\Repository\ComplementRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitDetailsController extends AbstractController
{
    #[Route('/produit/details/{id}', name: 'app_produit_details')]
    public function index(ComplementRepository $complementRepository, ProduitRepository $produitRepository, $id): Response
    {
        $prod = $produitRepository->find($id);
        if ($prod instanceof Menu) {
            return $this->render('produit_details/index.html.twig', [
                'prod' => $prod,
                'menu'=> 1
            ]);
        }
        return $this->render('produit_details/index.html.twig', [
            'prod' => $prod,
            'complements' => $complementRepository->findAll()
        ]);
    }
}
