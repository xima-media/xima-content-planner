<?php

return [
    'ximatypo3contentplanner_filterrecords' => [
        'path' => '/content-planner/records',
        'target' => \Xima\XimaTypo3ContentPlanner\Controller\RecordController::class . '::filterAction',
    ],
    'ximatypo3contentplanner_comments' => [
        'path' => '/content-planner/comments',
        'target' => \Xima\XimaTypo3ContentPlanner\Controller\RecordController::class . '::commentsAction',
    ],
];
