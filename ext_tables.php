<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Open Graph');


// add additional fields to pages
// @todo: move new fields to a separate TAB!
$temporaryColumns = array (	
	'tx_skantoopengraph_title' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:skanto_opengraph/Resources/Private/Language/locallang_db.xml:pages.tx_skantoopengraph_title',
        'config' => array (
			'type' => 'input',
			'size' => '30',
		)
	),
	'tx_skantoopengraph_description' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:skanto_opengraph/Resources/Private/Language/locallang_db.xml:pages.tx_skantoopengraph_description',
        'config' => array (
			'type' => 'text',
		)
	),
	// @todo: 
	/*
		possibility to add more open graph protocol objects
		(images, articles, etc.)
	*/
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $temporaryColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;LLL:EXT:skanto_opengraph/Resources/Private/Language/locallang_db.xml:pages.tx_skantoopengraph_palette,tx_skantoopengraph_title,tx_skantoopengraph_description,--div--,','','after:lastUpdated'); 

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages_language_overlay', $temporaryColumns, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages_language_overlay', '--div--;LLL:EXT:skanto_opengraph/Resources/Private/Language/locallang_db.xml:pages.tx_skantoopengraph_palette;tx_skantoopengraph_title,tx_skantoopengraph_description,--div--,', '', 'after:lastUpdated'); 


?>