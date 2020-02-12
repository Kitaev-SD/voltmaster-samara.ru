<?php
// подключим все необходимые файлы:
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php'); // первый общий пролог

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/capybara.seoparsercsv/include.php'); // инициализация модуля
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/capybara.seoparsercsv/prolog.php'); // пролог модуля

// подключим языковой файл
IncludeModuleLangFile(__FILE__);

// получим права доступа текущего пользователя на модуль
$POST_RIGHT = $APPLICATION->GetGroupRight('capybara.seoparsercsv');
// если нет прав - отправим к форме авторизации с сообщением об ошибке
if ($POST_RIGHT == 'D') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

$aTabs = array(array('DIV' => 'edit1', 'TAB' => 'Обработка'));
$tabControl = new CAdminTabControl('tabControl', $aTabs);

$APPLICATION->SetTitle('Capybara ImportCSV 1.02');

// здесь будет вся серверная обработка и подготовка данных
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php'); // второй общий пролог?>

<?  echo bitrix_sessid_post();

    $tabControl->Begin();


    $tabControl->BeginNextTab(); ?>

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
        <span style='background-color: red'><br><i>Версия 1.02</i></span>
    </p>
<?
CModule::IncludeModule("iblock");
##Путь к рабочей папке парсера на сервере
$ourpath=COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_PATH');
$seoparsercsvParser = new seoparsercsvParser;
if (isset($_POST["upload"]))
{

   $table_path=$seoparsercsvParser->loadFile($ourpath); //Загружаем файл
    if($table_path)
    {
        rename($table_path, $ourpath.'upload.csv');
        //Все файлы с загруженными данными именуются одинаково, чтобы не забивать место и не хранить старые выгрузки

        $table = $seoparsercsvParser->convertCSV($ourpath.'upload.csv');
        $seoparsercsvParser->tableParse($table);
        ##Если нужно увидеть результат загрузки CSV
         #      print_r($table);
    }
}

if($_GET['step'])
{
    $seoparsercsvParser->tableParse($seoparsercsvParser->convertCSV($ourpath.'upload.csv'), $_GET['step']);
}

?>
<?  $tabControl->End(); ?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>