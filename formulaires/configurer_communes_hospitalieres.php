<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION'))
	return;

	function formulaires_configurer_communes_hospitalieres_saisies_dist() {
	include_spip('inc/config');
	$config = lire_config('communes_hospitalieres');
	$color_defaut = isset($config['color']) ? $config['color'] : '#2e718e';
	$weight = 3;
	$color = '#2e718e';
	$opacity = 1;
	$fillopacity = 0.5;

	// Les prestas coonfigurés.

	$types = array(
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

	$saisies = array();
	foreach ($types as $type => $donnees) {
		$color = isset($config['color_' . $type]) ? $config['color_' . $type] : $color_defaut;
		$saisies[] =
				array(
				'saisie' => 'fieldset',
				'options' => array(
					'nom' => 'fieldset_' . $type,
					'label' => $donnees['label'],
				),
				'saisies' => array(
					array(
						'saisie' => 'input',
						'options' => array(
							'nom' => 'color_' . $type,
							'label' => _T('communes_hospitalieres:label_couleur_bord'),
							'defaut' => $color,
						)
					),
					array(
						'saisie' => 'input',
						'options' => array(
							'nom' => 'fillcolor_' . $type,
							'label' => _T('communes_hospitalieres:label_fillcolor'),
							'defaut' => isset($config['fillcolor_' . $type]) ?
								$config['fillcolor_' . $type] :
								$donnees['styles']['fillcolor'],
						)
					),
				),
		);
	}

	return array(
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_general',
				'label' => _T('communes_hospitalieres:cfg_titre_general')
			),
			'saisies' => array(
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'color_bord',
						'label' => _T('communes_hospitalieres:label_couleur_bord'),
						'defaut' => $color_defaut,
					)
				),
			),
		),
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_specifique',
				'label' => _T('communes_hospitalieres:cfg_titre_specifique')
			),
			'saisies' => $saisies,
		),
	);
}
