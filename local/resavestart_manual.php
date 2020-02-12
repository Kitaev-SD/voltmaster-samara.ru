<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
CModule::IncludeModule("iblock"); ?>
<?
if (!isset($_GET['step'])) {
	$_GET['step'] = 1;
}
$count = CResaveProduct::resaveProduct($_GET['step']);
if ($count === false) {
	echo "<span style='color:green;'>Обновление цен произведено успешно. Можете закрыть страницу</span>";
} else {
	echo "<span style='color:red;'>Продолжается обновление цен. Пожалуйста, подождите. Обработано еще $count товаров</span>";
	$next_page = $_GET['step'] + 1;
	header("refresh: 1; URL=/local/resavestart_manual.php?step=" . $next_page . "");
	#header("Location: http://voltmaster-samara.ru.capyba.ru/local/resavestart.php?step=" . $next_page . "");
	die();
} 