<?	global $APPLICATION;
	global $arOptions;
	IncludeModuleLangFile(__FILE__);
	CModule::IncludeModule('sale');
	require_once(__DIR__ .'/classes/general/boxberry.php'); 
	require_once(__DIR__ .'/classes/general/boxberry_parsel.php'); 
	require_once(__DIR__ .'/classes/general/delivery_boxberry.php'); 
	require_once(__DIR__ .'/classes/mysql/boxberry_order.php'); 	
?>