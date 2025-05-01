<?php
namespace App\Controller\AdminController;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author emds
 */
class AdminController extends AbstractController{
    
    /**
     * @var FormationRepository
     */
    private FormationRepository $repository;
    
    /**
     *
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/admin", name="admin")
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response{
        $formations = $this->repository->findAllLasted(2);
        return $this->render("admin/admin.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * @Route("/cgu", name="cgu")
     * @return Response
     */
    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response{
        return $this->render("pages/cgu.html.twig");
    }
}
