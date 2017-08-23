<?php
/**
 * Définit les autorisations du plugin Communes hospitalières
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Autorisations
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/*
 * Un fichier d'autorisations permet de regrouper
 * les fonctions d'autorisations de votre plugin
 */

/**
 * Fonction d'appel pour le pipeline
 * @pipeline autoriser
 */
function communes_hospitalieres_autoriser() {
}

/*
 * Exemple
 * function autoriser_communes_hospitalieres_configurer_dist($faire, $type, $id, $qui, $opt) {
 * // type est un objet (la plupart du temps) ou une chose.
 * // autoriser('configurer', '_communes_hospitalieres') => $type = 'communes_hospitalieres'
 * // au choix :
 * return autoriser('webmestre', $type, $id, $qui, $opt); // seulement les webmestres
 * return autoriser('configurer', '', $id, $qui, $opt); // seulement les administrateurs complets
 * return $qui['statut'] == '0minirezo'; // seulement les administrateurs (même les restreints)
 * // ...
 * }
 */

// -----------------
// Objet initiatives

/**
 * Autorisation de voir un élément de menu (initiatives)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiatives_menu_dist($faire, $type, $id, $qui, $opt) {
	return true;
}

/**
 * Autorisation de voir le bouton d'accès rapide de création (initiative)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiativecreer_menu_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('creer', 'initiative', '', $qui, $opt);
}

/**
 * Autorisation de créer (initiative)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiative_creer_dist($faire, $type, $id, $qui, $opt) {
	return in_array($qui['statut'], array(
		'0minirezo',
		'1comite',
		'6forum'
	));
}

/**
 * Autorisation de voir (initiative)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiative_voir_dist($faire, $type, $id, $qui, $opt) {
	return true;
}

/**
 * Autorisation de modifier (initiative)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiative_modifier_dist($faire, $type, $id, $qui, $opt) {
	$return = FALSE;
	$auteur = sql_getfetsel(
			'id_auteur',
			'spip_auteurs_liens',
			'id_auteur=' . $qui['id_auteur'] . ' AND id_objet=' . sql_quote($id) . ' AND objet=' . sql_quote('initiative'));
	$statut = sql_getfetsel("statut", "spip_initiatives", "id_initiative=" . sql_quote($id));

	if ($statut &&
			($qui['statut'] == '0minirezo' or
					(
						in_array($qui['statut'], array(
							'0minirezo',
							'1comite',
							'6forum'
						)) and
						$auteur
						)
					)) {
				$return = TRUE;
			}

	return $return;
}

/**
 * Autorisation de supprimer (initiative)
 *
 * @param string $faire
 *        	Action demandée
 * @param string $type
 *        	Type d'objet sur lequel appliquer l'action
 * @param int $id
 *        	Identifiant de l'objet
 * @param array $qui
 *        	Description de l'auteur demandant l'autorisation
 * @param array $opt
 *        	Options de cette autorisation
 * @return bool true s'il a le droit, false sinon
 *
 */
function autoriser_initiative_supprimer_dist($faire, $type, $id, $qui, $opt) {
	return $qui['statut'] == '0minirezo' and !$qui['restreint'];
}
