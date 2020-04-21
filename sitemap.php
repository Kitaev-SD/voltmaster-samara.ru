<?
#------------------------------------------------------------------------------------------------------------
$sitemap_mod = 0;				# 1 - в одном сайтмапе,  0 - разделить на категории

$count_per_page = 5000;			# количество товаров на одной странице сайтмап

$iblock_id_catalog = 28;		# инфоблок каталога товаров

$iblock_id_other = array(		# Остальные инфоблоки
	'tizers'	=>	10,			#  - Тизеры
	'staff'		=>	11,			#  - Сотрудники
	'faq'		=>	12,			#  - Вопросы и ответы
	'stores'	=>	13,			#  - Магазины
	'vacancies'	=>	14,			#  - Вакансии
	'brands'	=>	15,			#  - Бренды
	'certif'	=>	16,			#  - Лицензия и сернтификаты
	'services'	=>	17,			#  - Услуги
	'news'		=>	18,			#  - Новости
	'projects'	=>	19,			#  - Проекты
	'blog'		=>	21,			#  - Блог
	'sales'		=>	22			#  - Новинки и акции
);

$urlsStaticPages = array(		# статичные страницы
	'about/',
	'company/',
	'help/',
	'help/delivery/',
	'include/licenses_detail.php',
	'info/',
	'info/faq/',
	'contacts/',
	'catalog/',
	'news/',
	'sale/',
	'services/',
	'store/'
);

#------------------------------------------------------------------------------------------------------------

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Iblock, Sotbit\Seometa\SeometaUrlTable;
CModule::IncludeModule('iblock');
$total_prod_count = 0;
$sectionArr = getSectionList($iblock_id_catalog);
$productArr = getProductList($iblock_id_catalog);

if($total_prod_count % $count_per_page != 0) {
	$page_count = intdiv($total_prod_count, $count_per_page) + 1;
} else {
	$page_count = intdiv($total_prod_count, $count_per_page);
}

$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https' ;
$domainName = $protocol.'://'.$_SERVER['SERVER_NAME'];

#------------------------------------------------------------------------------------------------------------

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';

if($sitemap_mod == 0) {
	if (empty($_GET)) {
		echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=pages</loc></sitemap>';
		echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=categories</loc></sitemap>';
		for ($i = 1; $i <= $page_count; $i++) {
			echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=products-part-'.$i.'</loc></sitemap>';
		}
		echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=news</loc></sitemap>';
		echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=services</loc></sitemap>';
		echo 	'<sitemap><loc>'.$domainName.'/sitemap.xml?type=sales</loc></sitemap>';
		echo '</sitemapindex>';
	} else {
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		if ($_GET['type'] == 'pages') {
			echo getStaticPageList($domainName, $urlsStaticPages);
		} elseif ($_GET['type'] == 'categories') {
			echo getXMLtags($sectionArr, $domainName);
		} elseif(strpos($_GET['type'], 'products-part-') !== false) {
			$page_num =  intval(str_replace('products-part-', '', $_GET['type']));
			echo getXMLtags($productArr, $domainName, $page_num);
		} elseif($_GET['type'] == 'news'){
			echo getOtherList($domainName, $iblock_id_other['news']);
		} elseif($_GET['type'] == 'services'){
			echo getOtherList($domainName, $iblock_id_other['services']);
		} elseif($_GET['type'] == 'sales'){
			echo getOtherList($domainName, $iblock_id_other['sales']);
		}
		echo '</urlset>';
	}
} elseif($sitemap_mod == 1) {
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	echo getStaticPageList($domainName, $urlsStaticPages);
	echo getXMLtags($sectionArr, $domainName);
	echo getXMLtags($productArr, $domainName);
	echo getOtherList($domainName, $iblock_id_other['news']);
	echo '</urlset>';
}
# -------------------------------------------------------------------------------------------------------------
# ---------- FUNCTIONS ----------------------------------------------------------------------------------------

function getStaticPageList($domainName, $urlsStaticPages) {
	$pages_list =	'<url>';
	$pages_list.= 		'<loc>'.$domainName.'/</loc>';
	$pages_list.= 		'<changefreq>monthly</changefreq>';
	$pages_list.= 		'<priority>1</priority>';
	$pages_list.= 		'<lastmod>'.getDateStaticPage().'</lastmod>';
	$pages_list.= 	'</url>';

	foreach ($urlsStaticPages as $value) {
		$pages_list.=	'<url>';
		$pages_list.= 		'<loc>'.$domainName.'/'.$value.'</loc>';
		$pages_list.= 		'<changefreq>monthly</changefreq>';
		$pages_list.= 		'<priority>0.9</priority>';
		$pages_list.= 		'<lastmod>'.getDateStaticPage($value).'</lastmod>';
		$pages_list.= 	'</url>';
	}

	return $pages_list;
}

function getSectionList($iblock_id_) {
	$arSelect = Array();
	$arFilter = Array('IBLOCK_ID'=>$iblock_id_, 'ACTIVE'=>'Y', 'GLOBAL_ACTIVE'=>'Y');
	$res = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);

	$i = 0;
	while($ob = $res->GetNext()) {
		$priority = 0.7;
		$changefreq = 'monthly';	#always, hourly, daily, weekly, monthly, yearly, never
		$output[$i] = array('page_url' => $ob['SECTION_PAGE_URL'], 'priority' => $priority, 'lastmod' => getDateFormat($ob['TIMESTAMP_X']), 'changefreq' => $changefreq);
		$i++;
	}
	return $output;
}

function getProductList($iblock_id_) {
	global $total_prod_count;
	$arSelect = Array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "TIMESTAMP_X");
	$arFilter = Array("IBLOCK_ID"=>$iblock_id_, "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS"=>"Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){
		$arrRes = $ob->GetFields();
		$otherSect = [];
		$id = $arrRes['ID'];
		$url = $arrRes['DETAIL_PAGE_URL'];
		$lastmod = $arrRes['TIMESTAMP_X'];
		$priority = 0.5;
		$changefreq = 'weekly';	#always, hourly, daily, weekly, monthly, yearly, never
		$output[$total_prod_count] = array('page_url' => $url, 'priority' => $priority, 'lastmod' => getDateFormat($arrRes['TIMESTAMP_X']), 'changefreq' => $changefreq);
		$total_prod_count++;
		#-------  Дубли страниц (товары которые учатсвуют в разных разделах) -----------
		# $db_old_groups = CIBlockElement::GetElementGroups($id, false);
		# while($ar_group = $db_old_groups->Fetch()) {
		# 	$url = $ar_group['LIST_PAGE_URL'].$ar_group['CODE']."/".$arrRes['CODE'].'.html';
		# 	if(!in_array($url, $otherSect)){
		# 		$output[] = array('page_url' => $url, 'priority' => $priority, 'lastmod' => getDateFormat($arrRes['TIMESTAMP_X']), 'changefreq' => $changefreq);
		# 		$otherSect[] = $url;
		# 	}
		# }
	}
	return $output;
}

function getXMLtags($array_, $link_, $page_num_='') {
	global $count_per_page;

	$max_id = count($array_)-1;
	$start_id = $count_per_page*$page_num_ - $count_per_page;
	$end_id = ($count_per_page*$page_num_ < $max_id) ? ($count_per_page*$page_num_ - 1) : $max_id;

	if(empty($page_num_)){$start_id = 0; $end_id = $max_id; }

	for ($i = $start_id; $i <= $end_id; $i++) {
		$output .=	'<url>';
		$output .=		'<loc>'.$link_.$array_[$i]['page_url'].'</loc>';
		$output .=		'<changefreq>'.$array_[$i]['changefreq'].'</changefreq>';
		$output .=		'<priority>'.$array_[$i]['priority'].'</priority>';
		$output .=		'<lastmod>'.$array_[$i]['lastmod'].'</lastmod>';
		$output .=	'</url>';
	}

	return $output;
}

function getOtherList($link, $iblock_id_) {
	$arSelect = Array("DETAIL_PAGE_URL", "TIMESTAMP_X");
	$arFilter = Array("IBLOCK_ID" => $iblock_id_, "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
	while($ob = $res->GetNextElement()){
		$priority = 0.5;
		$changefreq = 'weekly';	#always, hourly, daily, weekly, monthly, yearly, never
		$output .=	'<url>';
		$output .=		'<loc>'.$link.$ob->GetFields()['DETAIL_PAGE_URL'].'</loc>';
		$output .=		'<changefreq>'.$changefreq.'</changefreq>';
		$output .=		'<priority>'.$priority.'</priority>';
		$output .=		'<lastmod>'.getDateFormat($ob->GetFields()['TIMESTAMP_X']).'</lastmod>';
		$output .=	'</url>';
	}
	return $output;
}

function getSeometaList($link) {
	$res=SeometaUrlTable::GetList(Array('select' => array('NEW_URL')));
	while($ob = $res->Fetch()){
		$output .= '<url><loc>'.$link.$ob['NEW_URL'].'</loc></url>';
	}
	return $output;
}

function checkSd($hostname) {
	$parts = explode('.',$hostname);
	if (count($parts) >= 3) {
		return true;
	} else {
		return false;
	}
}

function getDateFormat($date_) {
	$date_=strtotime($date_);			# конвертация строки в дату (в секундах)
	$date_ = FormatDate("c", $date_);	# перевод даты в формат yyyy-mm-ddTH:M:S+04:00
	return $date_;
}

function getDateStaticPage($alias='') {
	if(empty($alias)){
		return FormatDate("c", filemtime('index.php'));
	} elseif(strpos($alias, '.php') !== false || strpos($alias, '.html') !== false) {
		return FormatDate("c", filemtime($alias));
	} else {
		return FormatDate("c", filemtime($alias.'index.php'));
	}
}

?>