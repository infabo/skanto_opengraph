<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "skanto_opengraph".
 *
 * Auto generated 29-01-2014 17:32
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Open Graph',
	'description' => 'Includes Open Graph meta tags to all pages. Provides a Service to add Open Graph Data.',
	'category' => 'misc',
	'author' => 'Manuel Wohlers',
	'author_email' => 'mw@skanto.de',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.3',
	'constraints' => 
	array (
		'depends' => 
		array (
			'extbase' => '6.1.0-6.1.99',
			'fluid' => '6.1.0-6.1.99',
			'typo3' => '6.1.0-6.1.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
			'less_static_info' => '0.0.1',
		),
	),
);

?>