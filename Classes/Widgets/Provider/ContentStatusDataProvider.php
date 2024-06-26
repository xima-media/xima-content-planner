<?php

declare(strict_types=1);

namespace Xima\XimaTypo3ContentPlanner\Widgets\Provider;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use Xima\XimaTypo3ContentPlanner\Domain\Model\Dto\StatusItem;

class ContentStatusDataProvider implements ListDataProviderInterface
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getItems(): array
    {
        return $this->getItemsByDemand();
    }
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getItemsByDemand(?string $status = null, ?int $assignee = null, int $maxResults = 20): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');

        $query = $queryBuilder
            ->select(
                'uid',
                'title',
                'tstamp',
                'tx_ximatypo3contentplanner_status',
                'tx_ximatypo3contentplanner_assignee',
                'tx_ximatypo3contentplanner_comments',
            )
            ->from('pages')
            ->setMaxResults($maxResults)
            ->andWhere('tx_ximatypo3contentplanner_status IS NOT NULL')
            ->orderBy('tstamp', 'DESC');

        if ($status) {
            $query->andWhere('tx_ximatypo3contentplanner_status = :status')
                ->setParameter('status', $status);
        }

        if ($assignee) {
            $query->andWhere('tx_ximatypo3contentplanner_assignee = :assignee')
                ->setParameter('assignee', $assignee);
        }

        $items = [];
        $results = $query->executeQuery()
            ->fetchAllAssociative();

        foreach ($results as $result) {
            try {
                $items[] = StatusItem::create($result);
            } catch (\Exception $e) {
            }
        }

        return $items;
    }
}
