<?php
global $MESS;
$PathInstall = str_replace('\\', '/', __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
IncludeModuleLangFile($PathInstall.'/install.php');
include($PathInstall.'/version.php');
if (class_exists('capybara_seoparsercsv')) {
    return;
}

/**
 * Class capybara_seoparsercsv
 */
Class capybara_seoparsercsv extends CModule {
    var $MODULE_ID = 'capybara.seoparsercsv';
    public function __construct() {
        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path.'/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->PARTNER_NAME = GetMessage('CAPYBARA_PARSER_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('CAPYBARA_PARSER_PARTNER_URL');
        $this->MODULE_NAME = GetMessage('CAPYBARA_PARSER_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('CAPYBARA_PARSER_INSTALL_DESCRIPTION');
        $this->IBLOCK_TYPE = GetMessage('CAPYBARA_PARSER_IBLOCK_TYPE');
    }

    /**
     * Установка модуля
     */
    public function DoInstall() {
        if( !$this->InstallDB() || !$this->InstallEvents() || !$this->InstallFiles() ) {
            return;
        }
        RegisterModule( $this->MODULE_ID );
    }

    /**
     * Удаление модуля
     */
    public function DoUninstall() {
        if( !$this->UnInstallDB() || !$this->UnInstallEvents() || !$this->UnInstallFiles() ) {
            return;
        }
        UnRegisterModule( $this->MODULE_ID );

    }

    /**
     * Установка событий
     *
     * @return bool
     */
    public function InstallEvents() {
        //RegisterModuleDependences();
        return true;
    }

    /**
     * Удаление событий
     *
     * @return bool
     */
    public function UnInstallEvents() {
        //UnRegisterModuleDependences();
        return true;
    }

    /**
     * Установка файлов модуля.
     *
     * @return bool
     */
    public function InstallFiles() {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/capybara.seoparsercsv/install/admin', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
        return true;
    }

    /**
     * Удаление файлов модуля.
     *
     * @return bool
     */
    public function UnInstallFiles() {
        ///@todo Реализовать удаление файлов с данными сборов и файлы с логами при удалении модуля.
        return true;
    }

    /**
     * Установка таблиц связанных с модулем в базу.
     *
     * @return bool
     */
    public function InstallDB() {
        // Создание инфоблока сбора.
        return true;
    }

    /**
     * Удаление таблиц связанных с модулем из базы.
     *
     * @return bool
     */
    public function UnInstallDB() {
        // Удаление инфоблока сбора.
        return true;
    }
}
