<?php

namespace App\Controller\AdminController;

use App\Entity\Formation;
use App\Form\PlaylistType;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des playlists
 *
 * @author Aymen-fufu
 */

class AdminPlaylistController extends AbstractController
{
    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * Summary of categorieRepository
     * @var CategorieRepository
     */
    private $categorieRepository;

    private const RETOURNEPPLAYLIST = "admin/admin.playlists.html.twig";
    private const RETOURNEADMINPLAYLIST = "admin.playlists";

    /**
     * Summary of __construct
     * @param PlaylistRepository $playlistRepository 
     * @param CategorieRepository $categorieRepository 
     */
    public function __construct( PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/playlists", name="admin_playlists")
     * @param Request $request 
     * @return Response
     */

    public function index(Request $request): Response
    {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/adminplaylists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/ajouter", name="admin_playlists_ajouter")
     * @param Request $request 
     * @return Response
     */
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "formationCount":
                $playlists = $this->playlistRepository->findAllOrderByFormationCount($ordre);
                break;
            default:
                error_log("Erreur dans le tri des playlists");
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/adminplaylists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin_playlists_findallcontain")
     * @param Request $request 
     * @return Response
     */

    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        if ($champ == "id") {
            $valeur = (int) $valeur;
        }else{
            $valeur = (int) $valeur;
        }
        $playlists = $this->playlistRepository->findAllContain($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/adminplaylists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * @Route("/admin/playlists/playlist/{id}", name="admin_playlists_add")
     * @param Request $request 
     * @return Response
     */

    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);

        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) 
        {
            $this->playlistRepository->save($playlist);
            return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
        }
        return $this->render("admin/adminplaylists.html.twig", [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * @Route("/admin/playlists/playlist/{id}/modifier", name="admin_playlists_edit")
     * @param Request $request 
     * @param int $id 
     * @return Response
     */
    public function edit(Request $request, Playlist $playlist): Response
    {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);

        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid())
        {
            $this->playlistRepository->modify($playlist,true);
            return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
        }
        return $this->render("admin/adminplaylists.html.twig", [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * @Route("/admin/playlists/playlist/{id}/supprimer", name="admin_playlists_delete")
     * @param Request $request 
     * @return Response
     */
    public function delete(Request $request, Playlist $playlist): Response
    {
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
    }
}