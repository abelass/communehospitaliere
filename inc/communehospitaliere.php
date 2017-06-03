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
	$weight = 3;
	$color = '#2e718e';
	$opacity = 1;
	$fillopacity = 0.5;
	return array (
		'contact' => array(
			'label' =>_T('initiative:titre_type_initiative_contact'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#ffffff',
				'opacity' => $fillopacity,
			),
		),
		'interpellation' => array(
			'label' => _T('initiative:titre_type_initiative_interpellation'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#ffff00',
				'fillopacity' => $fillopacity,
			),
		),
		'motion' => array(
			'label' => _T('initiative:titre_type_initiative_motion'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#008000',
				'fillopacity' => $fillopacity,
			),
		)
	);
}
