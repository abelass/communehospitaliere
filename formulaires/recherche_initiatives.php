<?php

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * chargement des valeurs par defaut des champs du #FORMULAIRE_RECHERCHE
 * on peut lui passer l'url de destination en premier argument
 * on peut passer une deuxième chaine qui va différencier le formulaire pour pouvoir en utiliser plusieurs sur une même page
 *
 * @param integer $id id_rubrique
 * @param string $options
 *        pour le moment uniquement parents = true : afficher les cotneus de ls sousribrique
 * @return array
 */
function formulaires_recherche_initiatives_charger_dist(){

	$valeurs = array("recherche" => _request('recherche'));

	return $valeurs;
}
?>
