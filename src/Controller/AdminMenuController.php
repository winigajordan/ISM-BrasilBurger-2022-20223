<?php

namespace App\Controller;

use App\Entity\ComplementMenu;
use App\Entity\Menu;
use App\Repository\BoissonRepository;
use App\Repository\BurgerRepository;
use App\Repository\ComplementMenuRepository;
use App\Repository\FritteRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMenuController extends AbstractController
{
    #[Route('/admin/menu', name: 'app_admin_menu')]
    public function index(MenuRepository $menuRepository, BurgerRepository $burgerRepository, FritteRepository $fritteRepository, BoissonRepository $boissonRepository): Response
    {

        return $this->render('admin_menu/index.html.twig', [
            'boissons'=>$boissonRepository->findAll(),
            'frittes'=>$fritteRepository->findAll(),
            'burgers'=>$burgerRepository->findAll(),
            'menus'=>$menuRepository->findAll(),
        ]);
    }

    #[Route('/admin/menu/add', name: 'add_menu')]
    public function addMenu(Request $request,
                            BurgerRepository $burgerRepository,
                            FritteRepository $fritteRepository,
                            BoissonRepository $boissonRepository,
                            EntityManagerInterface $manager,
    ): Response
    {
        $data=$request->request;
        $menu = new Menu();
        $menu->setLibelle($data->get('libelle'));

        $img = $request->files->get('image');
        $imageName = uniqid().'.'.$img->guessExtension();
        $img->move( $this->getParameter('produits'),$imageName );
        $menu->setImage($imageName);

        $fritte = $fritteRepository->find($data->get('fritte'));
        $boisson = $boissonRepository->find($data->get('boisson'));
        $burger = $burgerRepository->find($data->get('burger'));

        $menu->setBurger($burger);
        $menu->setPrix($fritte->getPrix() + $boisson->getPrix() + $burger->getPrix());

        $menu->setEtat(true);
        $manager->persist($menu);

        $mc1 = new ComplementMenu();
        $mc1->setMenu($menu);
        $mc1->setComplement($boisson);

        $mc2 = new ComplementMenu();
        $mc2->setMenu($menu);
        $mc2->setComplement($fritte);

        $manager->persist($mc1);
        $manager->persist($mc2);

        $manager->flush();
        return $this->redirectToRoute('app_admin_menu');
    }

    #[Route('/admin/menu/update/{id}', name: 'menu_update')]
    public function update(
        $id,
        MenuRepository $menuRepository,
        BurgerRepository $burgerRepository,
        FritteRepository $fritteRepository,
        BoissonRepository $boissonRepository
    ): Response
    {

        return $this->render('admin_menu/index.html.twig', [
            'update'=>$menuRepository->find($id),
            'boissons'=>$boissonRepository->findAll(),
            'frittes'=>$fritteRepository->findAll(),
            'burgers'=>$burgerRepository->findAll(),
            'menus'=>$menuRepository->findAll(),
        ]);
    }

    #[Route('/admin/menu/update/etat/{id}', name: 'menu_visibility')]
    public function updateEtat(MenuRepository $menuRepository, EntityManagerInterface $manager, $id): Response
    {
        $menu = $menuRepository->find($id);
        $menu->setEtat(!$menu->isEtat());
        $manager->persist($menu);
        $manager->flush();
        return $this->redirectToRoute('app_admin_menu');
    }


    #[Route('/admin/menu/save', name: 'app_admin_menu_save')]
    public function saveMenu(Request $request,
                             ComplementMenuRepository $complementMenuRepository,
                             MenuRepository $menuRepository,
                             BurgerRepository $burgerRepository,
                             FritteRepository $fritteRepository,
                             BoissonRepository $boissonRepository,
                             EntityManagerInterface $manager
    ): Response
    {
        $data = $request->request;

        $menu = $menuRepository->find($data->get('id'));
        $complements =$complementMenuRepository->findBy(['menu'=>$menu]);
        foreach ($complements as $complement){
            $manager->remove($complement);
        }

        $fritte = $fritteRepository->find($data->get('fritte'));
        $boisson = $boissonRepository->find($data->get('boisson'));
        $burger = $burgerRepository->find($data->get('burger'));

        $menu->setBurger($burger);

        $menu->setPrix($fritte->getPrix() + $boisson->getPrix() + $burger->getPrix());

        $manager->persist($menu);

        $mc1 = new ComplementMenu();
        $mc1->setMenu($menu);
        $mc1->setComplement($boisson);

        $mc2 = new ComplementMenu();
        $mc2->setMenu($menu);
        $mc2->setComplement($fritte);

        $manager->persist($mc1);
        $manager->persist($mc2);
        $manager->flush();

        return $this->redirectToRoute('app_admin_menu');
    }


}
