<?php

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Backend\Controller\Page\TreeController::class] = [
    'className' => \Xima\XimaContentPlanner\Controller\TreeController::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1719240005] = [
    'nodeName' => 'currentUser',
    'priority' => 40,
    'class' => \Xima\XimaContentPlanner\Form\Element\CurrentUser::class,
];
