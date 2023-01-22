<?php

namespace App\Controller;

use App\Entity\Boisson;
use App\Entity\Fritte;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminComplementController extends AbstractController
{
    #[Route('/admin/complement', name: 'app_admin_complement')]
    public function index(ComplementRepository $complementRepository): Response
    {
        return $this->render('admin_complement/index.html.twig', [
            'comps' => $complementRepository->findAll(),
        ]);
    }

    #[Route('/admin/complement/add', name:'complement_add', methods: 'POST')]
    public function addComplement(Request $request, EntityManagerInterface $manager){
        //dd($request);
        $data = $request->request;
        $complement = null;

        if($data->get('type')==2){
            $complement = new Fritte();
        } else if ($data->get('type')==1) {
            $complement = new Boisson();
        }

        $complement->setLibelle($data->get('libelle'));
        $complement->setPrix($data->get('prix'));

        $img = $request->files->get('image');
        $imageName = uniqid().'.'.$img->guessExtension();
        $img->move( $this->getParameter('produits'),$imageName );
        $complement->setImage($imageName);

        $manager->persist($complement);
        $manager->flush();

        return $this->redirectToRoute('app_admin_complement');

    }

    #[Route('/admin/complement/update/{id}', name:'complement_update')]
    public function updateComplement($id, ComplementRepository $complementRepository){
        return $this->render('admin_complement/index.html.twig', [
            'comps'=>$complementRepository->findAll(),
            'update'=>$complementRepository->find($id)
        ]);
    }

    #[Route('/admin/complement/save', name:'complement_save')]
    public function complementrSave(Request $request, EntityManagerInterface $manager, ComplementRepository $complementRepository){
        $data=$request->request;
        $complement = $complementRepository->find($data->get('id'));
        $complement->setPrix($data->get('prix'));
        $complement->setLibelle($data->get('libelle'));
        $manager->persist($complement);
        $manager->flush();
        return $this->redirectToRoute('app_admin_complement');
    }

}
