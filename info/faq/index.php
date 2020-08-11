<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин «Вольтмастер» - электронные компоненты, измерительные приборы, электроинструментов. Цены. Онлайн-заказ. Доставка. Звоните &amp;amp;#9742; 8-800-55-02-911!");
#$APPLICATION->SetPageProperty("");
$APPLICATION->SetPageProperty("keywords", "Вопросы и ответы");
$APPLICATION->SetPageProperty("title", "Вопросы и ответы");
$APPLICATION->SetTitle("Вопросы и ответы");


global $USER;

if ($USER->IsAdmin()) {
	$PERMISSION = 'Y';
} else {
	$PERMISSION = 'I';
}

?>

<?$APPLICATION->IncludeComponent(
	"bitrix:forum.comments",
	"faq",
	Array(
		"ALLOW_ALIGN" => "N",
		"ALLOW_ANCHOR" => "N",
		"ALLOW_BIU" => "N",
		"ALLOW_CODE" => "N",
		"ALLOW_FONT" => "N",
		"ALLOW_HTML" => "N",
		"ALLOW_IMG" => "N",
		"ALLOW_LIST" => "N",
		"ALLOW_MENTION" => "N",
		"ALLOW_NL2BR" => "N",
		"ALLOW_QUOTE" => "N",
		"ALLOW_SMILES" => "N",
		"ALLOW_TABLE" => "N",
		"ALLOW_VIDEO" => "N",
		"CACHE_TIME" => "0",
		"CACHE_TYPE" => "A",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"EDITOR_CODE_DEFAULT" => "Y",
		"ENTITY_ID" => "1",
		"ENTITY_TYPE" => "s1",
		"ENTITY_XML_ID" => "Вопрос-ответ",
		"FORUM_ID" => "1",
		"IMAGE_HTML_SIZE" => "0",
		"IMAGE_SIZE" => "600",
		"MESSAGES_PER_PAGE" => "10",
		"NAME_TEMPLATE" => "",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"PERMISSION" => $PERMISSION,
		"PREORDER" => "N",
		"SET_LAST_VISIT" => "Y",
		"SHOW_MINIMIZED" => "Y",
		"SHOW_RATING" => "Y",
		"SUBSCRIBE_AUTHOR_ELEMENT" => "Y",
		"URL_TEMPLATES_PROFILE_VIEW" => "",
		"URL_TEMPLATES_READ" => "",
		"USER_FIELDS" => array("UF_FORUM_MES_URL_PRV"),
		"USE_CAPTCHA" => "Y",
 		"POST_FIRST_MESSAGE" => "Y"
	)
	// $component
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>