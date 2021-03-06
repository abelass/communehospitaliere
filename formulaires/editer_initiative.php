<?php
/**
 * Gestion du formulaire de d'édition de initiative
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Formulaires
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

include_spip('inc/actions');
include_spip('inc/editer');


/**
 * Identifier le formulaire en faisant abstraction des paramètres qui ne représentent pas l'objet edité
 *
 * @param int|string $id_initiative
 *     Identifiant du initiative. 'new' pour un nouveau initiative.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un initiative source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du initiative, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return string
 *     Hash du formulaire
 */
function formulaires_editer_initiative_identifier_dist($id_initiative = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	return serialize(array(intval($id_initiative)));
}

/**
 * Chargement du formulaire d'édition de initiative
 *
 * Déclarer les champs postés et y intégrer les valeurs par défaut
 *
 * @uses formulaires_editer_objet_charger()
 *
 * @param int|string $id_initiative
 *     Identifiant du initiative. 'new' pour un nouveau initiative.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un initiative source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du initiative, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Environnement du formulaire
 */
function formulaires_editer_initiative_charger_dist($id_initiative = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	include_spip('inc/communehospitaliere');
	$valeurs = formulaires_editer_objet_charger('initiative', $id_initiative, '', $lier_trad, $retour, $config_fonc, $row, $hidden);
	$definition_initiatives = ch_definitions_initiatives();
	foreach ($definition_initiatives AS $nom => $valeur) {
		$valeurs['_valeurs_type_initiative'][$nom] = $valeur['label'];
	}

	$valeurs['_valeurs_note'] = ch_definitions_notes();

	return $valeurs;
}

/**
 * Vérifications du formulaire d'édition de initiative
 *
 * Vérifier les champs postés et signaler d'éventuelles erreurs
 *
 * @uses formulaires_editer_objet_verifier()
 *
 * @param int|string $id_initiative
 *     Identifiant du initiative. 'new' pour un nouveau initiative.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un initiative source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du initiative, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Tableau des erreurs
 */
function formulaires_editer_initiative_verifier_dist($id_initiative = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	$erreurs = array();

	$verifier = charger_fonction('verifier', 'inc');

	foreach (array('date_conseil_communal') AS $champ) {
		$normaliser = null;
		if ($erreur = $verifier(_request($champ), 'date', array('normaliser'=>'datetime'), $normaliser)) {
			$erreurs[$champ] = $erreur;
		// si une valeur de normalisation a ete transmis, la prendre.
		} elseif (!is_null($normaliser)) {
			set_request($champ, $normaliser);
		// si pas de normalisation ET pas de date soumise, il ne faut pas tenter d'enregistrer ''
		} else {
			set_request($champ, null);
		}
	}


	$erreurs += formulaires_editer_objet_verifier('initiative', $id_initiative, array('id_gis', 'type_initiative', 'email', 'telephone'));

	// Ne pas réutiliser une commune.
	if ($id_gis = _request('id_gis')) {
		if ($id_initiative != 'new') {
			$where = 'id_initiative !=' . $id_initiative . ' AND id_gis=' . $id_gis;
		}
		else {
			$where = 'id_gis=' . $id_gis;
		}

		if (sql_getfetsel('id_initiative', 'spip_initiatives', $where)) {
			$erreurs['id_gis'] = _T('initiative:message_erreur_point_gis_choisis');
		}
		else {
			set_request('titre', sql_getfetsel('titre', 'spip_gis', 'id_gis=' . $id_gis));
		}
	}

	// Définir les styles spécifique dy type d'initiative pour le point gis.
	if ($type_initiative = _request('type_initiative')) {
		include_spip('inc/communehospitaliere');
		$definition_initiatives = ch_definitions_initiatives();
		set_request('styles_gis', $definition_initiatives[$type_initiative]['styles']);
	}

	return $erreurs;
}

/**
 * Traitement du formulaire d'édition de initiative
 *
 * Traiter les champs postés
 *
 * @uses formulaires_editer_objet_traiter()
 *
 * @param int|string $id_initiative
 *     Identifiant du initiative. 'new' pour un nouveau initiative.
 * @param string $retour
 *     URL de redirection après le traitement
 * @param int $lier_trad
 *     Identifiant éventuel d'un initiative source d'une traduction
 * @param string $config_fonc
 *     Nom de la fonction ajoutant des configurations particulières au formulaire
 * @param array $row
 *     Valeurs de la ligne SQL du initiative, si connu
 * @param string $hidden
 *     Contenu HTML ajouté en même temps que les champs cachés du formulaire.
 * @return array
 *     Retours des traitements
 */
function formulaires_editer_initiative_traiter_dist($id_initiative = 'new', $retour = '', $lier_trad = 0, $config_fonc = '', $row = array(), $hidden = '') {
	include_spip('action/editer_gis');
	$retours = formulaires_editer_objet_traiter('initiative', $id_initiative, '', $lier_trad, $retour, $config_fonc, $row, $hidden);

	if (!_request('exec') AND $id_initiative == 'new') {
		$id_initiative = $retours['id_initiative'];
		$redirect = generer_url_entite($id_initiative,'initiative','editer=' . $id_initiative, true);
		include_spip('inc/headers');
		if($redirect) {
			redirige_par_entete($redirect);
		}
	}
	gis_modifier(_request('id_gis'), _request('styles_gis'));
	return $retours;
}
