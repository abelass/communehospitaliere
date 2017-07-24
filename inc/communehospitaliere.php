<?php
/**
 * Les fonctions internes du plugin Communes hospitalières
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Inc\Communeshospitalieres
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function ch_definitions_initiatives() {
	include_spip('inc/config');
	$config = lire_config('communes_hospitalieres');
	$weight = 3;
	$color_defaut = isset($config['color']) ? $config['color'] : '#2e718e';
	$opacity = 1;
	$fillopacity = 0.5;
	return array (
		'contact' => array(
			'label' =>_T('initiative:titre_type_initiative_contact'),
			'styles' => array(
				'color' => isset($config['color_contact']) ? $config['color_contact'] : $color_defaut,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => isset($config['fillcolor_contact']) ? $config['fillcolor_contact'] :'#ffffff',
				'opacity' => $fillopacity,
			),
		),
		'interpellation' => array(
			'label' => _T('initiative:titre_type_initiative_interpellation'),
			'styles' => array(
				'color' => isset($config['color_interpellation']) ? $config['color_interpellation'] : $color_defaut,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => isset($config['fillcolor_interpellation']) ? $config['fillcolor_interpellation'] :'#ffff00',
				'fillopacity' => $fillopacity,
			),
		),
		'motion' => array(
			'label' => _T('initiative:titre_type_initiative_motion'),
			'styles' => array(
				'color' => isset($config['color_motion']) ? $config['color_motion'] : $color_defaut,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => isset($config['fillcolor_motion']) ? $config['fillcolor_motion'] : '#008000',
				'fillopacity' => $fillopacity,
			),
		)
	);
}

function ch_definitions_notes() {
	return array (
		1 => _T('initiative:titre_note_1'),
		2 => _T('initiative:titre_note_2'),
		3 => _T('initiative:titre_note_3'),
	);
}