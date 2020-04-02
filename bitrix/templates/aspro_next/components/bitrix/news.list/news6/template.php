<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/components/bitrix/news.list/news6/styles.css">
<?$this->setFrameMode(true);?>
<?$isAjax = (isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] == "Y");?>
<?if($arResult['ITEMS']):?>
	<?if(!$isAjax):?>
	<div class="item-views table-type-block table-elements <?=$templateName;?>">

		<?// top pagination?>
		<?if($arParams['DISPLAY_TOP_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>

		<?
		$bHasSection = false;
		if($arParams['PARENT_SECTION'] && (isset($arResult['SECTIONS']) && $arResult['SECTIONS']))
		{
			if(isset($arResult['SECTIONS'][$arParams['PARENT_SECTION']]) && $arResult['SECTIONS'][$arParams['PARENT_SECTION']])
				$bHasSection = true;
		}
		if($bHasSection)
		{
			// edit/add/delete buttons for edit mode
			$arSectionButtons = CIBlock::GetPanelButtons($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 0, $arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], array('SESSID' => false, 'CATALOG' => true));
			$this->AddEditAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_EDIT'));
			$this->AddDeleteAction($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="section" id="<?=$this->GetEditAreaId($arResult['SECTIONS'][$arParams['PARENT_SECTION']]['ID'])?>">
			<?
		}?>
		<div class="items row flexbox news_blog">
	<?endif;?>
			<?$arParams['LINE_ELEMENT_COUNT_LIST'] = ($arParams['LINE_ELEMENT_COUNT_LIST'] <=0 ? 3 : $arParams['LINE_ELEMENT_COUNT_LIST']);?>
		
		<?foreach($arResult['ITEMS'] as $arItem) {
			$ID 					= $arItem['ID'];
			$NAME 					= $arItem['NAME'];
			$ACTIVE_FROM 			= $arItem['ACTIVE_FROM'];
			$DISPLAY_ACTIVE_FROM	= $arItem['DISPLAY_ACTIVE_FROM'];
			$TIMESTAMP_X 			= $arItem['TIMESTAMP_X'];
			$DETAIL_PAGE_URL		= $arItem['DETAIL_PAGE_URL'];
			$LIST_PAGE_URL 			= $arItem['LIST_PAGE_URL'];			# /news/
			$DETAIL_TEXT 			= $arItem['DETAIL_TEXT'];
			$PREVIEW_TEXT 			= $arItem['PREVIEW_TEXT'];
			$PREVIEW_PICTURE 		= $arItem['PREVIEW_PICTURE']['SRC'];
			$PREVIEW_PICTURE_ALT 	= $arItem['PREVIEW_PICTURE']['ALT'];
			$PREVIEW_PICTURE_TITLE 	= $arItem['PREVIEW_PICTURE']['TITLE'];

			$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
			?>

			<div class="news_item" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
				<div class="news_item-image">
					<?if($bDetailLink){?><a href="<?=$DETAIL_PAGE_URL?>"><?}?>
						<img src="<?=$PREVIEW_PICTURE?>" alt="<?=$PREVIEW_PICTURE_ALT?>" title="<?=$PREVIEW_PICTURE_TITLE?>">	
					<?if($bDetailLink){?></a><?}?>
				</div>
				<div class="news_item-info">
					<div class="news_date">
						<span><?=$DISPLAY_ACTIVE_FROM?></span>
					</div>
					<div class="news_title">
						<?if($bDetailLink){?><a href="<?=$DETAIL_PAGE_URL?>"><?}?>
							<?=$NAME?>
						<?if($bDetailLink){?></a><?}?>
					</div>
					<div class="news_descr">
						<p><?=$PREVIEW_TEXT?></p>
					</div>
				</div>
			</div>
		<?}?>
	<?if(!$isAjax):?>
			</div>
			<?if($bHasSection):?>
				</div>
			<?endif;?>
	<?endif;?>
		<?// bottom pagination?>
		<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
			<div class="bottom_nav" <?=($isAjax ? "style='display: none; '" : "");?>>
			<?=$arResult['NAV_STRING']?>
			</div>
		<?endif;?>
	<?if(!$isAjax):?>
	</div>
<?endif;?>
<?endif;?>

