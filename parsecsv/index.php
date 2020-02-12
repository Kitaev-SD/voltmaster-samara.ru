<?
##Настройки отключены, потому что изменение php.ini через скрипт часто рушит сервер. Вместо этого в .htaccess проставлено php_value memory_limit 1024M
//ini_set('memory_limit', '256M');
//ini_set('max_execution_time', 300);
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);


?>
<? require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$APPLICATION->SetTitle("Capybara ImportCSV 0.01");
include "parsecsv.php"; //Сюда вынесены общие функции
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php'); ?>

<form method="POST" enctype="multipart/form-data">
    <fieldset>
    <input type="file" name="testfile">
    <p>Пожалуйста, выберите файл в формате .csv для актуализации дополнительного описания разделов</p>
        <p>ВАЖНО! <br>
            <br> В загружаемом файле должно быть строго:
            <br> 1. Три колонки - код, название, описание, разделенные точкой с запятой - ";".
            <br> 2. Строки должны быть в единой кодировке (windows-1251) без нечитаемых символов, иначе они добавлены не будут.
            <br> 3. Файл должен быть не более 3 МБ.
        </p>
    <input type="submit" name="upload" value="Загрузить">
    </fieldset>
</form>
    <p><br>
        <span style='background-color: red'><br><i>Версия 0.01</i></span>
    </p>

<pre>
<?
CModule::IncludeModule("iblock");
#$ourpath='/home/bitrix/www/parsecsv/'; ##Путь к рабочей папке парсера на сервере
$ourpath='/var/www/www-root/data/www/voltmaster-samara.ru.capyba.ru/parsecsv/';
if (isset($_POST["upload"]))
{
   $table_path=loadFile(); //Загружаем файл
    if($table_path)
    {
        rename($table_path, $ourpath.'upload.csv');
        //Все файлы с загруженными данными именуются одинаково, чтобы не забивать место и не хранить старые выгрузки
        $table = convertCSV($ourpath.'upload.csv');
		tableParse($table);
        ##Если нужно увидеть результат загрузки CSV
         #      print_r($table);
    }
}

if($_GET['step'])
{
	tableParse(convertCSV($ourpath.'upload.csv'), $_GET['step']);
}

?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>