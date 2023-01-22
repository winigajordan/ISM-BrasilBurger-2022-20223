<?php

namespace App\Controller;

use App\Repository\BurgerRepository;
use App\Repository\ComplementRepository;
use App\Repository\MenuRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index( MenuRepository $menuRepository, BurgerRepository $burgerRepository, ComplementRepository $complementRepository): Response
    {
        if (!empty($_GET)){
            if ($_GET['filter']=="menus")
            {
                return $this->render('home/index.html.twig', [
                    'products' =>$menuRepository->findAll(),
                ]);
            } elseif ($_GET['filter']=="burgers"){
                return $this->render('home/index.html.twig', [
                    'products' =>$burgerRepository->findAll(),
                    'complements'=>$complementRepository->findAll()
                ]);
            } else {
                return $this->render('home/index.html.twig', [
                    'products' =>[],
                ]);
            }
        }
        return $this->render('home/index.html.twig', [
            'products' =>$menuRepository->findAll(),
        ]);
    }
/*
    #[Route('/', name: 'app_home_filter')]
    public function filter( Request $request, ProduitRepository $produitRepository, MenuRepository $menuRepository): Response
    {
        $data = $request->query;
        if ($data->get('filter')=="menus")
        {
            return $this->render('home/index.html.twig', [
                'products' =>$menuRepository->findAll(),
            ]);
        } elseif ($data->get('filter')=="burgers"){
            return $this->render('home/index.html.twig', [
                'products' =>$produitRepository->findAll(),
            ]);
        } else {
            return $this->render('home/index.html.twig', [
                'products' =>[],
            ]);
        }

    }
*/
}
