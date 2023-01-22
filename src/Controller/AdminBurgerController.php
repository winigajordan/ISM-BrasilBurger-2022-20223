<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBurgerController extends AbstractController
{
    #[Route('/admin/burger', name: 'app_admin_burger')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        return $this->render('admin_burger/index.html.twig', [
            'burgers'=>$burgerRepository->findAll()
        ]);
    }

    #[Route('/admin/burger/add', name:'burger_add', methods: 'POST')]
    public function addBurger(Request $request, EntityManagerInterface $manager){
        dd($request);
        $data = $request->request;
        $burger = new Burger();
        $burger->setLibelle($data->get('libelle'));
        $burger->setPrix($data->get('prix'));

        $img = $request->files->get('image');
        $imageName = uniqid().'.'.$img->guessExtension();
        $img->move( $this->getParameter('produits'), $imageName );
        $burger->setImage($imageName);

        $manager->persist($burger);
        $manager->flush();

        return $this->redirectToRoute('app_admin_burger');

    }

    #[Route('/admin/burger/update/{id}', name:'burger_update')]
    public function updateBurger($id, BurgerRepository $burgerRepository){
        return $this->render('admin_burger/index.html.twig', [
            'burgers'=>$burgerRepository->findAll(),
            'update'=>$burgerRepository->find($id)
        ]);
    }

    #[Route('/admin/burger/save', name:'burger_save')]
    public function burgerSave(Request $request, EntityManagerInterface $manager, BurgerRepository $burgerRepository){
        $data=$request->request;
        $burger = $burgerRepository->find($data->get('id'));
        $burger->setPrix($data->get('prix'));
        $burger->setLibelle($data->get('libelle'));
        $manager->persist($burger);
        $manager->flush();
        return $this->redirectToRoute('app_admin_burger');
    }




}
