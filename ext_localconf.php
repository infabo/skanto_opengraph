<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['css_styled_content']['pi1_hooks']['render_singleMediaElement'][] = 'Skanto\SkantoOpengraph\Service\OpenGraphService->cssStyledContentAddImage';

$TYPO3_CONF_VARS['FE']['pageOverlayFields'] .= $TYPO3_CONF_VARS['FE']['pageOverlayFields']  ? ',' : ''; 
$TYPO3_CONF_VARS['FE']['pageOverlayFields'] .= 'tx_skantoopengraph_title,tx_skantoopengraph_description';

?>