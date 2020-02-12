<?php

class seoparsercsvParser {

    function csvToArray($fileName)
    {
        $csv = array_map('str_getcsv', file($fileName));
        return $csv;
    }

    function convertCSV($filePath)
    {

        $importer = new seoparsercsvGet($filePath, false, ";");
        $data = $importer->get();

        $data2 = array();

        foreach ($data as $k=>$item)
        {
            $norm_name = iconv('windows-1251', 'UTF-8', $item[1]);
            $norm_descr = iconv('windows-1251', 'UTF-8', $item[2]);

            if($norm_descr) {
                $data2[] = array ($norm_name, $norm_descr);
            }


        }
        return $data2;
    }

    /*
     *
     * Загрузка файла через форму.
     * Закоментированы отлаочные функции, а также реализация работы с файлом из временной папки, если прав на нужную нет.
     *
     */
    function loadFile($ourpath)
    {
        $newpath=$ourpath;
//    $newpath='/var/www/www-root/data/www/raskat97.ru.capyba.ru/parsecsv/';
        $path_info = pathinfo($newpath.($_FILES["testfile"]["name"])); //Задаем путь
        if ($_FILES['testfile']['size'] > 3500000) {
            echo('Размер файла не соответствует требованиям');
        }
        if (is_file($newpath . $_FILES["testfile"]["name"])) //Есть ли уже файл с таким именем
        {
            echo "Извините, файл с таким именем уже существует";
        }
        elseif ($path_info["extension"] === "csv") //Проверка расширения файла
        {
            if (move_uploaded_file($_FILES["testfile"]["tmp_name"], $newpath . $_FILES["testfile"]["name"])) {
                return $table_path = $newpath . ($_FILES["testfile"]["name"]);
            } else { var_dump($_FILES);
##Данное поле выдается в т.ч. если проблемы с правами доступа к папке и файлам.
                echo "<div style='background-color: red'> Ошибка при сохранении файла - нет доступа к папке, необходимо изменить настройки сервера.
            Пожалуйста, обратитесь к системному администратору или поддержке хостинга. </div>";
//            return $table_path = $_FILES["testfile"]["tmp_name"];
            }
        }
        else
        {
            echo "Извините, не обнаружен файл с расширением CSV";
        }
    }

    function tableParse($array, $k = 0) {
        if (!is_array($array)) {
            return false;
        }
        for ($i = $k; $i < ($k + 20); $i++) {
            if(strlen($array[$i][0])>2) {
                seoparsercsvSave::updateDescr($array[$i][0],$array[$i][1]);
            } else {
                echo "Загрузка закончена";
                
                ##@ToDo Вынести парсер в отдельное место
                $path = COption::GetOptionString('capybara.seoparsercsv', 'capybara_parser_PATH');
                file_put_contents($path.'/logParser.txt', "Успешно произведена выгрузка описаний в разделы");

                header("refresh: 10; URL=seoparsercsv_admin.php");
                die();
            }
        }
        $n = $i + 1;
        echo "Продолжается выполнение загрузки. Не закрывайте страницу.";
        header("refresh: 1; URL=seoparsercsv_admin.php?step=" . $n . "");
        die();
    }
}