<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


/**
 * Description of CategorieRepositoryTest
 *
 *
 */
class CategorieRepositoryTest extends KernelTestCase{
    
    /**
     * Réécupère le repository des catégories
     * @return CategorieRepository
     */
    public function recupRepository(): CategorieRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Test le nombre d'enregistrement des catégories
     */
     public function testNbCategories() {
       
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(6, $nbCategories);
    }
   
    /*
     * Création d'une instance de Categorie
     * @return Catégorie
     */
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                ->setName('Nom catégorie pour le test');
        return $categorie;
    }
    
    /*
     * Teste l'ajout d'une Categorie
     */

    public function testAddFormation(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /*
     * Teste la suppression d'une Formation
     */
    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression");
    }

    /**
     * Teste la méthode findAllForOnePlaylist la liste des categories d'une playlist
     */
     public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllForOnePlaylist(1);
        $nbCategories = count($categories);
        $this->assertEquals(2,$nbCategories);
        $this->assertEquals("Java",$categories[0]->getName());
     }
}