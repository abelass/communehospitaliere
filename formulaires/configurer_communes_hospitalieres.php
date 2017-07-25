<?php

// Sécurité
if (!defined('_ECRIRE_INC_VERSION'))
	return;
function formulaires_configurer_communes_hospitalieres_saisies_dist() {
	include_spip('inc/config');

	$config = lire_config('communes_hospitalieres');

	// Styles map.
	$color_defaut = isset($config['color']) ? $config['color'] : '#2e718e';
	$weight = 3;
	$color = '#2e718e';
	$opacity = 1;
	$fillopacity = 0.5;

	// Notifications.
	$choix_expediteurs = array(
		'webmaster' => _T('reservation:notifications_expediteur_choix_webmaster'),
		'administrateur' => _T('reservation:notifications_expediteur_choix_administrateur'),
		'email' => _T('reservation:notifications_expediteur_choix_email')
	);

	if (defined('_DIR_PLUGIN_FACTEUR')) {
		$choix_expediteurs['facteur'] = _T('reservation:notifications_expediteur_choix_facteur');
	}

	$types = array(
		'contact' => array(
			'label' => _T('initiative:titre_type_initiative_contact'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#ffffff',
				'opacity' => $fillopacity
			)
		),
		'interpellation' => array(
			'label' => _T('initiative:titre_type_initiative_interpellation'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#ffff00',
				'fillopacity' => $fillopacity
			)
		),
		'motion' => array(
			'label' => _T('initiative:titre_type_initiative_motion'),
			'styles' => array(
				'color' => $color,
				'weight' => $weight,
				'opacity' => $opacity,
				'fillcolor' => '#008000',
				'fillopacity' => $fillopacity
			)
		)
	);

	$saisies = array();
	foreach ($types as $type => $donnees) {
		$color = isset($config['color_' . $type]) ? $config['color_' . $type] : $color_defaut;
		$saisies[] = array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_' . $type,
				'label' => $donnees['label']
			),
			'saisies' => array(
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'color_' . $type,
						'label' => _T('communes_hospitalieres:label_couleur_bord'),
						'defaut' => $color
					)
				),
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'fillcolor_' . $type,
						'label' => _T('communes_hospitalieres:label_fillcolor'),
						'defaut' => isset($config['fillcolor_' . $type]) ? $config['fillcolor_' . $type] : $donnees['styles']['fillcolor']
					)
				)
			)
		);
	}

	return array(
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_general',
				'label' => _T('communes_hospitalieres:cfg_titre_map')
			),
			'saisies' => array(

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
								'defaut' => $color_defaut
							)
						)
					)
				),
				array(
					'saisie' => 'fieldset',
					'options' => array(
						'nom' => 'fieldset_specifique',
						'label' => _T('communes_hospitalieres:cfg_titre_specifique')
					),
					'saisies' => $saisies
				),
			),
		),
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_notifications_parametres',
				'label' => _T('communes_hospitalieres:cfg_titre_notification')
			),
			'saisies' => array(
				array(
					'saisie' => 'selection',
					'options' => array(
						'nom' => 'type',
						'label' => _T('communes_hospitalieres:notifications_destinataire_label'),
						'cacher_option_intro' => 'on',
						'defaut' => $config['vendeur'],
						'datas' => array(
							'webmaster' => _T('communes_hospitalieres:notifications_choix_webmaster'),
							'administrateur' => _T('communes_hospitalieres:notifications_choix_administrateur'),
						),
					),
				),
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'type_webmaster',
						'label' => _T('communes_hospitalieres:notifications_webmaster_label'),
						'statut' => '0minirezo',
						'cacher_option_intro' => "on",
						'webmestre' => 'oui',
						'multiple' => 'oui',
						'defaut' => $config['vendeur_webmaster'],
						'afficher_si' => '@type@ == "webmaster"'
					),
				),
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'type_administrateur',
						'label' => _T('communes_hospitalieres:notifications_administrateur_label'),
						'statut' => '0minirezo',
						'multiple' => 'oui',
						'cacher_option_intro' => "on",
						'defaut' => $config['vendeur_administrateur'],
						'afficher_si' => '@type@ == "administrateur"'
					),
				),
			),
		),
	);
}
