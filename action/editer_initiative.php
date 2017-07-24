<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2016                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

/**
 * Gestion générique de modification des objets éditoriaux
 *
 * @package SPIP\Core\Edition
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Modifie le statut et/ou la date d'un objet
 *
 * @param string $objet
 * @param int $id
 * @param array $c
 *   $c est un array ('statut', 'id_parent' = changement de rubrique)
 *   statut et rubrique sont lies, car un admin restreint peut deplacer
 *   un objet publie vers une rubrique qu'il n'administre pas
 * @param bool $calcul_rub
 * @return string
 */
function initiative_instituer($id, $c, $calcul_rub = true) {
	$objet = 'initiative';
	if (include_spip('action/editer_' . $objet)
		and function_exists($instituer = $objet . "_instituer")
	) {
		return $instituer($id, $c, $calcul_rub);
	}

	$table_sql = table_objet_sql($objet);
	$trouver_table = charger_fonction('trouver_table', 'base');
	$desc = $trouver_table($table_sql);
	if (!$desc or !isset($desc['field'])) {
		return _L("Impossible d'instituer $objet : non connu en base");
	}

	include_spip('inc/autoriser');
	include_spip('inc/rubriques');
	include_spip('inc/modifier');

	$sel = array();
	$sel[] = (isset($desc['field']['statut']) ? "statut" : "'' as statut");

	$champ_date = '';
	if (isset($desc['date']) and $desc['date']) {
		$champ_date = $desc['date'];
	} elseif (isset($desc['field']['date'])) {
		$champ_date = 'date';
	}

	$sel[] = ($champ_date ? "$champ_date as date" : "'' as date");
	$sel[] = (isset($desc['field']['id_rubrique']) ? 'id_rubrique' : "0 as id_rubrique");

	$row = sql_fetsel($sel, $table_sql, id_table_objet($objet) . '=' . intval($id));

	$id_rubrique = $row['id_rubrique'];
	$statut_ancien = $statut = $row['statut'];
	$date_ancienne = $date = $row['date'];
	$champs = array();

	$d = ($date and isset($c[$champ_date])) ? $c[$champ_date] : null;
	$s = (isset($desc['field']['statut']) and isset($c['statut'])) ? $c['statut'] : $statut;

	// cf autorisations dans inc/instituer_objet
	if ($s != $statut or ($d and $d != $date)) {
		if ($id_rubrique ?
			autoriser('publierdans', 'rubrique', $id_rubrique)
			:
			autoriser('instituer', $objet, $id, null, array('statut' => $s))
		) {
			$statut = $champs['statut'] = $s;
		} else {
			if ($s != 'publie' and autoriser('modifier', $objet, $id)) {
				$statut = $champs['statut'] = $s;
			} else {
				spip_log("editer_objet $id refus " . join(' ', $c));
			}
		}

		// En cas de publication, fixer la date a "maintenant"
		// sauf si $c commande autre chose
		// ou si l'objet est deja date dans le futur
		// En cas de proposition d'un objet (mais pas depublication), idem
		if ($champ_date) {
			if ($champs['statut'] == 'publie'
				or ($champs['statut'] == 'prop' and !in_array($statut_ancien, array('publie', 'prop')))
				or $d
			) {
				if ($d or strtotime($d = $date) > time()) {
					$champs[$champ_date] = $date = $d;
				} else {
					$champs[$champ_date] = $date = date('Y-m-d H:i:s');
				}
			}
		}
	}

	// Verifier que la rubrique demandee existe et est differente
	// de la rubrique actuelle
	if ($id_rubrique
		and isset($c['id_parent'])
		and $id_parent = $c['id_parent']
		and $id_parent != $id_rubrique
		and (sql_fetsel('1', "spip_rubriques", "id_rubrique=" . intval($id_parent)))
	) {
		$champs['id_rubrique'] = $id_parent;

		// si l'objet etait publie
		// et que le demandeur n'est pas admin de la rubrique
		// repasser l'objet en statut 'propose'.
		if ($statut == 'publie'
			and !autoriser('publierdans', 'rubrique', $id_rubrique)
		) {
			$champs['statut'] = 'prop';
		}
	}


	// Envoyer aux plugins
	$champs = pipeline('pre_edition',
		array(
			'args' => array(
				'table' => $table_sql,
				'id_objet' => $id,
				'action' => 'instituer',
				'statut_ancien' => $statut_ancien,
				'date_ancienne' => $date_ancienne,
				'id_parent_ancien' => $id_rubrique,
			),
			'data' => $champs
		)
	);

	if (!count($champs)) {
		return '';
	}

	// Envoyer les modifs.
	objet_editer_heritage($objet, $id, $id_rubrique, $statut_ancien, $champs, $calcul_rub);

	// Invalider les caches
	include_spip('inc/invalideur');
	suivre_invalideur("id='$objet/$id'");

	/*
	if ($date) {
		$t = strtotime($date);
		$p = @$GLOBALS['meta']['date_prochain_postdate'];
		if ($t > time() AND (!$p OR ($t < $p))) {
			ecrire_meta('date_prochain_postdate', $t);
		}
	}*/

	// Pipeline
	pipeline('post_edition',
		array(
			'args' => array(
				'table' => $table_sql,
				'id_objet' => $id,
				'action' => 'instituer',
				'statut_ancien' => $statut_ancien,
				'date_ancienne' => $date_ancienne,
				'id_parent_ancien' => $id_rubrique,
			),
			'data' => $champs
		)
	);

	// Notifications
	if ($notifications = charger_fonction('notifications', 'inc')) {
		$notifications("instituer$objet", $id,
			array('statut' => $statut, 'statut_ancien' => $statut_ancien, 'date' => $date, 'date_ancienne' => $date_ancienne)
		);
	}

	return ''; // pas d'erreur
}

