<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'tw_events',
    'tx_twevents_domain_model_organization'
);
