<?php
##Чтение и подгрузка CSV сразу в массив обратно, если разделители дефолтные
function csvToArray($fileName)
{
    $csv = array_map('str_getcsv', file($fileName));
    return $csv;
}

##А это - если не дефолтные. Сейчас используется этот класс. Взято с php.net.
class CsvImporter
{
    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;

    //--------------------------------------------------------------------
    function __construct($file_name, $parse_header=false, $delimiter="\t", $length=30000000)
    {
        $this->fp = fopen($file_name, "r");
        $this->parse_header = $parse_header;
        $this->delimiter = $delimiter;
        $this->length = $length;


        if ($this->parse_header)
        {
           $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }

    }
    //--------------------------------------------------------------------
    function __destruct()
    {
        if ($this->fp)
        {
            fclose($this->fp);
        }
    }
    //--------------------------------------------------------------------
    function get($max_lines=0)
    {
        //if $max_lines is set to 0, then get all the data

        $data = array();

        if ($max_lines > 0)
            $line_count = 0;
        else
            $line_count = -1; // so loop limit is ignored

        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE)
        {
            if ($this->parse_header)
            {
                foreach ($this->header as $i => $heading_i)
                {
                    $row_new[$heading_i] = $row[$i];
                }
                $data[] = $row_new;
            }
            else
            {
                $data[] = $row;
            }

            if ($max_lines > 0)
                $line_count++;
        }
        return $data;
    }
    //--------------------------------------------------------------------

}

/*
 * Примеры использования класса для работы с малыми и большими файлами.
 *
Sample usage for small files:-
-------------------------------------
<?php
$importer = new CsvImporter("small.txt",true);
$data = $importer->get();
print_r($data);
?>

Sample usage for large files:-
-------------------------------------
<?php
$importer = new CsvImporter("large.txt",true);
while($data = $importer->get(2000))
{
    print_r($data);
}
*/

/*
 * Реализация работы с классом преобразования в CSV.
 */
function convertCSV($filePath)
{

    $importer = new CsvImporter($filePath,false, ";");
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
function loadFile()
{
    global $ourpath;
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
        if (move_uploaded_file($_FILES["testfile"]["tmp_name"], $newpath . $_FILES["testfile"]["name"]))
        {
            return $table_path = $newpath . ($_FILES["testfile"]["name"]);
        }
        else
        {
##Данное поле выдается в т.ч. если проблемы с правами доступа к папке и файлам. Однако ни разу таких проблем замечено не было.
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

function updateDescr($name, $descr) {
	$arFilter = Array("IBLOCK_ID" => 28, "NAME" => $name);
    $res = CIBlockSection::GetList(Array(), $arFilter);
	
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
		$ID = $arFields["ID"];

		$bs = new CIBlockSection;
		$arFields2 = Array(
			"UF_SECTADDDESCR" => $descr
		  );

		$result = $bs->Update($ID, $arFields2);

	}
}

function tableParse($array, $k = 0) {
	if (!is_array($array)) {
		return false;
	}
	for ($i = $k; $i < ($k + 20); $i++) {
		if(strlen($array[$i][0])>2) {
			updateDescr($array[$i][0],$array[$i][1]);
		} else {
			echo "Загрузка закончена";
			header("refresh: 10; URL=capybaracsv.php");
			die();
		}
	}
	$n = $i + 1;
	echo "Продолжается выполнение загрузки. Не закрывайте страницу.";
    header("refresh: 1; URL=capybaracsv.php?step=" . $n . "");
    die();
}
?>