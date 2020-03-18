<?php

namespace App\Entity\Radius;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Find all users that belong to a group
     *
     * @param null $group
     *
     * @return mixed
     */
    public function findByGroup($group = null)
    {
        @ini_set('memory_limit', -1);

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('u', 'rc', 'ug'/*, 'ra'*/)
            ->from(User::class, 'u')
            ->leftJoin('u.radiusCheck', 'rc')
            ->leftJoin('u.userGroups', 'ug')
            ->leftJoin('ug.group', 'groups');
        //->leftJoin('u.radiusAccounting', 'ra');

        if ($group) {
            $query->where('groups.name = :groupname')
                ->setParameter('groupname', $group);
        }
        $users = $query->getQuery()->getResult();

        $radiusAccountingData = $this->getAllAccountingSums();

        /** @var User $user */
        foreach ($users as $user) {
            if (isset($radiusAccountingData[$user->getUsername()])) {
                $user->hydrateRadiusAccountingData($radiusAccountingData[$user->getUsername()]);
            } else {
                $user->hydrateRadiusAccountingData([
                    'currentAcctInputOctets'  => 0,
                    'currentAcctOutputOctets' => 0,
                    'currentAcctSessionTime'  => 0,
                    'lastLogout'              => null,
                ]);
            }
        }

        return $users;
    }

    /**
     * Find a Radius user by username
     *
     * @param string $username
     *
     * @return mixed
     *
     * @deprecated
     */
    public function findByUsername($username)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('u', 'rc', 'ug', 'ra')
            ->from(User::class, 'u')
            ->leftJoin('u.radiusCheck', 'rc')
            ->leftJoin('u.userGroups', 'ug')
            ->leftJoin('ug.group', 'groups')
            ->leftJoin('u.radiusAccounting', 'ra');

        $query->where('u.username = :username')
            ->setParameter('username', $username);

        return $query->getQuery()->getResult();
    }

    /**
     * Gets all the SUMs of the different accounting data to give us used octets in and out, session times, and last
     * logout
     *
     * @return array
     *
     * @throws \Doctrine\ORM\Query\QueryException
     */
    private function getAllAccountingSums()
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('u.username')
            ->addSelect('SUM(ra.acctinputoctets) AS currentAcctInputOctets')
            ->addSelect('SUM(ra.acctoutputoctets) AS currentAcctOutputOctets')
            ->addSelect('SUM(ra.acctsessiontime) AS currentAcctSessionTime')
            ->addSelect('MAX(ra.acctstoptime) AS lastLogout')
            ->addSelect('SUM(rm.totalDuration) AS totalMonthlyDuration')
            ->from(User::class, 'u')
            ->join('u.radiusAccounting', 'ra')
            ->join(AccountingMonthly::class, 'rm', Join::WITH, 'u.username = rm.user')
            ->indexBy('u', 'u.username')
            ->groupBy('u.username');

        return $query->getQuery()->getArrayResult();
    }
}
