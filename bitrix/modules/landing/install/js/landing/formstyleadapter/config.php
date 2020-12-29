<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/formstyleadapter.bundle.css',
	'js' => 'dist/formstyleadapter.bundle.js',
	'rel' => [
		'main.core',
		'main.core.events',
		'crm.form.client',
		'landing.ui.form.styleform',
		'landing.loc',
		'landing.ui.field.colorpickerfield',
		'landing.ui.panel.formsettingspanel',
		'landing.backend',
		'landing.env',
	],
	'skip_core' => false,
];