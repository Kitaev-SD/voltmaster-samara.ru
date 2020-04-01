<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/components/bitrix/news.list/news-blog3/styles.css">


<?$this->setFrameMode(true);
$isAjax = (isset($_GET["AJAX_REQUEST"]) && $_GET["AJAX_REQUEST"] == "Y");?>

<div class="banners-small blog news_blog">

	<?if($arResult['ITEMS']) {
		foreach($arResult['ITEMS'] as $arItem) {
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

			<div class="news_item">
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
		<?}
	}?>

</div>


<?if($arResult['ITEMS']):?>
	<?if(!$isAjax):?>
		<div class="banners-small blog news_blog">
	<?endif;?>
			<?foreach($arResult['ITEMS'] as $arItems):?>
				<div class="items row">
					<?foreach($arItems['ITEMS'] as $key => $arItem):?>
						<div class="row">
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						
						

						// use detail link?
						

						$isWideBlock = (isset($arItem['CLASS_WIDE']) && $arItem['CLASS_WIDE']);
						$hasWideBlock = (isset($arItem['CLASS']) && $arItem['CLASS']);
						?>
						<?if(isset($arItem['START_DIV']) && $arItem['START_DIV'] == 'Y'):?>
							<div class="col-md-4 col-sm-4">
						<?endif;?>
							<div class="<?=((isset($arItem['CLASS']) && $arItem['CLASS']) ? $arItem['CLASS'] : 'col-md-4 col-sm-4');?>">
								<div class="item shadow animation-boxs <?=($isWideBlock ? 'wide-block' : '')?> <?=($hasWideBlock ? '' : 'normal-block')?>"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
									<div class="inner-item">
										<?if($bImage):?>
											<div class="image shine">
												<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
												<img data-src=<?=$imageSrc?> alt="<?=($bImage ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($bImage ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
												<?if($bDetailLink):?></a><?endif;?>
											</div>
										<?endif;?>
										<div class="title">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?endif;?>
												<span><?=$arItem['NAME']?></span>
											<?if($bDetailLink):?></a><?endif;?>
											<?if($arItem['PREVIEW_TEXT'] && ($isWideBlock || !$bImage)):?>
												<div class="prev_text-block"><?=$arItem['PREVIEW_TEXT'];?></div>
											<?endif;?>
											<?if($arItem['DISPLAY_ACTIVE_FROM']):?>
												<div class="date-block"><?=$arItem['DISPLAY_ACTIVE_FROM'];?></div>
											<?endif;?>
										</div>
									</div>
								</div>
							</div>
						<?if(isset($arItem['END_DIV']) && $arItem['END_DIV'] == 'Y'):?>
							</div>
						<?endif;?>
						</div>
					<?endforeach;?>
				</div>
			<?endforeach;?>
			<div class="bottom_nav" <?=($isAjax ? "style='display: none; '" : "");?>>
				<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
			</div>
	<?if(!$isAjax):?>
		</div>
	<?endif;?>
<?endif;?>