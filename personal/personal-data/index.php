<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	$APPLICATION->SetTitle("Персональные данные");
	if(!$USER->isAuthorized()){LocalRedirect(SITE_DIR.'auth');} else {
?>
<?$APPLICATION->IncludeComponent("bitrix:main.profile", "profile", array(
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"SET_TITLE" => "N",
	"SEND_INFO" => "N",
	"CHECK_RIGHTS" => "N",
	"USER_PROPERTY_NAME" => "",
	"USER_PROPERTY" => Array("UF_CHECKORG", "UF_ORGNAME", "UF_INN", "UF_ACCOUNT", "UF_BIK", "UF_LEGALADDRESS", "UF_BANK", "UF_CORRACC"), 
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>