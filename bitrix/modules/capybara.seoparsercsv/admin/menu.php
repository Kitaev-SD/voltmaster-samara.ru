<?php
/**
 * Файл для отображения пунктов меню в административной панели Битрикс.
 */
IncludeModuleLangFile(__FILE__);

$aMenu = array(
    'parent_menu' => 'global_menu_services',
    'section' => 'capybara_seoparsercsv',
    'sort' => 100,
    'module_id' => 'capybara.seoparsercsv',
    'text' => GetMessage('CAPYBARA_MENU_MAIN'),
    'title' => GetMessage('VOTE_MENU_MAIN_TITLE'),
    ///@todo Добавить иконку.
    'page_icon' => 'capybara_seoparsercsv_page_icon',
    'items_id' => 'capybara_seoparsercsv',
    'items' => array(
        array(
            'text' => GetMessage('CAPYBARA_MENU_2'),
            'url' => 'seoparsercsv_admin.php?lang='.LANGUAGE_ID,
            'more_url' => Array(),
            'title' => GetMessage('CAPYBARA_MENU_2_ALT')
        ),
    )
);
return $aMenu;
