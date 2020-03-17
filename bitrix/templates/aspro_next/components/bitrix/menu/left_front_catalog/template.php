<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( false );
global $APPLICATION;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
CModule::IncludeModule("highloadblock");
$hlbl = 7; 	# ID highloadblock блока
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();


if(!function_exists("template_content")) {
	function template_content($arResult, $entity_data_class_) {
		if( !empty( $arResult ) ){
			global $arTheme;?>
			<div class="menu_top_block catalog_block">
				<ul class="menu dropdown">
					<?foreach( $arResult as $key => $arItem ){?>
						<li class="full <?=($arItem["CHILD"] ? "has-child" : "");?> <?=($arItem["SELECTED"] ? "current opened" : "");?> m_<?=strtolower($arTheme["MENU_POSITION"]["VALUE"]);?> v_<?=strtolower($arTheme["MENU_TYPE_VIEW"]["VALUE"]);?>">
							<a class="icons_fa <?=($arItem["CHILD"] ? "parent" : "");?>" href="<?=$arItem["SECTION_PAGE_URL"]?>" >
								<?if($arItem["IMAGES"] && $arTheme["LEFT_BLOCK_CATALOG_ICONS"]["VALUE"] == "Y"){?>
									<span class="image"><img src="<?=$arItem["IMAGES"]["src"];?>" alt="<?=$arItem["NAME"];?>" /></span>
								<?}?>
								<span class="name"><?=$arItem["NAME"]?>
								<? echo "(".getCountFromHLB($entity_data_class_, $arItem["ID"]).")";?>
								</span>
								<div class="toggle_block"></div>
								<div class="clearfix"></div>
							</a>
							<?if($arItem["CHILD"]){?>
								<ul class="dropdown">
									<?foreach($arItem["CHILD"] as $key2 => $arChildItem){?>
										<li class="<?=($arChildItem["CHILD"] ? "has-childs" : "");?> <?if($arChildItem["SELECTED"]){?> current <?}?>">
											<?if($arChildItem["IMAGES"] && $arTheme['SHOW_CATALOG_SECTIONS_ICONS']['VALUE'] == 'Y' && $arTheme["MENU_TYPE_VIEW"]["VALUE"] !== 'BOTTOM'){?>
												<span class="image"><a href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><img src="<?=$arChildItem["IMAGES"]["src"];?>" alt="<?=$arChildItem["NAME"];?>" /></a></span>
											<?}?>
											<a class="section" href="<?=$arChildItem["SECTION_PAGE_URL"];?>"><span>
											<?=$arChildItem["NAME"];?>
											<? echo "(".getCountFromHLB($entity_data_class_, $arChildItem["ID"]).")";?>
											</span></a>
											<?if($arChildItem["CHILD"]){?>
												<ul class="dropdown">
													<?foreach($arChildItem["CHILD"] as $key3 => $arChildItem1){?>
														<li class="menu_item <?if($arChildItem1["SELECTED"]){?> current <?}?>">
															<a class="parent1 section1" href="<?=$arChildItem1["SECTION_PAGE_URL"];?>"><span>
															<?=$arChildItem1["NAME"];?>
															<? echo "(".getCountFromHLB($entity_data_class_, $arChildItem1["ID"]).")";?>
															</span></a>
														</li>
													<?}?>
												</ul>
											<?}?>
											<div class="clearfix"></div>
										</li>
									<?}?>
								</ul>
							<?}?>
						</li>
					<?}?>
				</ul>
			</div>
		<?}
		return $arResult;
	}
}

if(!function_exists("getCountFromHLB")){
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
}


$start = microtime(true);
$obCache = new CPHPCache; 
if ($obCache->InitCache('604800', 'catalog_menu')) {	# время кеширование = одна неделя
   	$vars = $obCache->GetVars(); 
   	template_content($vars, $entity_data_class);
} else if ($obCache->StartDataCache()) {
	$arResult = template_content($arResult, $entity_data_class);
	$obCache->EndDataCache($arResult);
}
$duration = microtime(true) - $start;

?>