<?php
namespace HcbIamDioeMeme\HcbIamdioeMeme\Domain\Repository;

/***
 *
 * This file is part of the "iamDioe Meme" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * The repository for MemeEntries
 */
class MemeEntrieRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Freigegebene Einträge
     * 
     * @return Tx_Extbase_Persistence_QueryResultInterface
     */
    public function getAllPublic(){
        $query = $this->createQuery();
        $query->matching($query->equals('freigegeben', 1));
        return $query->execute();
    }

}
