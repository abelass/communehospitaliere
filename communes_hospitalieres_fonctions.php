<?php
/**
 * Fonctions utiles au plugin Communes hospitalières
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Retourne le nom de la Note.
 *
 * @param integer $note.
 *
 * @return string
 *
 */
function nom_note($note) {
	include_spip('inc/communehospitaliere');
	$definition_notes = ch_definitions_notes();
	$nom = isset($definition_notes[$note]) ? $definition_notes[$note] : $note;
	return $nom;
}

/**
 * Retourne le nom de l'initiative.
 *
 * @param string $type.
 *
 * @return string
 *
 */
function nom_type_initiative($type) {
	include_spip('inc/communehospitaliere');
	$definitions_initiatives= ch_definitions_initiatives();
	$nom = isset($definitions_initiatives[$type]) ? $definitions_initiatives[$type]['label']: $type;
	return $nom;
}

