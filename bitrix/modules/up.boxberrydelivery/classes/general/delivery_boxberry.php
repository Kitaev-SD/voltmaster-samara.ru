<?php 
CModule::IncludeModule("sale");
IncludeModuleLangFile(__FILE__);
class CDeliveryBoxberry
{
    public static $api;
    public static $selPVZ = NULL;
    public static $isActive = true;
    public static $address_field;
    public static $widget=array('key','settings');
    public static $possible_delivery = array();
    const MIN_WEIGHT = 5;
    protected static $region_bitrix_name;
    protected static $city_bitrix_name;
    protected static $city_widget_name;
    protected static $kd_available;
    protected static $settings;
    protected static $module_id='up.boxberrydelivery';
	
    public static function Init()
    {
		CModule::IncludeModule(self::$module_id);
		if (!CBoxberry::init_api()) return array("ERROR" => GetMessage('WRONG_API_CONNECT'));
		self::$widget = CBoxberry::method_exec('GetKeyIntegration'); 
		self::$widget['settings'] = CBoxberry::method_exec('WidgetSettings',NULL,TRUE); 
		$GLOBALS['bxb_settings'] = $settings;
		$GLOBALS['key'] = self::$widget['key'];
		$GLOBALS['widget_settings'] = self::$widget['settings'];
		return array(
          "SID" => "boxberry", 
          "NAME" => GetMessage('DELIVERY_NAME'),
          "DESCRIPTION" => "",
          "DESCRIPTION_INNER" => GetMessage('DESCRIPTION_INNER'),
          "BASE_CURRENCY" => COption::GetOptionString("sale", "default_currency", "RUB"),
		  "HANDLER" => __FILE__,
          "DBGETSETTINGS" => array("CDeliveryBoxberry", "GetSettings"),
          "DBSETSETTINGS" => array("CDeliveryBoxberry", "SetSettings"),
          "GETCONFIG" => array("CDeliveryBoxberry", "GetConfig"),
          
          "COMPABILITY" => array("CDeliveryBoxberry", "Compability"),      
          "CALCULATOR" => array("CDeliveryBoxberry", "Calculate"), 
		
			'PROFILES' => array(
				'PVZ' => array(
					'TITLE' => GetMessage('BOXBERRY_PVZ'),
					'DESCRIPTION' => "",
					),
				'KD' => array(
					'TITLE' => GetMessage('BOXBERRY_KD'),
					'DESCRIPTION' => "",
					),
				'PVZ_COD' => array(
					'TITLE' => GetMessage('BOXBERRY_PVZ_COD'),
					'DESCRIPTION' => "",
					),
				'KD_COD' => array(
					'TITLE' => GetMessage('BOXBERRY_KD_COD'),
					'DESCRIPTION' => "",
					)
				)
		);
    }
	public static function WidgetInit() {
	global $APPLICATION;
		if (strpos($APPLICATION->GetCurPage(), 'bitrix/admin') === false || !ADMIN_SECTION)
		{
			$GLOBALS['APPLICATION']->IncludeComponent("bberry:boxberry.widget", "", array(),false);
		}		
	}
	public static function GetConfig()
	{    
	   $arConfig = array(
			"CONFIG" => array(
				"default" => array(),
			)
		); 
		return $arConfig; 
	}
  
   
    public static function SetSettings($arSettings)
    {
		foreach ($arSettings as $key => $value) 
        {
            if (strlen($value) > 0)
				$arSettings[$key] = ($value);
			else
				unset($arSettings[$key]);
        }
    
        return serialize($arSettings);
    }  
	
    public static function GetSettings($strSettings) 
    { 
		$settings = unserialize($strSettings); 
		if (empty($settings)) return;
			return $settings; 
	} 
	private function plural_form($number, $after) 
	{
      $cases = array (2, 0, 1, 1, 1, 2);
      return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ].' ';
    }
	private function ceilPrice ($p,$d=1)
	{
        return ceil($p/$d) * $d;
    }
	private function upString($name)
	{
		return str_replace(GetMessage('yo'), GetMessage('ye'), (LANG_CHARSET == 'windows-1251' ? mb_strtoupper($name,'CP1251') : mb_strtoupper($name)));
	}
	
	private function getFullDimensions($arOrder, $arConfig)
	{
		$weight_default = COption::GetOptionString(self::$module_id, 'BB_WEIGHT');
		if (count($arOrder["ITEMS"]) == 1 && $arOrder["ITEMS"][0]["QUANTITY"]==1){
				$multiplier = 10;
				$full_package["WIDTH"] =  $arOrder["ITEMS"][0]["DIMENSIONS"]["WIDTH"] / $multiplier;
				$full_package["HEIGHT"] = $arOrder["ITEMS"][0]["DIMENSIONS"]["HEIGHT"] / $multiplier;
				$full_package["LENGTH"] = $arOrder["ITEMS"][0]["DIMENSIONS"]["LENGTH"] / $multiplier;
				$full_package["WEIGHT"] = ($arOrder["ITEMS"][0]['WEIGHT'] == '0.00' || (float)$arOrder["ITEMS"][0]['WEIGHT'] < (float)self::MIN_WEIGHT ? $weight_default : $arOrder["ITEMS"][0]['WEIGHT']);
		} else {
				$full_package["WIDTH"] = 0;
				$full_package["HEIGHT"] = 0;
				$full_package["LENGTH"] = 0;
				$full_package["WEIGHT"] = 0;
				
				foreach ($arOrder["ITEMS"] as $item){
					$full_package["WEIGHT"] += $item["QUANTITY"] * ($item['WEIGHT'] == '0.00' || $item['WEIGHT'] < (float)self::MIN_WEIGHT  ? $weight_default : $item['WEIGHT'] );
				}
		}
		return $full_package;
	}
    public static function GetPointCode($city_code)
	{
        if (LANG_CHARSET == 'windows-1251'){
            $city_code = iconv('CP1251','UTF-8',$city_code);
        }
        if ($possible_boxberry_points = CBoxberry::method_exec('ListPoints', array('CityCode='.$city_code.'&prepaid=1'))){
            return $possible_boxberry_points[0]['Code'];
        }else{
            return false;
        }
    }
	
	public static function GetBitrixRegionNames($location)
	{
		self::$city_bitrix_name = false;
		self::$region_bitrix_name = false;

		if (!empty($location)){
			  	$parameters = array();
        		$parameters['filter']['=CODE'] = $location;
        		$parameters['filter']['NAME.LANGUAGE_ID'] = "ru";
        		$parameters['limit'] = 1;
        		$parameters['select'] = array('*','LNAME' => 'NAME.NAME');

				$arVal = Bitrix\Sale\Location\LocationTable::getList( $parameters )->fetch();
				$fullCityName = Bitrix\Sale\Location\Admin\LocationHelper::getLocationPathDisplay( $location );

				if ( $arVal && strlen( $arVal[ 'LNAME' ] ) > 0 )
				{
					$val = $arVal[ 'LNAME' ];
					self::$city_bitrix_name = self::upString($val);
					self::$region_bitrix_name = self::upString($fullCityName);
					$city_widget_name = explode (",", self::$region_bitrix_name);
					$city_widget_name = array_reverse ($city_widget_name);
                    if (!isset($city_widget_name[1]) || empty($city_widget_name[1])) {
                        self::$city_widget_name = $city_widget_name[0];
                    }elseif (@strpos($city_widget_name[0], GetMessage("BOXBERRY_MOSCOW"))!==false) {
                        self::$city_widget_name = $city_widget_name[0];
                    }else{
                        self::$city_widget_name = $city_widget_name[0] . ' ' . $city_widget_name[1];
                    }

				}
				
		}
	}

    public static function GetCityCode()
    {
        self::$kd_available = false;
        $regionBitrixName = str_replace('¸', 'å', self::$region_bitrix_name);
        $cityBitrixName = str_replace('¸', 'å', self::$city_bitrix_name);
        $boxberry_list = Cboxberry::method_exec('ListCitiesFull');

        foreach((array)$boxberry_list as $boxberry_cities) {
            $city_name = self::upString($boxberry_cities['Name']);
            $region_name = self::upString($boxberry_cities['Region']);
            $district_name = self::upString($boxberry_cities['District']);
            $prefix = self::upString($boxberry_cities['Prefix']);
            $check_kd = $boxberry_cities['CourierDelivery'];
            $check_pvz = $boxberry_cities['PickupPoint'];
            $prefix_fl = substr($prefix, 0, 1);
            $correct_region = explode(' ', $region_name);
            $short_boxberry_city_name = explode(' ', $city_name);
            $short_bitrix_city_name = explode(' ', $cityBitrixName);
            $short_bitrix_city_name_fl = substr($short_bitrix_city_name[1], 0, 1);

            if (((empty($short_bitrix_city_name[1])) && (strpos($short_boxberry_city_name[0], $short_bitrix_city_name[0])!==false)) || (($short_boxberry_city_name[0] . ' ' . $short_boxberry_city_name[1]) == ($short_bitrix_city_name[0] . ' ' . $short_bitrix_city_name[1])) || ((strpos($short_boxberry_city_name[0], $short_bitrix_city_name[0])!==false) && (@strpos($regionBitrixName, $district_name) !==false)) || ((strpos($short_boxberry_city_name[0], $short_bitrix_city_name[0])!==false) && (@strpos($short_bitrix_city_name_fl, $prefix_fl)!==false))) {
                self::$kd_available = $check_kd;
                if (strpos($regionBitrixName, $correct_region[0]) !== false) {
                    if (strpos($cityBitrixName, $city_name) !== false) {
                        if ($check_pvz == true) {
                            return $boxberry_cities["Code"];
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function GetZipKD()
    {
        if (self::$kd_available == true) {
            $regionBitrixName = str_replace('¸', 'å', self::$region_bitrix_name);
            $cityBitrixName = str_replace('¸', 'å', self::$city_bitrix_name);
            $possible_zip = Cboxberry::method_exec('ListZips');

            foreach ((array)$possible_zip as $boxberry_zips) {
                $city_name = self::upString($boxberry_zips['City']);
                $region_name = self::upString($boxberry_zips['Area']);
                $correct_region_kd = explode(' ', $region_name);

                if ((@strpos($regionBitrixName, $correct_region_kd[0]) !== false) || (@strpos($correct_region_kd[0], GetMessage("BOXBERRY_LISTZIPS_AREA_FIX")) !== false)) {
                    if (strpos($cityBitrixName, $city_name) !== false) {
                        return $boxberry_zips["Zip"];
                        }
                    }
                }
            }
        return false;
    }

    public static function Compability($arOrder, $arConfig)
    {
		$api_url = COption::GetOptionString(self::$module_id, 'API_URL');
		$api_token = COption::GetOptionString(self::$module_id, 'API_TOKEN');
		self::GetBitrixRegionNames($arOrder['LOCATION_TO']);
		$union_ex = explode (",", self::$region_bitrix_name);
		$union_exc = (@strpos($union_ex[0], GetMessage("BOXBERRY_UNION_EXC"))!==false);

		if (($location_to = self::GetCityCode()) && ($union_exc == true)){
			if (!in_array($location_to ,$GLOBALS['widget_settings']["result"][1]["CityCode"])){
				$arReturn[] = 'PVZ';
				$arReturn[] = 'PVZ_COD';
			}
		}else{
            if ($location_to = self::GetCityCode()) {
                if (!in_array($location_to, $GLOBALS['widget_settings']["result"][1]["CityCode"])) {
                    $arReturn[] = 'PVZ_COD';
                }
            }
        }
		if (!in_array($location_to ,(array)$GLOBALS['widget_settings']["result"][1]["CityCode"])){
			if ((self::GetZipKD()) && ($union_exc == true)){
					$arReturn[] = 'KD';
					$arReturn[] = 'KD_COD';
            }
		}else{
            if (!in_array($location_to ,(array)$GLOBALS['widget_settings']["result"][1]["CityCode"])){
                if (self::GetZipKD()){
                    $arReturn[] = 'KD_COD';
                }
            }
        }
		
        return $arReturn;
    }
	private static function setLink_params($arrParams=array())
	{
		$_SESSION['link_params'] = json_encode($arrParams);
	}
	public static function getLink_params()
	{
		if (isset($_SESSION['link_params']) && !empty($_SESSION['link_params'])){
			return $_SESSION['link_params'];
		}
		return false;
	}
	public static function Calculate($profile, $arConfig, $arOrder, $STEP, $TEMP = false)
    {
        self::GetBitrixRegionNames($arOrder['LOCATION_TO']);
        $cityCode = self::GetCityCode();

		$bxb_custom_link = COption::GetOptionString('up.boxberrydelivery', 'BB_CUSTOM_LINK');
		$bxb_button = COption::GetOptionString('up.boxberrydelivery', 'BB_BUTTON');
		$pvz_to = self::GetPointCode($cityCode); // 2BXB_PointCode to
        $zip_to = self::GetZipKD();

		$parcel_size = self::getFullDimensions($arOrder, $arConfig);
		$kd_surch = COption::GetOptionString(self::$module_id, 'BB_KD_SURCH');
		self::setLink_params(array(
				'callback_function'=>'delivery',
				'widget_key'=>$GLOBALS['key'],
				'custom_city'=>self::$city_widget_name ,
				'target_start'=>'',
				'ordersum'=>$arOrder["PRICE"],
				'weight'=>$parcel_size['WEIGHT'] ,
				'paysum'=>0 ,
				'height'=>$parcel_size['HEIGHT'] ,
				'width'=>$parcel_size['WIDTH'] ,
				'depth'=>$parcel_size['LENGTH']
			)
		);
		if ($profile == 'PVZ'){
				$arrParams=array(
					'target='. $pvz_to,
					'weight=' . $parcel_size['WEIGHT'],
					'height='. $parcel_size['HEIGHT'],
					'width='. $parcel_size['WIDTH'],
					'depth='. $parcel_size['LENGTH'],
					'ordersum=' . $arOrder['PRICE'], 
					'paysum=' . $arOrder['PRICE'], 
					'sucrh=1', 
					'version=2.2', 
					'cms=bitrix', 
					'url='.$_SERVER['SERVER_NAME'], 
				);
				$price_delivery = CBoxberry::method_exec('DeliveryCosts',$arrParams, TRUE);
				if (isset($price_delivery['price'])){
					$price = $price_delivery['price'];
				}else{
					return array(
						"RESULT" => "ERROR",
						"TEXT" => "",
					);
				}

			
			if ($GLOBALS['widget_settings']["result"][3]['hide_delivery_day']!=1){
				$period = $price_delivery['delivery_period'];
				$period = self::plural_form($period, array(GetMessage("DAY"),GetMessage("DAYS"),GetMessage("DAYSS")));
			}else{
				$period = NULL;
			}
            if (empty($bxb_custom_link)){
                $link_pvz = $bxb_button == 'Y' ? "<br/><br/><a class='bxbbutton' href=\"#\" onclick=\"boxberry.checkLocation(1);boxberry.open(delivery, '" . $GLOBALS['key'] . "' , '" . self::$city_widget_name . "' , '', '" . $arOrder["PRICE"] . "' , '" . $parcel_size['WEIGHT'] . "' ,'" . $arOrder["PRICE"] . "' ,'" . $parcel_size['HEIGHT'] . "','" . $parcel_size['WIDTH'] . "','" . $parcel_size['LENGTH'] . "' ); return false;\"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAYCAYAAAD6S912AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAE+SURBVHgBnVSBccMgDNR5Am9QRsgIjMIGZYN4g2QDpxN0BEagGzgbpBtQqSdiBQuC/Xc6G0m8XogDQEFKaUTzaAHtkVZEtBnNQi8w+bMgof+FTYKIzTuyS7HBKsqdIKfvqUZ2fpv0mj+JDkwZdILMQCcEaSwDuQULO8GDI7hS3VzZYFmJ09RzfFWJP981deJcU+tIhMoPWtDdSo3KJYKSe81tD7imid63zYIFHZr/h79mgDp+K/47NDBwgkG5YxG7VTZ/KT7zLIZEt8ZQjDhwusBeIZNDOcnDD3AAXPT/BkjnUlPZQTjnCUunO6KSWtyoE8HAQb+DcNmoU6ptXw+dLD91cyvJc1JUrpHM63+dROuXStyk9UW30NHKKM7mrJDl2AS9KFR4USiy7wp7kV5fm4coEOEomDQI0qk1LMIfknqE+j7lxtgAAAAASUVORK5CYII='><span>" . GetMessage("SELECT_BUTTON_TEXT") . "</span></a>" : "<br/><a href=\"#\" onclick=\"boxberry.checkLocation(1);boxberry.open(delivery, '" . $GLOBALS['key'] . "' , '" . self::$city_widget_name . "' , '', '" . $arOrder["PRICE"] . "' , '" . $parcel_size['WEIGHT'] . "' , '0' ,'" . $parcel_size['HEIGHT'] . "','" . $parcel_size['WIDTH'] . "','" . $parcel_size['LENGTH'] . "' ); return false;\">" . GetMessage("SELECT_LINK_TEXT") . "</a>";
            }
            return array(
                "RESULT" => "OK",
                "VALUE" => $price,
                "TRANSIT" => $period. $link_pvz
			);
            
        }elseif ($profile == 'PVZ_COD'){
			$arrParams=array(
					'target='. $pvz_to,
					'weight=' . $parcel_size['WEIGHT'],
					'height='. $parcel_size['HEIGHT'],
					'width='. $parcel_size['WIDTH'],
					'depth='. $parcel_size['LENGTH'],
					'ordersum=' . $arOrder['PRICE'], 
					'paysum=0',
					'sucrh=1', 
					'version=2.2', 
					'cms=bitrix', 
					'url='.$_SERVER['SERVER_NAME'], 
					
				);
				$price_delivery = CBoxberry::method_exec('DeliveryCosts',$arrParams, TRUE);
				if (isset($price_delivery['price'])){
					$price = $price_delivery['price'];
				}else{
					return array(
						"RESULT" => "ERROR",
						"TEXT" => "",						
					);
				}		
			
			if ($GLOBALS['widget_settings']["result"][3]['hide_delivery_day']!=1){
				$period = $price_delivery['delivery_period'];
				$period = self::plural_form($period, array(GetMessage("DAY"),GetMessage("DAYS"),GetMessage("DAYSS")));
			}else{
				$period = NULL;
			}
			if (empty($bxb_custom_link)){
                $link_pvz_cod = $bxb_button == 'Y' ? "<br/><br/><a class='bxbbutton' href=\"#\" onclick=\"boxberry.checkLocation(1);boxberry.open(delivery, '" . $GLOBALS['key'] . "' , '" . self::$city_widget_name . "' , '', '" . $arOrder["PRICE"] . "' , '" . $parcel_size['WEIGHT'] . "' , '0' ,'" . $parcel_size['HEIGHT'] . "','" . $parcel_size['WIDTH'] . "','" . $parcel_size['LENGTH'] . "' ); return false;\"><img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAYCAYAAAD6S912AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAE+SURBVHgBnVSBccMgDNR5Am9QRsgIjMIGZYN4g2QDpxN0BEagGzgbpBtQqSdiBQuC/Xc6G0m8XogDQEFKaUTzaAHtkVZEtBnNQi8w+bMgof+FTYKIzTuyS7HBKsqdIKfvqUZ2fpv0mj+JDkwZdILMQCcEaSwDuQULO8GDI7hS3VzZYFmJ09RzfFWJP981deJcU+tIhMoPWtDdSo3KJYKSe81tD7imid63zYIFHZr/h79mgDp+K/47NDBwgkG5YxG7VTZ/KT7zLIZEt8ZQjDhwusBeIZNDOcnDD3AAXPT/BkjnUlPZQTjnCUunO6KSWtyoE8HAQb+DcNmoU6ptXw+dLD91cyvJc1JUrpHM63+dROuXStyk9UW30NHKKM7mrJDl2AS9KFR4USiy7wp7kV5fm4coEOEomDQI0qk1LMIfknqE+j7lxtgAAAAASUVORK5CYII='><span>" . GetMessage("SELECT_BUTTON_TEXT") . "</span></a>" : "<br/><a href=\"#\" onclick=\"boxberry.checkLocation(1);boxberry.open(delivery, '" . $GLOBALS['key'] . "' , '" . self::$city_widget_name . "' , '', '" . $arOrder["PRICE"] . "' , '" . $parcel_size['WEIGHT'] . "' , '0' ,'" . $parcel_size['HEIGHT'] . "','" . $parcel_size['WIDTH'] . "','" . $parcel_size['LENGTH'] . "' ); return false;\">" . GetMessage("SELECT_LINK_TEXT") . "</a>";
			}
            
			return array(
                "RESULT" => "OK",
                "VALUE" => $price,
                "TRANSIT" => $period. $link_pvz_cod
			);
            
        }elseif ($profile == 'KD'){
            $arrParams=array(
                    'target='. $pvz_to,
                    'weight=' . $parcel_size['WEIGHT'],
                    'height='. $parcel_size['HEIGHT'],
					'width='. $parcel_size['WIDTH'],
					'depth='. $parcel_size['LENGTH'],
                    'ordersum=' . $arOrder['PRICE'], 
					'paysum=' . $arOrder['PRICE'], 
					($kd_surch=="Y" ? "" : 'sucrh=1'), 
					'version=2.2', 
					'cms=bitrix', 
					'url='.$_SERVER['SERVER_NAME'], 
					'zip='. $zip_to
                );
            
            $price_delivery = CBoxberry::method_exec('DeliveryCosts',$arrParams, TRUE);
			if (isset($price_delivery['price'])){
				$price = $price_delivery['price'];
			}else{
				return array(
					"RESULT" => "ERROR",
					"TEXT" => "",						
				);
			}
			if ($GLOBALS['widget_settings']["result"][3]['hide_delivery_day']!=1){
				$period = $price_delivery['delivery_period'];
				$period = self::plural_form($period, array(GetMessage("DAY"),GetMessage("DAYS"),GetMessage("DAYSS")));
			}else{
				$period = NULL;
			}
		    return array(
                  "RESULT" => "OK",
                  "VALUE" => $price,
                  "TRANSIT" => $period
            );
        }elseif ($profile == 'KD_COD'){
            
            $arrParams=array(
                    'target='. $pvz_to,
					'height='. $parcel_size['HEIGHT'],
					'width='. $parcel_size['WIDTH'],
					'depth='. $parcel_size['LENGTH'],
                    'weight=' . $parcel_size['WEIGHT'],
                    'ordersum=' . $arOrder['PRICE'], 
					($kd_surch=="Y" ? "" : 'sucrh=1'), 
					'version=2.2', 
					'cms=bitrix', 
					'url='.$_SERVER['SERVER_NAME'],
                    'zip='. $zip_to
                );
            
            $price_delivery = CBoxberry::method_exec('DeliveryCosts',$arrParams, TRUE);
            if (isset($price_delivery['price'])){
				$price = $price_delivery['price'];
			}else{
				return array(
					"RESULT" => "ERROR",
					"TEXT" => "",						
				);
			}
			if ($GLOBALS['widget_settings']["result"][3]['hide_delivery_day']!=1){
				$period = $price_delivery['delivery_period'];
				$period = self::plural_form($period, array(GetMessage("DAY"),GetMessage("DAYS"),GetMessage("DAYSS")));
			}else{
				$period = NULL;
			}
			
            return array(
                  "RESULT" => "OK",
                  "VALUE" => $price,
                  "TRANSIT" => $period
            );
			
        }
    }
	function orderCreate($id,$arOrder)
    {
		if (COption::GetOptionString(self::$module_id, 'BB_LOG') == 'Y'){
				CBoxberry::$log->save($id);
				CBoxberry::$log->save($arOrder);
				CBoxberry::$log->save($_SESSION);
		}
		if (!function_exists('findParentBXB'))
		{
			function findParentBXB($profiles){
				if ($profiles['CODE']=='boxberry'){
					return $profiles['ID'];
				}
			}
		}

		$allDeliverys = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();
		$parent = array_filter ($allDeliverys, 'findParentBXB');
		$boxberry_profiles=array();

		foreach ($allDeliverys as $profile){
			foreach ($parent as $key=>$value){
				if($profile["PARENT_ID"]==$key){
					$boxberry_profiles[] = $profile["ID"];
				}
			}
		}

		if (!empty($id) && in_array($arOrder['DELIVERY_ID'],$boxberry_profiles))
		{
			$result = CBoxberry::MakePropsArray($arOrder);
			$arFields = array(
				'ORDER_ID' 			=> $id,
				'DATE_CHANGE' 		=> date('d.m.Y H:i:s'),
				'LID' 				=> $result['LID'],
				'PVZ_CODE' 			=> (isset($_SESSION['selPVZ']) && !empty($_SESSION['selPVZ']) ? $_SESSION['selPVZ'] : "" ),
				'STATUS'			=> '0',
				'STATUS_TEXT' 		=> 'NEW',
				'STATUS_DATE' 		=> date('d.m.Y H:i:s'),
			);

			CBoxberryOrder::Add($arFields);
		}
		return true;
	}

}

AddEventHandler("sale", "OnSaleComponentOrderOneStepDelivery", array('CDeliveryBoxberry', 'WidgetInit'));
AddEventHandler("sale", "onSaleDeliveryHandlersBuildList", array('CDeliveryBoxberry', 'Init'));
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", array('CDeliveryBoxberry', 'orderCreate')); 
AddEventHandler("sale", "OnOrderUpdate", array('CDeliveryBoxberry', 'orderCreate')); 
?>