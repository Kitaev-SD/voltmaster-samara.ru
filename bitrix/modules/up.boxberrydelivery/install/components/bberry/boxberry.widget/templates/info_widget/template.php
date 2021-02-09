<?	global $APPLICATION;
	CJSCore::Init(array("jquery"));
	$widget_url = COption::GetOptionString('up.boxberrydelivery', 'WIDGET_URL');
	$APPLICATION->AddHeadScript($widget_url);
?>

<div id="boxberry_widget"></div>

<script>
	boxberry.openOnPage('boxberry_widget'); 
	boxberry.open('', '1$DCIlCpOeh0NkfiVjTUQNEQ8fPbjnIldR','Москва','', '', 100);
</script>