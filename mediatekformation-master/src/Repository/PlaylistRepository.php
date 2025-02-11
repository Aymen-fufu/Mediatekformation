<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Config\TwigExtra\StringConfig;

/**
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param string $champ
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName(string $ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->groupBy('p.id')
                ->orderBy('p.name', $ordre)
                ->getQuery()
                ->getResult();
    }
	
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param string $champ
     * @param mixed $valeur
     * @param string $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue(string $champ, mixed $valeur, string $table=""): array{
        if($valeur===""){
            return $this->findAllOrderByName('ASC');
        }
        
        // Convert value to string for LIKE query
        $searchValue = (string) $valeur;
        
        if($table==""){
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$searchValue.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('p')
                    ->leftjoin('p.formations', 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$searchValue.'%')
                    ->groupBy('p.id')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult();
        }
    }

    /**
     * Recupere le nombre de formation par playlist et retourne toutes les playlists triées sur le nombre  de formation
     * @param string $ordre
     * @return Playlist[]
     */
    public function findAllOrderByFormationCount(string $ordre): array {
        return $this->createQueryBuilder('p')
            ->select('p as playlist', 'COUNT(f.id) as formationCount')
            ->leftjoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('formationCount', $ordre)
            ->getQuery()
            ->getResult();
    }

    
}
