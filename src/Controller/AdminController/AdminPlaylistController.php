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
     * @Route("/admin/playlists", name="admin.playlists")
     * @param Request $request
     * @return Response
     */

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(Request $request): Response
    {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/sort", name="admin.playlists.sort")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlists/sort/{champ}/{ordre}', name: 'admin.playlists.sort')]
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
                $playlists = $this->playlistRepository->findAll();
                error_log("Erreur dans le tri des playlists");
                break;
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
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
        return $this->render("admin/admin.playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * @Route("/admin/playlist/add{", name="admin.playlists.add")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/add', name: 'admin.playlists.add')]
    public function add(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);

        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) 
        {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
        }
        $categories = $this->categorieRepository->findAll();
        $playlists = $this->playlistRepository->findAll();
        return $this->render("admin/admin.playlist.add.html.twig", [
            'playlist' => $playlist,
            'categories' => $categories,
            'playlists' => $playlists,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * @Route("/admin/playlist/edit/{id}", name="admin.playlists.edit")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    #[Route('/admin/playlist/edit/{id}', name: 'admin.playlists.edit')]
    public function edit(Request $request, Playlist $playlist): Response
    {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);

        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid())
        {
            $this->playlistRepository->modify($playlist,true);
            return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
        }
        return $this->render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView()
        ]);
    }

    /**
     * @Route("/admin/playlists/playlist/{id}/delete", name="admin.playlists.delete")
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/playlist/delete/{id}', name: 'admin.playlists.delete')]
    public function delete(Request $request, Playlist $playlist): Response
    {
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute(self::RETOURNEADMINPLAYLIST);
    }
}