<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);?>
<?$APPLICATION->AddChainItem(GetMessage("TITLE"));?>
<?$APPLICATION->SetTitle(GetMessage("TITLE"));?>
<?$APPLICATION->SetPageProperty("TITLE_CLASS", "center");?>
<style type="text/css">
	.left-menu-md, body .container.cabinte-page .maxwidth-theme .left-menu-md, .right-menu-md, body .container.cabinte-page .maxwidth-theme .right-menu-md{display:none !important;}
	.content-md{width:100%;}
</style>
<?global $USER, $APPLICATION;
if( !$USER->IsAuthorized() ){?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"new_register",
	Array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array("EMAIL", "NAME"),
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array("EMAIL", "NAME", "LAST_NAME"),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => array("UF_CHECKORG", "UF_ORGNAME", "UF_INN", "UF_ACCOUNT", "UF_BIK", "UF_LEGALADDRESS"),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?>
<?}else{
	LocalRedirect( $arParams["SEF_FOLDER"] );
}?>
<script>
//Разные шаблоны при регистрации для юр и физлиц.
$('#uri').click(function(){
  $(".organization").show();
  $("[name='UF_ORGNAME']").prop('required',true);
  $("[name='UF_INN']").prop('required',true);
$('input:radio[name="UF_CHECKORG"]').filter('[value="1"]').attr('checked', false);
$('input:radio[name="UF_CHECKORG"]').filter('[value="0"]').attr('checked', true);
});
$('#fiz').click(function(){
  $(".organization").hide();
  $("[name='UF_ORGNAME']").prop('required',false);
  $("[name='UF_INN']").prop('required',false);
  $('input:radio[name="UF_CHECKORG"]').filter('[value="0"]').attr('checked', false);
$('input:radio[name="UF_CHECKORG"]').filter('[value="1"]').attr('checked', true);
});
</script>