<?
IncludeModuleLangFile(__FILE__);
$module_id = "up.boxberrydelivery";
if($APPLICATION->GetGroupRight($module_id) != "D")
{
    $aMenu = array(
        "parent_menu" => "global_menu_store",
        "sort" => 100,
        "text" => GetMessage("BB_MENU_TEXT"),
        "title" => GetMessage("BB_MENU_TITLE"),
        "icon" => "boxberry_menu_icon",
        "page_icon" => "boxberry_page_icon",
        "url" => "boxberry.php?lang=".LANGUAGE_ID,
    );
	return $aMenu;
}
return false;



?>



