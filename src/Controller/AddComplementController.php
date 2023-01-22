<?php

namespace App\Controller;

use App\Repository\ComplementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddComplementController extends AbstractController
{
    #[Route('/add/complement/{id}', name: 'app_add_complement')]
    public function index(ComplementRepository $complementRepository, $id): Response
    {
        return $this->render('add_complement/index.html.twig', [
            'complements' => $complementRepository->findAll(),
            'id'=>$id
        ]);
    }
}
