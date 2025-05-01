<?php


namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 */
class FormationsValidationsTest extends KernelTestCase {
    
    /*
     * Recupere une formation
     */
    public function getFormation() : Formation
    {
        return(new Formation ())
                ->setTitle('Formation pour le test')
                ->setPublishedAt(new DateTime("2022/12/11"));
    }
    
    /*
     * Teste une date valide
     */
    public function testValidDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2022/12/11"));
        $this->assertErrors($formation,0);
    }
    
   
    
    /*
     *  Récupération d'erreur
     */
    
     public function assertErrors(Formation $formation, int $nbErreursAttendues,string $message=""){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues,$error,$message);
    }
}