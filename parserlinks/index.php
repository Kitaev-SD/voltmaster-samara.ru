<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("search");

ini_set("max_execution_time", 300);
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
 * Реализация работы с классом преобразования в CSV.
 */
function convertCSV($filePath)
{

    $importer = new CsvImporter($filePath,false, "@");
    $data = $importer->get();

    $data2 = array();

    foreach ($data as $k=>$item)
    {

			$data2[] = array ($item[1], $item[2]); 
			#$data2[] = array ($item[0], $item[1]); 
    }
    return $data2;
}

$file = 'allLinkRawWar2.csv';
require_once('logger.php');

$result = convertCSV($file);
$logger = new Logger('falseProduct4', 'falsesnew4.txt');
$logNormCsv = new Logger('', 'readyparsnew4.csv');
echo "<pre>";
foreach ($result as $key=>$value) {
	if ($key > 28000 && $key < 31000) {
	

		if(strlen($value[0]) > 1) {
			$res = findWithBitrixIB28($value[1]);

			#$res = findwithgetlist($value[0]);
			#$value[0] = str_replace('#', '', $value[0]);
			#$value[1] = str_replace('#', '', $value[1]);
			#$res = getsectaddr($value[1]);
			if($res && strlen($res)>1) {
				$result = 'redirectMatch 301 ^/'.$value[0].'$ '.$res;
				$logNormCsv->logRedirect($result);
				#var_dump($res);
			} else {
				$logger->log($value[0].'@'.$value[1]);
				#var_dump($value);
			}
		}
	}
} 
function getsectaddr($name) {
	$arFilter = array('IBLOCK_ID' => 28, '%NAME'=>$name);
	$rsSections = CIBlockSection::GetList(array(), $arFilter);
	while ($arSection = $rsSections->Fetch())
	{
		return '/catalog/'.$arSection['CODE'].'/';
	}
}
#getsectaddr('Аксессуары для домашней  химлаборатории');


function findwithgetlist($code) {
	$code2 = str_replace('-', '_', $code);
	$code3 = str_replace('products/', '', $code2);
	$arFilter = array('IBLOCK_ID' => 28, '%CODE'=>$code3);
	$res = CIBlockElement::GetList(array(), $arFilter);
	while($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();
		return $arFields['DETAIL_PAGE_URL'];
	}
	return false;
}
function findwithgetlistname($name) {
	$name_clear = explode('(', $name)[0];
	$arFilter = array('IBLOCK_ID' => 28, '%NAME'=>$name_clear);
	$res = CIBlockElement::GetList(array(), $arFilter);
	while($ob = $res->GetNextElement()){ 
		$arFields = $ob->GetFields();
		return $arFields['DETAIL_PAGE_URL'];
	}
	return false;
}
#var_dump(findwithgetlistname('ЯЯяТэн, духовка,1500 Вт'));
#var_dump(findwithgetlist('products/res-180k-1w-c2-33'));
function findWithBitrixIB28($name) {
	
	$filter = $name;
	$iblock = 28;

	$obSearch = new CSearch;
	$obSearch->SetOptions(array(//мы добавили еще этот параметр, чтобы не ругался на форматирование запроса
	   'ERROR_ON_EMPTY_STEM' => false,
	));
	$obSearch->Search(array(
	   'QUERY' => $filter,
	   'SITE_ID' => SITE_ID,
	   'MODULE_ID' => 'iblock',
	   'PARAM2' => $iblock
	));
	if (!$obSearch->selectedRowsCount()) {//и делаем резапрос, если не найдено с морфологией...
	   $obSearch->Search(array(
		  'QUERY' => $filter,
		  'SITE_ID' => SITE_ID,
		  'MODULE_ID' => 'iblock',
		  'PARAM2' => $iblock
	   ), array(), array('STEMMING' => false));//... уже с отключенной морфологией
	}  
	while ($row = $obSearch->fetch()) {  
		  if ($row['URL']) {
			  return $row['URL'];
		  }
	}
	return false;
}

#var_dump(findWithBitrixIB28('ЯЯяТэн, духовка,1500 Вт'));

