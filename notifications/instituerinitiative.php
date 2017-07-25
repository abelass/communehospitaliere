<?php
/*
 * Plugin Notifications
 * (c) 2009 SPIP
 * Distribue sous licence GPL
 *
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

// Fonction appelee par divers pipelines
// http://code.spip.net/@notifications_instituerarticle_dist
function notifications_instituernitiative_dist($quoi, $id_initiative, $options) {

	// ne devrait jamais se produire
	if ($options['statut'] == $options['statut_ancien']) {
		spip_log("statut inchange", 'notifications');

		return;
	}

	include_spip('inc/texte');

	$modele = "";

	if ($options['statut'] == 'prop' and $options['statut_ancien'] != 'publie') {
		$modele = "notifications/initiative_propose";
	}

	if ($modele) {
		$dest = (isset($config['type_' . $config['type']]) and intval($config['type_' . $config['type']])) ? $config['type_' . $config['type']] : array(
			1
		);

		$sql = sql_select('email', 'spip_auteurs', 'id_auteur IN (' . implode(',', $dest) . ')');

		$destinataires = array();
		while ($data = sql_fetch($sql)) {
			$destinataires[] = $data['email'];
		}

		$destinataires = pipeline('notifications_destinataires', array(
			'args' => array(
				'quoi' => $quoi,
				'id' => $id_article,
				'options' => $options
			),
			'data' => $destinataires
		));

		$texte = email_notification_objet($id_initiative, 'initiative', $modele);
		notifications_envoyer_mails($destinataires, $texte);
	}
}
