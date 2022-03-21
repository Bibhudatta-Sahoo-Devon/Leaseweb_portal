<?php

namespace App\Repository;

use App\Entity\Ram;
use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    /**
     * To store server data
     * @param $serverData
     * @param $ramData
     * @param $hddData
     * @param $locationData
     * @return bool
     * @throws Exception
     */
    public function storeServerDetails($serverData, $ramData, $hddData, $locationData): bool
    {
        try {

            $insertFlag = false;
            $allModels = array_unique(array_column($serverData, 'model'));
            $presentRecords = [];
            $records = $this->findBy(['model' => $allModels]);

            if (!empty($records)) {
                foreach ($records as $data) {//todo array key = model+ram+hdd+location+price
                    $key = implode('-', $data->getAllData());
                    $presentRecords[$key] = $data;
                }
            }
            foreach ($serverData as $data) {

                $data['price'] = preg_replace("[A-Za-z]", "", $data['price']);

                if (!array_key_exists(implode('-', $data), $presentRecords)) {
                    $server = new Server();
                    $server->setModel($data['model']);
                    $server->setRam($ramData[$data['ram']]);
                    $server->setHdd($hddData[$data['hdd']]);
                    $server->setLocation($locationData[$data['location']]);
                    $server->setPrice($data['price']);

                    $this->_em->persist($server);
                    $insertFlag = true;
                }

            }

            if ($insertFlag)
                $this->_em->flush();

            return $insertFlag;

        } catch (Exception $exception) {
            throw $exception;
        }

    }

    /**
     * @param $filters
     * @return int|mixed|string
     * @throws Exception
     */
    public function searchServerDetails($filters,$page,$nextUrl)
    {
        try {

            $parameters = [];

            $queryKeys = [
                'hddIds' => 'h.id',
                'ramIds' => 'r.id',
                'locationIds' => 'l.id'
            ];

            $qb = $this->createQueryBuilder('s')
                ->Select('s.id,r.name as ram,h.name as hdd,l.address,s.price')
                ->join('s.ram', 'r')
                ->join('s.hdd', 'h')
                ->join('s.location', 'l')
                ->orderBy('s.id');

            if (!empty($filters)) {
                foreach ($filters as $filterKey => $filterValue) {
                    $qb->andWhere("$queryKeys[$filterKey] in (:$filterKey)");
                    $parameters[$filterKey] = $filterValue;
                }
            }

            if (!empty($parameters)) {
                $qb->setParameters($parameters);
            }

            $query = $qb->getQuery();

            $pageSize = '25';

            $paginator = new Paginator($query);
            $paginator->setUseOutputWalkers(false);

            $totalSearchedServers = $paginator->count();

            $pagesCount = ceil($totalSearchedServers / $pageSize);

            $paginator->getQuery()
                ->setFirstResult($pageSize * ($page-1))
                ->setMaxResults($pageSize);

            $servers = $paginator->getIterator()->getArrayCopy();

            $response = ['TotalSearchedServersCount'=>$totalSearchedServers, 'TotalPages'=>$pagesCount,'Data'=>$servers];

            if ($pagesCount > $page){
                $response['Next Page'] = urldecode($nextUrl);
            }

            return $response;

        } catch (Exception $exception) {
            throw $exception;
        }
    }


}
