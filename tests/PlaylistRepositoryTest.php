<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    /**
     * Récupère le repository de Playlist
     */
    public function recupRepository(): PlaylistRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Test le nombre d'enregistrements
     */
    public function testNbPlaylists() {
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(27, $nbPlaylists);
    }

    /**
     * Crée une nouvelle Playlist
     */
    public function newPlaylist(): Playlist {
        $playlist = (new Playlist())
                ->setName('Playlist de test')
                ->setDescription('Description de test');
        return $playlist;
    }

    /**
     * Teste findAllOrderByName
     */
    public function testFindAllOrderbyName(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByName("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
    }

    /**
     * Teste findAllOrderByFormationCount
     */
    public function testFindAllOrderByFormationCount(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByFormationCount("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(28, $nbPlaylists);
    }

    /**
     * Teste findByContainValue
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlists = $repository->findByContainValue("name", "Eclipse");
        $nbPlaylists = count($playlists);
        $this->assertEquals(3, $nbPlaylists);
    }
}