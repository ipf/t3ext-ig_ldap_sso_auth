<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Init table configuration array for tx_igldapssoauth_config.
$GLOBALS['TCA']['tx_igldapssoauth_config'] = array(
	'ctrl' => array(
		'title'     => 'LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:tx_igldapssoauth_config',
		'label'     => 'name',
		'adminOnly' => 1,
		'rootLevel' => 1,
		'dividers2tabs'=> TRUE,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		//'type' => 'name',
		'default_sortby' => 'ORDER BY name',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Config.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/icon_tx_igldapssoauth_config.png',
	),

	'feInterface' => array(
		'fe_admin_fieldList' => 'hidden, name, cas_host,cas_uri, cas_port, ldap_server, ldap_protocol, ldap_host, ldap_port, ldap_basedn, ldap_password, be_users_basedn, be_users_filter, be_users_mapping, be_groups_basedn, be_groups_filter, be_groups_mapping, fe_users_basedn, fe_users_filter, fe_users_mapping, fe_groups_basedn, fe_groups_filter, fe_groups_mapping',
	)

);

// Add fields tx_igldapssoauth_dn to be_groups TCA.
$tempColumns = array(
	'tx_igldapssoauth_dn' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:tx_igldapssoauth_config.be_groups.tx_igldapssoauth_dn',
		'config' => array(
			'type' => 'input',
			'size' => 30,
		)
	),
);

if (version_compare(TYPO3_version, '6.1.0', '<')) {
	t3lib_div::loadTCA('be_groups');
}
t3lib_extMgm::addTCAcolumns('be_groups', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('be_groups', 'tx_igldapssoauth_dn;;;;1-1-1');

//// Add fields tx_igldapssoauth_dn to be_users TCA.
//$tempColumns = array(
//	'tx_igldapssoauth_dn' => array(
//		'exclude' => 1,
//		'label' => 'LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:be_users.tx_igldapssoauth_dn',
//		'config' => array(
//			'type' => 'input',
//			'size' => '30',
//		)
//	),
//);
//
//if (version_compare(TYPO3_version, '6.1.0', '<')) {
//	t3lib_div::loadTCA('be_users');
//}
//t3lib_extMgm::addTCAcolumns('be_users', $tempColumns, 1);
//t3lib_extMgm::addToAllTCAtypes('be_users', 'tx_igldapssoauth_dn;;;;1-1-1');

// Add fields tx_igldapssoauth_dn to fe_groups TCA.
$tempColumns = array(
	'tx_igldapssoauth_dn' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:tx_igldapssoauth_config.fe_groups.tx_igldapssoauth_dn',
		'config' => array(
			'type' => 'input',
			'size' => '30',
		)
	),
);

if (version_compare(TYPO3_version, '6.1.0', '<')) {
	t3lib_div::loadTCA('fe_groups');
}
t3lib_extMgm::addTCAcolumns('fe_groups', $tempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('fe_groups', 'tx_igldapssoauth_dn');

//// Add fields tx_igldapssoauth_dn to fe_users TCA.
//$tempColumns = array(
//	'tx_igldapssoauth_dn' => Array (
//		'exclude' => 1,
//		'label' => 'LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:fe_users.tx_igldapssoauth_dn',
//		'config' => array(
//			'type' => 'input',
//			'size' => '30',
//		)
//	),
//);
//
//if (version_compare(TYPO3_version, '6.1.0', '<')) {
//	t3lib_div::loadTCA('fe_users');
//}
//t3lib_extMgm::addTCAcolumns('fe_users', $tempColumns, 1);
//t3lib_extMgm::addToAllTCAtypes('fe_users', 'tx_igldapssoauth_dn;;;;1-1-1');

$icons = array(
	'overlay-ldap-record' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/overlay-ldap-record.png',
);
t3lib_SpriteManager::addSingleIcons($icons, $_EXTKEY);

$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayPriorities'][] = 'is_ldap_record';
$GLOBALS['TBE_STYLES']['spriteIconApi']['spriteIconRecordOverlayNames']['is_ldap_record'] = 'extensions-' . $_EXTKEY . '-overlay-ldap-record';

// Alter users TCA
$GLOBALS['EXT_CONFIG']['enableBELDAPAuthentication'] ? $GLOBALS['TCA']['be_users']['columns']['password']['config']['eval'] = 'md5,password' : null;
$GLOBALS['EXT_CONFIG']['enableFELDAPAuthentication'] ? $GLOBALS['TCA']['fe_users']['columns']['password']['config']['eval'] = 'password': null;
// Load TCA.

if (version_compare(TYPO3_version, '6.1.0', '<')) {
	t3lib_div::loadTCA('tt_content');
}
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1']='layout,select_key';

// Add plugin in list_type

t3lib_extMgm::addPlugin(array('LLL:EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY . '_pi1'), 'list_type');

// Add plugin to content wizard

if (TYPO3_MODE === 'BE') {
	$GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_igldapssoauth_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'pi1/class.tx_igldapssoauth_pi1_wizicon.php';
}

// Add BE module on top of tools main module

if (TYPO3_MODE === 'BE') {
	t3lib_extMgm::addModule('tools', 'txigldapssoauthM1', 'top', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}

// Initialize "context sensitive help" (csh)
t3lib_extMgm::addLLrefForTCAdescr('tx_igldapssoauth_config', 'EXT:ig_ldap_sso_auth/Resources/Private/Language/locallang_csh_db.xml');

// Initialize static extension templates (remove deprecated TS in version 1.4)
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'ig_ldap_sso_auth [DEPRECATED]');
t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'ig_ldap_sso_auth');
