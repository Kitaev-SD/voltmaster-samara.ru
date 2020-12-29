<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2013 Bitrix
 */

use Bitrix\Main\Session\Legacy\HealerEarlySessionStart;

require_once(mb_substr(__FILE__, 0, mb_strlen(__FILE__) - mb_strlen("/include.php"))."/bx_root.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/start.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/virtual_io.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/virtual_file.php");

$application = \Bitrix\Main\Application::getInstance();
$application->initializeExtendedKernel(array(
	"get" => $_GET,
	"post" => $_POST,
	"files" => $_FILES,
	"cookie" => $_COOKIE,
	"server" => $_SERVER,
	"env" => $_ENV
));

//define global application object
$GLOBALS["APPLICATION"] = new CMain;

if(defined("SITE_ID"))
	define("LANG", SITE_ID);

if(defined("LANG"))
{
	if(defined("ADMIN_SECTION") && ADMIN_SECTION===true)
		$db_lang = CLangAdmin::GetByID(LANG);
	else
		$db_lang = CLang::GetByID(LANG);

	$arLang = $db_lang->Fetch();

	if(!$arLang)
	{
		throw new \Bitrix\Main\SystemException("Incorrect site: ".LANG.".");
	}
}
else
{
	$arLang = $GLOBALS["APPLICATION"]->GetLang();
	define("LANG", $arLang["LID"]);
}

if($arLang["CULTURE_ID"] == '')
{
	throw new \Bitrix\Main\SystemException("Culture not found, or there are no active sites or languages.");
}

$lang = $arLang["LID"];
if (!defined("SITE_ID"))
	define("SITE_ID", $arLang["LID"]);
define("SITE_DIR", $arLang["DIR"]);
define("SITE_SERVER_NAME", $arLang["SERVER_NAME"]);
define("SITE_CHARSET", $arLang["CHARSET"]);
define("FORMAT_DATE", $arLang["FORMAT_DATE"]);
define("FORMAT_DATETIME", $arLang["FORMAT_DATETIME"]);
define("LANG_DIR", $arLang["DIR"]);
define("LANG_CHARSET", $arLang["CHARSET"]);
define("LANG_ADMIN_LID", $arLang["LANGUAGE_ID"]);
define("LANGUAGE_ID", $arLang["LANGUAGE_ID"]);

$culture = \Bitrix\Main\Localization\CultureTable::getByPrimary($arLang["CULTURE_ID"], ["cache" => ["ttl" => CACHED_b_lang]])->fetchObject();

$context = $application->getContext();
$context->setLanguage(LANGUAGE_ID);
$context->setCulture($culture);

$request = $context->getRequest();
if (!$request->isAdminSection())
{
	$context->setSite(SITE_ID);
}

$application->start();

$GLOBALS["APPLICATION"]->reinitPath();

if (!defined("POST_FORM_ACTION_URI"))
{
	define("POST_FORM_ACTION_URI", htmlspecialcharsbx(GetRequestUri()));
}

$GLOBALS["MESS"] = array();
$GLOBALS["ALL_LANG_FILES"] = array();
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/tools.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/database.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/main.php");
IncludeModuleLangFile(__FILE__);

error_reporting(COption::GetOptionInt("main", "error_reporting", E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR|E_PARSE) & ~E_STRICT & ~E_DEPRECATED);

if(!defined("BX_COMP_MANAGED_CACHE") && COption::GetOptionString("main", "component_managed_cache_on", "Y") <> "N")
{
	define("BX_COMP_MANAGED_CACHE", true);
}

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/filter_tools.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/ajax_tools.php");

/*ZDUyZmZNDhjZDA2MGQ5YmI3MjczNzg5ODI4ZWIyNTEyMzgzNjE=*/$GLOBALS['_____1919905219']= array(base64_decode(''.'R2V0TW9kdW'.'x'.'lRXZ'.'lb'.'nRz'),base64_decode('RXhl'.'Y3V0ZU1vZHVsZUV'.'2'.'Z'.'W5'.'0RXg='));$GLOBALS['____126323757']= array(base64_decode('ZGVma'.'W5l'),base64_decode('c'.'3'.'R'.'ybGVu'),base64_decode(''.'YmFzZ'.'TY0X2R'.'l'.'Y29k'.'Z'.'Q=='),base64_decode('dW5zZXJ'.'p'.'YWxpemU='),base64_decode('aXN'.'fYX'.'JyYXk='),base64_decode('Y2'.'91'.'b'.'nQ='),base64_decode('aW5fYX'.'J'.'yYXk='),base64_decode('c2'.'V'.'yaWF'.'s'.'aXpl'),base64_decode('YmFzZTY0X2'.'Vu'.'Y29k'.'ZQ'.'=='),base64_decode(''.'c3R'.'ybGVu'),base64_decode('YXJyYXlfa2V'.'5X2V4a'.'XN'.'0cw=='),base64_decode('aW5fYXJy'.'YXk='),base64_decode(''.'c3'.'R'.'y'.'bGVu'),base64_decode('YXJyYXlf'.'a'.'2V5X'.'2V4'.'a'.'XN0'.'c'.'w'.'=='),base64_decode('bWV0aG9kX2V4aX'.'N0cw=='),base64_decode('Y2'.'F'.'sbF9'.'1c2Vy'.'X2Z1bmNfYXJyYXk='),base64_decode('aW5fYXJyY'.'Xk='),base64_decode('ZG'.'VmaW5l'));if(!function_exists(__NAMESPACE__.'\\___615710308')){function ___615710308($_1650077830){static $_1870881582= false; if($_1870881582 == false) $_1870881582=array('Q'.'lVTSU5FU1NfRURJ'.'VEl'.'P'.'T'.'g==',''.'W'.'Q==','bWF'.'pbg==','fm'.'NwZl9'.'tYX'.'Bfd'.'mFsd'.'WU=','','U2'.'1hbGw=','U21hbGw'.'=','bWFpb'.'g==',''.'fmNwZl9tY'.'XBfdmF'.'sdWU'.'=','bWFpbg==',''.'T'.'24=','U2'.'V0dGlu'.'Z'.'3N'.'DaGFuZ2U=','VF'.'lQRQ==','Rg='.'=','WA==',''.'R'.'EFU'.'RQ==','','RkVBVFV'.'S'.'RVM=','RVhQSVJFRA==','R'.'kV'.'BVFVSRV'.'M=','Rg'.'==',''.'RU5D'.'T0RF','WQ==');return base64_decode($_1870881582[$_1650077830]);}};$GLOBALS['____126323757'][0](___615710308(0), ___615710308(1));class CBXFeatures{ private static $_138221258= array( "Small" => array(), "Big" => array( "CatMultiPrice", "CatMultiStore", "CatDiscountSave", "SaleAffiliate", "SaleAccounts", "SaleCCards", "SaleReports", "SaleRecurring", "CatCompleteSet", "CatMultiFactor",),); private static $_527489822= false; private static $_1217740141= false; private static function __1665642521(){ if(self::$_527489822 == false){ self::$_527489822= array(); foreach(self::$_138221258 as $_83198157 => $_276746351){ foreach($_276746351 as $_901593272) self::$_527489822[$_901593272]= $_83198157;}} if(self::$_1217740141 == false){ self::$_1217740141= array(); $_1115312278= COption::GetOptionString(___615710308(2), ___615710308(3), ___615710308(4)); if($GLOBALS['____126323757'][1]($_1115312278)> min(114,0,38)){ $_1115312278= $GLOBALS['____126323757'][2]($_1115312278); self::$_1217740141= $GLOBALS['____126323757'][3]($_1115312278); if(!$GLOBALS['____126323757'][4](self::$_1217740141)) self::$_1217740141= array(___615710308(5));} if($GLOBALS['____126323757'][5](self::$_1217740141) <=(1260/2-630)) self::$_1217740141= array(___615710308(6));}} public static function InitiateEditionsSettings($_2042709242){ self::__1665642521(); $_64323683= array(); foreach(self::$_138221258 as $_83198157 => $_276746351){ if($GLOBALS['____126323757'][6]($_83198157, $_2042709242)){ self::$_1217740141[]= $_83198157;} else{ foreach($_276746351 as $_901593272) $_64323683[]= $_901593272;}} $_1464305942= $GLOBALS['____126323757'][7](self::$_1217740141); $_1464305942= $GLOBALS['____126323757'][8]($_1464305942); COption::SetOptionString(___615710308(7), ___615710308(8), $_1464305942); foreach($_64323683 as $_911320234) self::__954327338($_911320234, false);} public static function IsFeatureEnabled($_901593272){ if($GLOBALS['____126323757'][9]($_901593272) <= 0) return true; self::__1665642521(); if(!$GLOBALS['____126323757'][10]($_901593272, self::$_527489822)) return true; return $GLOBALS['____126323757'][11](self::$_527489822[$_901593272], self::$_1217740141);} public static function IsFeatureInstalled($_901593272){ return self::IsFeatureEnabled($_901593272);} public static function IsFeatureEditable($_901593272){ if($GLOBALS['____126323757'][12]($_901593272) <= 0) return true; self::__1665642521(); if(!$GLOBALS['____126323757'][13]($_901593272, self::$_527489822)) return true; return false;} private static function __954327338($_901593272, $_1238441255){ if($GLOBALS['____126323757'][14]("CBXFeatures", "On".$_901593272."SettingsChange")) $GLOBALS['____126323757'][15](array("CBXFeatures", "On".$_901593272."SettingsChange"), array($_901593272, $_1238441255)); $_679651868= $GLOBALS['_____1919905219'][0](___615710308(9), ___615710308(10).$_901593272.___615710308(11)); while($_665362506= $_679651868->Fetch()) $GLOBALS['_____1919905219'][1]($_665362506, array($_901593272, $_1238441255));} public static function SetFeatureEnabled($_901593272, $_1238441255= true, $_67493591= true){} public static function SaveFeaturesSettings($_221021640, $_1961690397){} public static function GetFeaturesList(){ self::__1665642521(); $_5060378= array(); foreach(self::$_138221258 as $_83198157 => $_276746351){ $_5060378[$_83198157]= array( ___615710308(12) => $GLOBALS['____126323757'][16]($_83198157, self::$_1217740141)? ___615710308(13): ___615710308(14), ___615710308(15) => ___615710308(16), ___615710308(17) => array(), ___615710308(18) => false,); foreach($_276746351 as $_901593272) $_5060378[$_83198157][___615710308(19)][$_901593272]=($_5060378[$_83198157] == ___615710308(20));} return $_5060378;}} $GLOBALS['____126323757'][17](___615710308(21), ___615710308(22));/**/			//Do not remove this

//component 2.0 template engines
$GLOBALS["arCustomTemplateEngines"] = array();

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/urlrewriter.php");

/**
 * Defined in dbconn.php
 * @param string $DBType
 */

require_once(__DIR__.'/autoload.php');
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/agent.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/user.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/event.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/menu.php");
AddEventHandler("main", "OnAfterEpilog", array("\\Bitrix\\Main\\Data\\ManagedCache", "finalize"));
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/".$DBType."/usertype.php");

if(file_exists(($_fname = $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/update_db_updater.php")))
{
	$US_HOST_PROCESS_MAIN = False;
	include($_fname);
}

if(file_exists(($_fname = $_SERVER["DOCUMENT_ROOT"]."/bitrix/init.php")))
	include_once($_fname);

if(($_fname = getLocalPath("php_interface/init.php", BX_PERSONAL_ROOT)) !== false)
	include_once($_SERVER["DOCUMENT_ROOT"].$_fname);

if(($_fname = getLocalPath("php_interface/".SITE_ID."/init.php", BX_PERSONAL_ROOT)) !== false)
	include_once($_SERVER["DOCUMENT_ROOT"].$_fname);

if(!defined("BX_FILE_PERMISSIONS"))
	define("BX_FILE_PERMISSIONS", 0644);
if(!defined("BX_DIR_PERMISSIONS"))
	define("BX_DIR_PERMISSIONS", 0755);

//global var, is used somewhere
$GLOBALS["sDocPath"] = $GLOBALS["APPLICATION"]->GetCurPage();

if((!(defined("STATISTIC_ONLY") && STATISTIC_ONLY && mb_substr($GLOBALS["APPLICATION"]->GetCurPage(), 0, mb_strlen(BX_ROOT."/admin/")) != BX_ROOT."/admin/")) && COption::GetOptionString("main", "include_charset", "Y")=="Y" && LANG_CHARSET <> '')
	header("Content-Type: text/html; charset=".LANG_CHARSET);

if(COption::GetOptionString("main", "set_p3p_header", "Y")=="Y")
	header("P3P: policyref=\"/bitrix/p3p.xml\", CP=\"NON DSP COR CUR ADM DEV PSA PSD OUR UNR BUS UNI COM NAV INT DEM STA\"");

header("X-Powered-CMS: Bitrix Site Manager (".(LICENSE_KEY == "DEMO"? "DEMO" : md5("BITRIX".LICENSE_KEY."LICENCE")).")");
if (COption::GetOptionString("main", "update_devsrv", "") == "Y")
	header("X-DevSrv-CMS: Bitrix");

define("BX_CRONTAB_SUPPORT", defined("BX_CRONTAB"));

//agents
if(COption::GetOptionString("main", "check_agents", "Y") == "Y")
{
	$application->addBackgroundJob(["CAgent", "CheckAgents"], [], \Bitrix\Main\Application::JOB_PRIORITY_LOW);
}

//send email events
if(COption::GetOptionString("main", "check_events", "Y") !== "N")
{
	$application->addBackgroundJob(["CEvent", "CheckEvents"], [], \Bitrix\Main\Application::JOB_PRIORITY_LOW-1);
}

$healerOfEarlySessionStart = new HealerEarlySessionStart();
$healerOfEarlySessionStart->process($application->getKernelSession());

$kernelSession = $application->getKernelSession();
$kernelSession->start();
$application->getSessionLocalStorageManager()->setUniqueId($kernelSession->getId());

foreach (GetModuleEvents("main", "OnPageStart", true) as $arEvent)
	ExecuteModuleEventEx($arEvent);

//define global user object
$GLOBALS["USER"] = new CUser;

//session control from group policy
$arPolicy = $GLOBALS["USER"]->GetSecurityPolicy();
$currTime = time();
if(
	(
		//IP address changed
		$kernelSession['SESS_IP']
		&& $arPolicy["SESSION_IP_MASK"] <> ''
		&& (
			(ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($kernelSession['SESS_IP']))
			!=
			(ip2long($arPolicy["SESSION_IP_MASK"]) & ip2long($_SERVER['REMOTE_ADDR']))
		)
	)
	||
	(
		//session timeout
		$arPolicy["SESSION_TIMEOUT"]>0
		&& $kernelSession['SESS_TIME']>0
		&& $currTime-$arPolicy["SESSION_TIMEOUT"]*60 > $kernelSession['SESS_TIME']
	)
	||
	(
		//signed session
		isset($kernelSession["BX_SESSION_SIGN"])
		&& $kernelSession["BX_SESSION_SIGN"] <> bitrix_sess_sign()
	)
	||
	(
		//session manually expired, e.g. in $User->LoginHitByHash
		isSessionExpired()
	)
)
{
	$compositeSessionManager = $application->getCompositeSessionManager();
	$compositeSessionManager->destroy();

	$application->getSession()->setId(md5(uniqid(rand(), true)));
	$compositeSessionManager->start();

	$GLOBALS["USER"] = new CUser;
}
$kernelSession['SESS_IP'] = $_SERVER['REMOTE_ADDR'];
if (empty($kernelSession['SESS_TIME']))
{
	$kernelSession['SESS_TIME'] = $currTime;
}
elseif (($currTime - $kernelSession['SESS_TIME']) > 60)
{
	$kernelSession['SESS_TIME'] = $currTime;
}
if(!isset($kernelSession["BX_SESSION_SIGN"]))
	$kernelSession["BX_SESSION_SIGN"] = bitrix_sess_sign();

//session control from security module
if(
	(COption::GetOptionString("main", "use_session_id_ttl", "N") == "Y")
	&& (COption::GetOptionInt("main", "session_id_ttl", 0) > 0)
	&& !defined("BX_SESSION_ID_CHANGE")
)
{
	if(!isset($kernelSession['SESS_ID_TIME']))
	{
		$kernelSession['SESS_ID_TIME'] = $currTime;
	}
	elseif(($kernelSession['SESS_ID_TIME'] + COption::GetOptionInt("main", "session_id_ttl")) < $kernelSession['SESS_TIME'])
	{
		$compositeSessionManager = $application->getCompositeSessionManager();
		$compositeSessionManager->regenerateId();

		$kernelSession['SESS_ID_TIME'] = $currTime;
	}
}

define("BX_STARTED", true);

if (isset($kernelSession['BX_ADMIN_LOAD_AUTH']))
{
	define('ADMIN_SECTION_LOAD_AUTH', 1);
	unset($kernelSession['BX_ADMIN_LOAD_AUTH']);
}

if(!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS!==true)
{
	$doLogout = isset($_REQUEST["logout"]) && (strtolower($_REQUEST["logout"]) == "yes");

	if($doLogout && $GLOBALS["USER"]->IsAuthorized())
	{
		$secureLogout = (\Bitrix\Main\Config\Option::get("main", "secure_logout", "N") == "Y");

		if(!$secureLogout || check_bitrix_sessid())
		{
			$GLOBALS["USER"]->Logout();
			LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam('', array('logout', 'sessid')));
		}
	}

	// authorize by cookies
	if(!$GLOBALS["USER"]->IsAuthorized())
	{
		$GLOBALS["USER"]->LoginByCookies();
	}

	$arAuthResult = false;

	//http basic and digest authorization
	if(($httpAuth = $GLOBALS["USER"]->LoginByHttpAuth()) !== null)
	{
		$arAuthResult = $httpAuth;
		$GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
	}

	//Authorize user from authorization html form
	//Only POST is accepted
	if(isset($_POST["AUTH_FORM"]) && $_POST["AUTH_FORM"] <> '')
	{
		$bRsaError = false;
		if(COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y')
		{
			//possible encrypted user password
			$sec = new CRsaSecurity();
			if(($arKeys = $sec->LoadKeys()))
			{
				$sec->SetKeys($arKeys);
				$errno = $sec->AcceptFromForm(['USER_PASSWORD', 'USER_CONFIRM_PASSWORD', 'USER_CURRENT_PASSWORD']);
				if($errno == CRsaSecurity::ERROR_SESS_CHECK)
					$arAuthResult = array("MESSAGE"=>GetMessage("main_include_decode_pass_sess"), "TYPE"=>"ERROR");
				elseif($errno < 0)
					$arAuthResult = array("MESSAGE"=>GetMessage("main_include_decode_pass_err", array("#ERRCODE#"=>$errno)), "TYPE"=>"ERROR");

				if($errno < 0)
					$bRsaError = true;
			}
		}

		if($bRsaError == false)
		{
			if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
				$USER_LID = SITE_ID;
			else
				$USER_LID = false;

			if($_POST["TYPE"] == "AUTH")
			{
				$arAuthResult = $GLOBALS["USER"]->Login($_POST["USER_LOGIN"], $_POST["USER_PASSWORD"], $_POST["USER_REMEMBER"]);
			}
			elseif($_POST["TYPE"] == "OTP")
			{
				$arAuthResult = $GLOBALS["USER"]->LoginByOtp($_POST["USER_OTP"], $_POST["OTP_REMEMBER"], $_POST["captcha_word"], $_POST["captcha_sid"]);
			}
			elseif($_POST["TYPE"] == "SEND_PWD")
			{
				$arAuthResult = CUser::SendPassword($_POST["USER_LOGIN"], $_POST["USER_EMAIL"], $USER_LID, $_POST["captcha_word"], $_POST["captcha_sid"], $_POST["USER_PHONE_NUMBER"]);
			}
			elseif($_POST["TYPE"] == "CHANGE_PWD")
			{
				$arAuthResult = $GLOBALS["USER"]->ChangePassword($_POST["USER_LOGIN"], $_POST["USER_CHECKWORD"], $_POST["USER_PASSWORD"], $_POST["USER_CONFIRM_PASSWORD"], $USER_LID, $_POST["captcha_word"], $_POST["captcha_sid"], true, $_POST["USER_PHONE_NUMBER"], $_POST["USER_CURRENT_PASSWORD"]);
			}
			elseif(COption::GetOptionString("main", "new_user_registration", "N") == "Y" && $_POST["TYPE"] == "REGISTRATION" && (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true))
			{
				$arAuthResult = $GLOBALS["USER"]->Register($_POST["USER_LOGIN"], $_POST["USER_NAME"], $_POST["USER_LAST_NAME"], $_POST["USER_PASSWORD"], $_POST["USER_CONFIRM_PASSWORD"], $_POST["USER_EMAIL"], $USER_LID, $_POST["captcha_word"], $_POST["captcha_sid"], false, $_POST["USER_PHONE_NUMBER"]);
			}

			if($_POST["TYPE"] == "AUTH" || $_POST["TYPE"] == "OTP")
			{
				//special login form in the control panel
				if($arAuthResult === true && defined('ADMIN_SECTION') && ADMIN_SECTION === true)
				{
					//store cookies for next hit (see CMain::GetSpreadCookieHTML())
					$GLOBALS["APPLICATION"]->StoreCookies();
					$kernelSession['BX_ADMIN_LOAD_AUTH'] = true;

					CMain::FinalActions('<script type="text/javascript">window.onload=function(){(window.BX || window.parent.BX).AUTHAGENT.setAuthResult(false);};</script>');
					die();
				}
			}
		}
		$GLOBALS["APPLICATION"]->SetAuthResult($arAuthResult);
	}
	elseif(!$GLOBALS["USER"]->IsAuthorized())
	{
		//Authorize by unique URL
		$GLOBALS["USER"]->LoginHitByHash();
	}
}

//logout or re-authorize the user if something importand has changed
$GLOBALS["USER"]->CheckAuthActions();

//magic short URI
if(defined("BX_CHECK_SHORT_URI") && BX_CHECK_SHORT_URI && CBXShortUri::CheckUri())
{
	//local redirect inside
	die();
}

//application password scope control
if(($applicationID = $GLOBALS["USER"]->GetParam("APPLICATION_ID")) !== null)
{
	$appManager = \Bitrix\Main\Authentication\ApplicationManager::getInstance();
	if($appManager->checkScope($applicationID) !== true)
	{
		$event = new \Bitrix\Main\Event("main", "onApplicationScopeError", Array('APPLICATION_ID' => $applicationID));
		$event->send();

		CHTTP::SetStatus("403 Forbidden");
		die();
	}
}

//define the site template
if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
{
	$siteTemplate = "";
	if(is_string($_REQUEST["bitrix_preview_site_template"]) && $_REQUEST["bitrix_preview_site_template"] <> "" && $GLOBALS["USER"]->CanDoOperation('view_other_settings'))
	{
		//preview of site template
		$signer = new Bitrix\Main\Security\Sign\Signer();
		try
		{
			//protected by a sign
			$requestTemplate = $signer->unsign($_REQUEST["bitrix_preview_site_template"], "template_preview".bitrix_sessid());

			$aTemplates = CSiteTemplate::GetByID($requestTemplate);
			if($template = $aTemplates->Fetch())
			{
				$siteTemplate = $template["ID"];

				//preview of unsaved template
				if(isset($_GET['bx_template_preview_mode']) && $_GET['bx_template_preview_mode'] == 'Y' && $GLOBALS["USER"]->CanDoOperation('edit_other_settings'))
				{
					define("SITE_TEMPLATE_PREVIEW_MODE", true);
				}
			}
		}
		catch(\Bitrix\Main\Security\Sign\BadSignatureException $e)
		{
		}
	}
	if($siteTemplate == "")
	{
		$siteTemplate = CSite::GetCurTemplate();
	}
	define("SITE_TEMPLATE_ID", $siteTemplate);
	define("SITE_TEMPLATE_PATH", getLocalPath('templates/'.SITE_TEMPLATE_ID, BX_PERSONAL_ROOT));
}

//magic parameters: show page creation time
if(isset($_GET["show_page_exec_time"]))
{
	if($_GET["show_page_exec_time"]=="Y" || $_GET["show_page_exec_time"]=="N")
		$kernelSession["SESS_SHOW_TIME_EXEC"] = $_GET["show_page_exec_time"];
}

//magic parameters: show included file processing time
if(isset($_GET["show_include_exec_time"]))
{
	if($_GET["show_include_exec_time"]=="Y" || $_GET["show_include_exec_time"]=="N")
		$kernelSession["SESS_SHOW_INCLUDE_TIME_EXEC"] = $_GET["show_include_exec_time"];
}

//magic parameters: show include areas
if(isset($_GET["bitrix_include_areas"]) && $_GET["bitrix_include_areas"] <> "")
	$GLOBALS["APPLICATION"]->SetShowIncludeAreas($_GET["bitrix_include_areas"]=="Y");

//magic sound
if($GLOBALS["USER"]->IsAuthorized())
{
	$cookie_prefix = COption::GetOptionString('main', 'cookie_name', 'BITRIX_SM');
	if(!isset($_COOKIE[$cookie_prefix.'_SOUND_LOGIN_PLAYED']))
		$GLOBALS["APPLICATION"]->set_cookie('SOUND_LOGIN_PLAYED', 'Y', 0);
}

//magic cache
\Bitrix\Main\Composite\Engine::shouldBeEnabled();

foreach(GetModuleEvents("main", "OnBeforeProlog", true) as $arEvent)
	ExecuteModuleEventEx($arEvent);

if((!defined("NOT_CHECK_PERMISSIONS") || NOT_CHECK_PERMISSIONS!==true) && (!defined("NOT_CHECK_FILE_PERMISSIONS") || NOT_CHECK_FILE_PERMISSIONS!==true))
{
	$real_path = $request->getScriptFile();

	if(!$GLOBALS["USER"]->CanDoFileOperation('fm_view_file', array(SITE_ID, $real_path)) || (defined("NEED_AUTH") && NEED_AUTH && !$GLOBALS["USER"]->IsAuthorized()))
	{
		/** @noinspection PhpUndefinedVariableInspection */
		if($GLOBALS["USER"]->IsAuthorized() && $arAuthResult["MESSAGE"] == '')
		{
			$arAuthResult = array("MESSAGE"=>GetMessage("ACCESS_DENIED").' '.GetMessage("ACCESS_DENIED_FILE", array("#FILE#"=>$real_path)), "TYPE"=>"ERROR");

			if(COption::GetOptionString("main", "event_log_permissions_fail", "N") === "Y")
			{
				CEventLog::Log("SECURITY", "USER_PERMISSIONS_FAIL", "main", $GLOBALS["USER"]->GetID(), $real_path);
			}
		}

		if(defined("ADMIN_SECTION") && ADMIN_SECTION==true)
		{
			if ($_REQUEST["mode"]=="list" || $_REQUEST["mode"]=="settings")
			{
				echo "<script>top.location='".$GLOBALS["APPLICATION"]->GetCurPage()."?".DeleteParam(array("mode"))."';</script>";
				die();
			}
			elseif ($_REQUEST["mode"]=="frame")
			{
				echo "<script type=\"text/javascript\">
					var w = (opener? opener.window:parent.window);
					w.location.href='".$GLOBALS["APPLICATION"]->GetCurPage()."?".DeleteParam(array("mode"))."';
				</script>";
				die();
			}
			elseif(defined("MOBILE_APP_ADMIN") && MOBILE_APP_ADMIN==true)
			{
				echo json_encode(Array("status"=>"failed"));
				die();
			}
		}

		/** @noinspection PhpUndefinedVariableInspection */
		$GLOBALS["APPLICATION"]->AuthForm($arAuthResult);
	}
}

       //Do not remove this

