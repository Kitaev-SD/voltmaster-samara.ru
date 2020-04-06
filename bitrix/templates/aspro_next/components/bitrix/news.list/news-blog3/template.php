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