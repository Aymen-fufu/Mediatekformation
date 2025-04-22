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
     * @Route("/admin/categories", name="admin_categories")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admincategories.html.twig", [
            'categories' => $categories,
            
        ]);
    }

    /**
     * @Route("/admin/categories/add", name="admin_categories_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        $categorie = new Categorie();
        $name = $request->request->get('name');
        $categorie->setName($name);
        $this->categorieRepository->add($categorie, true);
        return $this->redirectToRoute('admin_categories');
    }

    
    public function delete (Categorie $categorie): Response
    {
        
        $this->categorieRepository->delete($categorie, true);
        return $this->redirectToRoute('admin_categories');
    }



}