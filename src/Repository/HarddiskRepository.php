<?php

namespace App\Repository;

use App\Entity\Harddisk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Harddisk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Harddisk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Harddisk[]    findAll()
 * @method Harddisk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HarddiskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Harddisk::class);
    }

    /**
     * To store hard disk data
     * @param $hddData
     * @return array
     * @throws Exception
     */
    public function storeHardDiskDetails($hddData): array
    {
        try {
            $presentRecords = [];
            if (!empty($hddData)) {

                $flush = false;
                $records = $this->findBy(['name' => $hddData]);

                if (!empty($records)) {
                    foreach ($records as $data) {
                        $presentRecords[$data->getName()] = $data;
                    }
                }

                foreach ($hddData as $data) {

                    $separator = (strpos($data, "TB") !== false) ? 'TB' : 'GB';
                    $temp = explode($separator, $data);

                    if (!array_key_exists($data, $presentRecords) && !empty($temp) && count($temp) > 1) {

                        $size = explode('x', $temp[0]);
                        $size = $size[0] * $size[1];

                        $size = ($separator == "TB") ? $size * 1000 : $size;
                        $type = (strpos($temp[1], "SATA") !== false) ? "SATA" : $temp[1];

                        $hardDisk = new Harddisk();
                        $hardDisk->setName($data);
                        $hardDisk->setSize($size);
                        $hardDisk->setType($type);

                        $this->_em->persist($hardDisk);
                        $flush = true;
                    }
                }
                if ($flush) {

                    $this->_em->flush();
                    $presentRecords = [];
                    $records = $this->findBy(['name' => $hddData]);

                    if (!empty($records)) {
                        foreach ($records as $data) {
                            $presentRecords[$data->getName()] = $data;
                        }
                    }
                }
            }

            return $presentRecords;

        }catch (Exception $exception){
            throw $exception;
        }
    }

    /**
     * @param $searchData
     * @return array
     * @throws Exception
     */
    public function getHardDiskSearchIds($searchData): array
    {
        try {

            $parameters = $ids = [];
            $qb = $this->createQueryBuilder('h')->select('h.id');

            if(isset($searchData['type']) && !empty($searchData['type'])){
                $qb->where('h.type = :type');
                $parameters['type'] = $searchData['type'];
            }

            if(isset($searchData['size']['form']) && !empty($searchData['size']['form'])){
                $qb->andWhere('h.size >= :formSize');
                $parameters['formSize'] = $searchData['size']['form'];
            }
            if(isset($searchData['size']['to']) && !empty($searchData['size']['to'])){
                $qb->andWhere('h.size <= :toSize');
                $parameters['toSize'] = $searchData['size']['to'];
            }

            if (!empty($parameters)){
                $qb->setParameters($parameters);
            }

            $result = $qb->getQuery()->execute();

            if (!empty($result)){
                $ids = array_column($result,'id');
            }

            return $ids;

        }catch (Exception $exception){
            throw $exception;
        }
    }
}
