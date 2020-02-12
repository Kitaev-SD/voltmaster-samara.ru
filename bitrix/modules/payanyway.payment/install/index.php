<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/prolog.php'); 

/**
 * Install and Deinstall payanyway.payment
 *
 */
class payanyway_payment extends CModule {	// Required vars
	
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $aPaths; // Array of paths for install
	var $MODULE_ID = 'payanyway.payment';

	/**
	 * Constructor
	 */
	function __construct() {
		$this->PARTNER_NAME = 'PayAnyWay';
		$this->PARTNER_URI = 'https://payanyway.ru';

		include(dirname(__FILE__).'/version.php');

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = GetMessage('PAYANYWAY.PAYMENT_INSTALL_NAME');
		if (CModule::IncludeModule($this->MODULE_ID)) {
			$this->MODULE_DESCRIPTION = GetMessage('PAYANYWAY.PAYMENT_INSTALL_DESCRIPTION');
		} else {
			$this->MODULE_DESCRIPTION = GetMessage('PAYANYWAY.PAYMENT_PREINSTALL_DESCRIPTION');
		}
		$this->aPaths = array(
			'bitrix' => '/bitrix',
			'payanyway' => '/payanyway',
		);
	}

	function DoInstall() {
		global $APPLICATION, $DB;

		$GLOBALS['errors'] = false;
		$this->errors = false;
		// copy files
		if (!CModule::IncludeModule($this->MODULE_ID)) {
			$this->InstallFiles();
			RegisterModule($this->MODULE_ID);
		}

		$GLOBALS['errors'] = $this->errors;
		$APPLICATION->IncludeAdminFile(
			GetMessage('PAYANYWAY.PAYMENT_INSTALL_TITLE'), 
			$_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/payanyway.payment/install/step_ok.php'
			);
	}

	function DoUninstall() {
		global $APPLICATION, $uninstall;

		if (isset($uninstall) && $uninstall == 'Y' && CModule::IncludeModule($this->MODULE_ID)) {
			$this->UnInstallFiles();
			UnRegisterModule($this->MODULE_ID);
		} 
		else {
			$APPLICATION->IncludeAdminFile(
				GetMessage('PAYANYWAY.PAYMENT_INSTALL_TITLE'), 
				$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/install/unstep_ok.php'
				);
		}
	}

	/**
	 * Copy files
	 * @return boolean
	 */
	function InstallFiles() {
		$path_from = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/install/www';
		$path_to = $_SERVER['DOCUMENT_ROOT'];
		foreach ($this->aPaths as $name_from => $name_to) {
			if (!CopyDirFiles($path_from . $this->aPaths[$name_from], $path_to . $this->aPaths[$name_from], true, true, false, '.svn')) {
				$this->errors = array(GetMessage('PAYANYWAY.PAYMENT_INSTALL_ERROR'));
			}
		}

                // install reports
		$path_from = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/install/reports';
		$path_to = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/reports';
		foreach ($this->aPaths as $name_from => $name_to) {
			if (!CopyDirFiles($path_from . $this->aPaths[$name_from], $path_to . $this->aPaths[$name_from], true, true, false, '.svn')) {
				// $this->errors = array(GetMessage('PAYANYWAY.PAYMENT_INSTALL_ERROR'));
			}
		}

		return true;
	}

	/**
	 * Delete files
	 * @return boolean
	 */
	function UnInstallFiles() {
		$path_from = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/install/www';
		$path_to = $_SERVER['DOCUMENT_ROOT'];
		
		DeleteDirFilesEx('/payanyway');
		DeleteDirFilesEx('/bitrix/php_interface/include/sale_payment/payanyway.payment');

                DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/payanyway.payment/install/reports', 
                               $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/reports');

		return true;
	}

}
