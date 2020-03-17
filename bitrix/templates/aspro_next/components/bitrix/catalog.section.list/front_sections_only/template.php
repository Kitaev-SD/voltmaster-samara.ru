<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
CModule::IncludeModule("highloadblock");
$hlbl = 7; 	# ID highloadblock блока
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

if($arResult['SECTIONS']):?>
	<div class="sections_wrapper">
		<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
			<div class="top_block">
				<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
				<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
			</div>
		<?endif;?>
		<div class="list items">
			<div class="row margin0 flexbox">
				<?foreach($arResult['SECTIONS'] as $arSection):
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
					<div class="col-md-3 col-sm-4 col-xs-6">
						<div class="item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
							<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]!="N"):?>
								<div class="img shine">
									<?if($arSection["PICTURE"]["SRC"]):?>
										<?$img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
									<?elseif($arSection["~PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
									<?else:?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/catalog_category_noimage.png" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" /></a>
									<?endif;?>
								</div>
							<?endif;?>
							<div class="name">
								<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link">
									<?=$arSection['NAME'];?> <? echo "(".getCountFromHLB($entity_data_class, $arSection["ID"]).")";?>
								</a>
							</div>
						</div>
					</div>
				<?endforeach;?>
			</div>
		</div>
	</div>
<?endif;?>

<?
function getCountFromHLB($entity_data_class_, $section_id_){
	$rsData = $entity_data_class_::getList(array(
	   "select" => array("*"),
	   "order" => array(),
	   "filter" => array('UF_SECTION_ID' => $section_id_)
	));

	$elm_count = '';

	if ($arData = $rsData->fetch()) {
		$elm_count = $arData['UF_ELEMENT_COUNT'];
	}

	return $elm_count;
}
?>