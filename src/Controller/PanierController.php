<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Session $session, ProduitRepository $produitRepository): Response
    {
        //   $session->set('panier',[]);
        $table = [];
        $total = 0;
        $data = $session->get('panier', []);
        foreach ($data as $id => $qte){

            $table[]=[
                'prod'=> $produitRepository->find($id),
                'qte'=>$qte,
                'montant'=>$qte*$produitRepository->find($id)->getPrix()
            ];

            $total+=$produitRepository->find($id)->getPrix()*$qte;
        }
        return $this->render('panier/index.html.twig', [
            'panier'=>$table,
            'total'=>$total
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add_one')]
    public function addOne($id, Session $session): Response
    {


        $panier = $session->get('panier', []);
        if (!array_key_exists($id, $panier)){
            $panier[$id]=1;
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/update', name: 'app_panier_add')]
    public function add(Request $request, Session $session): Response
    {

        $data = $request->request;
        //dd($data);
        $panier = $session->get('panier', []);

        foreach ($data as $id=>$qte){
            $panier = $this->checker($panier, $id, $qte);
        }
        //dd($panier);
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier');
    }

        public function checker($table, $id, $qte)
    {
        $qte = intval($qte);

        if (!array_key_exists($id, $table) and $qte!=0){
            $table[$id]=$qte;
        } else {

            if ($qte==0){
                unset($table[$id]);
            }else{
                $table[$id]=$qte;
            }
        }

        return $table;
    }
}

