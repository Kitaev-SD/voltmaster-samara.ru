<?php
exit;
# 0 0 * * 0 /usr/bin/php -f /var/www/voltmaster-samara.ru/test_set_prop.php &>> /var/www/voltmaster-samara.ru/test_set_prop.log &
$iblock_id = 28; 		# ID инфоблока

$startTime = microtime(true);
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Entity;
CModule::IncludeModule("iblock");
setProp($iblock_id);

#____________________________________ FUNCTIONS ____________________________________________________

#---------- Получаем количество элементов в разделе по id раздела ---------------------------------
function setProp($iblock_id){
	$iterator = CIBlockElement::GetPropertyValues($iblock_id, array(), true, array());
	while ($row = $iterator->Fetch()) {
		// $code_value 	= $row['290']['2'];
		// $elm_id = $row['IBLOCK_ELEMENT_ID'];

		// CIBlockElement::SetPropertyValues($elm_id, $iblock_id, $code_value, 'CML2_BAR_CODE');

		// --- Проверка поля-------
		echo '<pre>';
		print_r($row['287']);
		echo '</pre>';
	}
}