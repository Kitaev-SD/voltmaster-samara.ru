<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Парсер редиректов");
?>
<?
$filename = "upload/check_p1.txt";
$num = 0; //счётчик итераций
$fileRedirects = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/fileRedirectsAll.txt', 'a');
$fileNoRedirects = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/fileNoRedirectsAll.txt', 'a');

if (file_exists($filename) && is_readable ($filename)) {
    $lines = explode("\n", file_get_contents($filename));
    if (!empty($lines)) {
        foreach ($lines as $line) {
            if (!empty($line)) {
                $params = explode('*', $line); //разбиваем на адрес элемента и его имя
                if (!empty($params[0]) && !empty($params[1])) {
                    $arSelectElems = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL"); //нужно только наименование и детальная страница элемента
                    $arFilterElems = Array("IBLOCK_ID" => 28, "?NAME" => trim($params[1])); //проверим на содержание, так как в имени могут быть лишние невидимые символы, их убираем с помощью trim()
                    $resElems = CIBlockElement::GetList(Array(), $arFilterElems, false, Array(), $arSelectElems);
                    while($getElems = $resElems->GetNextElement()){ //аналог элемента найден
                        $arFields = $getElems->GetFields();
                        if(!empty($arFields)) {
                            if(trim($params[1]) == trim($arFields['NAME'])) {
                                if (trim($params[0]) != $arFields['DETAIL_PAGE_URL']) { //если URL-адреса отличаются, тогда запишем редирект
                                    $writeRedirect = 'Redirect 301 ' . trim($params[0]) . ' ' . $arFields['DETAIL_PAGE_URL'] . PHP_EOL;
                                    fwrite($fileRedirects, $writeRedirect);
                                    $num = 1;
                                }
                            }
                        }
                    }
                    if($num == 0){ //возможно, что данная строка - это раздел
                        $arFilterSect = Array("IBLOCK_ID" => 28, "NAME" => trim($params[1]));
                        $sectResult = CIBlockSection::GetList(Array(), $arFilterSect, true);
                        while($getSects = $sectResult->GetNext()) {
                            $writeRedirect = 'Redirect 301 ' . trim($params[0]) . ' ' . $getSects['SECTION_PAGE_URL'] . PHP_EOL;
                            fwrite($fileRedirects, $writeRedirect);
                            $num = 1;
                        }
                    }
                    if($num != 1){ //данная строка это ни товар, ни раздел, запишем в отдельный файл
                        $writeRedirect = $params[0].'*' .$params[1] . PHP_EOL;
                        fwrite($fileNoRedirects, $writeRedirect);
                    }
                    $num = 0;
                }
            }
        }
    }
    else echo "Check the filename, file doesn't exists!";
}
fclose($fileRedirects);
fclose($fileNoRedirects);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>