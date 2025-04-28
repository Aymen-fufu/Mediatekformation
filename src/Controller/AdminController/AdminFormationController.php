<?php

namespace App\Controller\AdminController;

use App\Form\FormationType;
use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controleur des formations
 *
 * @author Aymen-fufu
 */


class AdminFormationController extends AbstractController
{
    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */

    private const RETOURNEPFORMATION = "admin/admin.formations.html.twig";
    private const RETOURNEADMINFORMATION = "admin.formations";
    private $categorieRepository;

    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/formations", name="admin.formations")
     * @return Response
     */
    
     #[Route('/admin/formations', name: 'admin.formations')]
    public function index() : Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/formations/ajouter", name="admin_formations_ajouter")
     * @return Response
     */
    #[Route('/admin/formations/sort', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @return Response
     */
    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * @Route("/admin/formations/formation/{id}", name="admin.formations.add")
     * @return Response
     * @param Request $request
     */
    #[Route('/admin/formations/formation/add', name: 'admin.formations.add')]
    public function add(Request $request): Response
    {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);

        if ($formFormation->isSubmitted() && $formFormation->isValid())
        {
            $this-> formationRepository->add($formation);
            return $this->redirectToRoute(self::RETOURNEADMININFORMATION);
        }
        return $this->render("admin/admin.formations.html.twig", [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()]);
    }

    /**
     * @Route("/admin/formations/formation/modifier", name="admin.formations.edit")
     * @return Response
     * @param Request $request 
     * @param int $id
     */
    #[Route('/admin/formations/formation/edit', name: 'admin.formations.edit')]
    public function edit(Request $request, Formation $formation): Response 
    {
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);

        if ($formFormation->isSubmitted() && $formFormation->isValid()) 
        {
            $this->formationRepository->modify($formation,true);
            return $this->redirectToRoute(self::RETOURNEADMININFORMATION);
        }
        return $this->render("admin/admin.formations.html.twig", [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()]);
    }

    /**
     * @Route("/admin/formations/formation/{id}/supprimer", name="admin.formations.delete")
     * @return Response
     * @param Request $request 
     * @param int $id
     */
    #[Route('/admin/formations/formation/{id}/delete', name: 'admin.formations.delete')]
    public function delete(Request $request, Formation $formation): Response
    {
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute(self::RETOURNEADMININFORMATION);
    }


    




}