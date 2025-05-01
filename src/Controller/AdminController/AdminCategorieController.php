<?php

namespace App\Controller\AdminController;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * Controleur des categories
 *
 * @author Aymen-fufu
 */


class AdminCategorieController extends AbstractController
{

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var FormationRepository
     */
    private $playlistRepository;

    /**
     * Summary of __construct
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $playlistRepository
     */
    public function __construct(CategorieRepository $categorieRepository, FormationRepository $playlistRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    /**
     * @Route("/admin/categories", name="admin.categories")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories,
            
        ]);
    }

    /**
     * @Route("/admin/categories/add", name="admin.categories.add")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/categories/add', name: 'admin.categories.add')]
    public function add(Request $request): Response
    {
        $categorie = new Categorie();
        $name = $request->request->get('name');
        $categorie->setName($name);
        $this->categorieRepository->add($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin.categories.delete")
     * @param Request $request
     * @param Categorie $categorie
     * @return Response
     */
    #[Route('/admin/categories/delete/{id}', name: 'admin.categories.delete')]
    public function delete(Categorie $categorie): Response
    {
        
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
    }



}