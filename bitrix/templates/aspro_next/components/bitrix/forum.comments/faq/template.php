<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
CUtil::InitJSCore(array('ajax'));
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
// ************************* Input params***************************************************************
?>
<div class="feed-wrap faq_wrap">
<div class="feed-comments-block">
	<a name="comments"></a>
<?
// *************************/Input params***************************************************************

$link = $APPLICATION->GetCurPageParam("MID=#ID#", array("MID", "sessid", "AJAX_POST", "ENTITY_XML_ID", "ENTITY_TYPE", "ENTITY_ID", "REVIEW_ACTION", "MODE", "FILTER", "result"));

if (isset($arParams["PUBLIC_MODE"]) && $arParams["PUBLIC_MODE"])
{
	$editRight = "N";
}
else
{
	$editRight = (
		$arResult["PANELS"]["EDIT"] == "N"
			? (
				$arParams["ALLOW_EDIT_OWN_MESSAGE"] === "N"
					? "OWN"
					: ($arParams["ALLOW_EDIT_OWN_MESSAGE"] === "LAST" ? "OWNLAST" : "N")
			)
			: "Y"
	);
}

$arResult["OUTPUT_LIST"] = $APPLICATION->IncludeComponent(
	"bitrix:main.post.list",
	"faq",
	array(
		"TEMPLATE_ID" => $arParams["tplID"],
		"RATING_TYPE_ID" => ($arParams["SHOW_RATING"] == "Y" ? "FORUM_POST" : ""),
		"ENTITY_XML_ID" => $arParams["ENTITY_XML_ID"],
		"RECORDS" => $arResult["MESSAGES"],

		"NAV_STRING" => $arResult["NAV_STRING"],
		"NAV_RESULT" => $arResult["NAV_RESULT"],
		"PREORDER" => $arParams["PREORDER"],
		"RIGHTS" => array(
			"MODERATE" =>  $arResult["PANELS"]["MODERATE"],
			"EDIT" => $editRight,
			"DELETE" => $editRight
		),
		"VISIBLE_RECORDS_COUNT" => 3,

		"ERROR_MESSAGE" => $arResult["ERROR_MESSAGE"],
		"OK_MESSAGE" => $arResult["OK_MESSAGE"],
		"RESULT" => ($arResult["RESULT"] ?: $request->getQuery("MID")),
		"PUSH&PULL" => $arResult["PUSH&PULL"],
		"VIEW_URL" => ($arParams["SHOW_LINK_TO_MESSAGE"] == "Y" && !(isset($arParams["PUBLIC_MODE"]) && $arParams["PUBLIC_MODE"]) ? $link : ""),
		"EDIT_URL" => ForumAddPageParams($link, array("REVIEW_ACTION" => "GET"), false, false),
		"MODERATE_URL" => ForumAddPageParams($link, array("REVIEW_ACTION" => "#ACTION#"), false, false),
		"DELETE_URL" => ForumAddPageParams($link, array("REVIEW_ACTION" => "DEL"), false, false),
		"AUTHOR_URL" => $arParams["URL_TEMPLATES_PROFILE_VIEW"],

		"AVATAR_SIZE" => $arParams["AVATAR_SIZE_COMMENT"],
		"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
		"SHOW_LOGIN" => $arParams['SHOW_LOGIN'],

		"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
		"LAZYLOAD" => $arParams["LAZYLOAD"],

		"NOTIFY_TAG" => ($arParams["bFromList"] ? "BLOG|COMMENT" : ""),
		"NOTIFY_TEXT" => ($arParams["bFromList"] ? TruncateText(str_replace(Array("\r\n", "\n"), " ", $arParams["POST_DATA"]["~TITLE"]), 100) : ""),
		"SHOW_MINIMIZED" => $arParams["SHOW_MINIMIZED"],
		"SHOW_POST_FORM" => $arResult["SHOW_POST_FORM"],

		"IMAGE_SIZE" => $arParams["IMAGE_SIZE"],
		"BIND_VIEWER" => $arParams["BIND_VIEWER"],
		"mfi" => $arParams["mfi"],
		"bPublicPage" => (isset($arParams["PUBLIC_MODE"]) && $arParams["PUBLIC_MODE"])
	),
	$this->__component
);
?><?=$arResult["OUTPUT_LIST"]["HTML"]?><?
if ($arResult["SHOW_POST_FORM"] == "Y")
{
	include(__DIR__."/form.php");
}
?>
</div>
</div>

<script type="text/javascript">
   $(document).ready(function(){
      $('.comments-reply-field-captcha-image, .ui-btn.ui-btn-sm.ui-btn-primary').click(function(){
         $.getJSON('<?=$this->__folder?>/reload_captcha.php', function(data) {
            $('.comments-reply-field-captcha-image img').attr('src','/bitrix/tools/captcha.php?captcha_sid='+data);
            $('input[name="captcha_code"]').val(data);
         });
         return false;
      });
   });
</script>