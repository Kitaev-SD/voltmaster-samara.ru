<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Магазин «Вольтмастер» - электронные компоненты, измерительные приборы, электроинструментов. Цены. Онлайн-заказ. Доставка. Звоните &amp;amp;#9742; 8-800-55-02-911!");
#$APPLICATION->SetPageProperty("");
$APPLICATION->SetPageProperty("keywords", "Вопросы и ответы");
$APPLICATION->SetPageProperty("title", "Вопросы и ответы");
$APPLICATION->SetTitle("Вопросы и ответы");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:forum.comments",
	"faq",
	Array(
		"ALLOW_ALIGN" => "Y",
		"ALLOW_ANCHOR" => "Y",
		"ALLOW_BIU" => "Y",
		"ALLOW_CODE" => "Y",
		"ALLOW_FONT" => "Y",
		"ALLOW_HTML" => "Y",
		"ALLOW_IMG" => "Y",
		"ALLOW_LIST" => "Y",
		"ALLOW_MENTION" => "Y",
		"ALLOW_NL2BR" => "Y",
		"ALLOW_QUOTE" => "Y",
		"ALLOW_SMILES" => "Y",
		"ALLOW_TABLE" => "Y",
		"ALLOW_VIDEO" => "Y",
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
		// "PERMISSION" => "I",
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