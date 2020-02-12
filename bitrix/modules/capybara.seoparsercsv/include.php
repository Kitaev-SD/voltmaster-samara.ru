<?php
global $DB, $MESS, $APPLICATION;

require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/admin_tools.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/filter_tools.php');


CModule::AddAutoloadClasses('capybara.seoparsercsv', array(
    'seoparsercsvGet' => 'classes/get.php',
    'seoparsercsvSave' => 'classes/save.php',
    'seoparsercsvParser' => 'classes/parse.php'
));
