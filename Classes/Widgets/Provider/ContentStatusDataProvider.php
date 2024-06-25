<?php

declare(strict_types=1);

namespace Xima\XimaContentPlanner\Widgets\Provider;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use Xima\XimaContentPlanner\Configuration;
use Xima\XimaContentPlanner\EventListener\DrawBackendHeaderListener;

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
                'tx_ximacontentplanner_status',
                'tx_ximacontentplanner_assignee',
                'tx_ximacontentplanner_comments',
            )
            ->from('pages')
            ->setMaxResults($maxResults)
            ->andWhere('tx_ximacontentplanner_status IS NOT NULL')
            ->orderBy('tstamp', 'DESC');

        if ($status) {
            $query->andWhere('tx_ximacontentplanner_status = :status')
                ->setParameter('status', $status);
        }

        if ($assignee) {
            $query->andWhere('tx_ximacontentplanner_assignee = :assignee')
                ->setParameter('assignee', $assignee);
        }

        $items = [];
        $results = $query->executeQuery()
            ->fetchAllAssociative();

        foreach ($results as $result) {
            try {
                $items[] = $this->createListItem($result);
            } catch (\Exception $e) {
            }
        }

        return $items;
    }

    private function createListItem(array $result): array
    {
        return [
            'uid' => $result['uid'],
            'title' => $result['title'],
            'tstamp' => $result['tstamp'],
            'status' => $result['tx_ximacontentplanner_status'],
            'status_icon' => Configuration::STATUS_ICONS[$result['tx_ximacontentplanner_status']],
            'assignee' => (int)$result['tx_ximacontentplanner_assignee'],
            'assignee_name' => DrawBackendHeaderListener::getBackendUsernameById((int)$result['tx_ximacontentplanner_assignee']),
            'comments' => $result['tx_ximacontentplanner_comments'],
        ];
    }
}
