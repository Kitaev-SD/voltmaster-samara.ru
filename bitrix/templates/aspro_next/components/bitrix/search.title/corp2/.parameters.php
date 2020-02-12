<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arTemplateParameters = array(
	"SHOW_INPUT" => array(
		"NAME" => GetMessage("TP_BST_SHOW_INPUT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",
	),
	"SHOW_INPUT_FIXED" => array(
		"NAME" => GetMessage("T_SHOW_INPUT_FIXED"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"INPUT_ID" => array(
		"NAME" => GetMessage("TP_BST_INPUT_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "title-search-input",
	),
	"CONTAINER_ID" => array(
		"NAME" => GetMessage("TP_BST_CONTAINER_ID"),
		"TYPE" => "STRING",
		"DEFAULT" => "title-search",
	),
);
?>
