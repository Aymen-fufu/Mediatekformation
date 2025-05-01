<?php


namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



/**
 * Description of FormationRepositoryTest
 *
 */
class FormationRepositoryTest extends KernelTestCase{
    
    /**
     * Récupère le repository de Formation
     * @return FormationRepository
     */
    public function recupRepository(): FormationRepository {
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }

    /**
     * Test le nombre d'enregistrement des formations
     */
     public function testNbFormation() {
       
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }
   
    /*
     * Création d'une instance de Formation
     * @return Formation
     */
    public function newFormation(): Formation{
        $formation = (new Formation())
                ->setTitle('Formation pour le test')
                ->setPublishedAt(new DateTime("11/12/2022"))
                ->setDescription("Description test du test d'intégration");
        return $formation;
    }
    
    /*
     * Teste l'ajout d'une Formation
     */

    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newformation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /*
     * Teste la suppression d'une Formation
     */
    public function testRemove(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }

    /**
     * Teste la méthode findAllOrderBy triée sur un champ
     */
    public function testFindAllOrderby(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderBy("id", "ASC");
        $nbFormations = count($formations);
        $this->assertEquals(238, $nbFormations);
        $this->assertEquals("Eclipse n°8 : Déploiement", $formations[0]->getTitle());
    }
    
    /**
     * Teste la méthode findAllOrderByT triée sur un champ se trouvant dans une autre table
     */
    public function testFindAllOrderbyT(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderByT("name", "ASC","playlist");
        $nbFormations = count($formations);
        $this->assertEquals(237, $nbFormations);
        $this->assertEquals("Bases de la programmation n°74 - POO : collections", $formations[0]->getTitle());
    }
    
    /**
     * Teste la méthode findByContainValue dont un champ contient une valeur
     */
     public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "Eclipse n°7 : Tests unitaires");
        $nbFormations = count($formations);
        $this->assertEquals(1,$nbFormations);
        $this->assertEquals("Eclipse n°7 : Tests unitaires", $formations[0]->getTitle());
         
     }
    
    /**
     * Teste la méthode findByContainValueT dont un champ qui se trouve dans une autre table contient une valeur
    */
     public function testFindByContainValueT(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValueT("name", "Java","categories");
        $nbFormations = count($formations);
        $this->assertEquals(15,$nbFormations,"erreur du 1er assert");
        $this->assertEquals("Eclipse n°8 : Déploiement", $formations[0]->getTitle(),"erreur du 2eme assert");
     }
         
    
     
     /**
     * Teste la méthode findAllLasted une formation la plus récente
     */
     public function testFindAllLasted(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1,$nbFormations);
        $this->assertEquals(new DateTime("11/12/2022"),$formations[0]->getPublishedAt());
     }
    
     /**
     * Teste la méthode findAllForOnePlaylist la liste des formations d'une playlist
     */
     public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllForOnePlaylist(1);
        $nbFormations = count($formations);
        $this->assertEquals(8,$nbFormations);
        $this->assertEquals("Eclipse n°1 : installation de l'IDE",$formations[0]->getTitle());
     }
     
    
}