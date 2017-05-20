<?php
/**
 * Utilisation de l'action supprimer pour l'objet initiative
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Action
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}



/**
 * Action pour supprimer un·e initiative
 *
 * Vérifier l'autorisation avant d'appeler l'action.
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, initiative, #ID_INITIATIVE}|oui)
 *         [(#BOUTON_ACTION{<:initiative:supprimer_initiative:>,
 *             #URL_ACTION_AUTEUR{supprimer_initiative, #ID_INITIATIVE, #URL_ECRIRE{initiatives}},
 *             danger, <:initiative:confirmer_supprimer_initiative:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     [(#AUTORISER{supprimer, initiative, #ID_INITIATIVE}|oui)
 *         [(#BOUTON_ACTION{
 *             [(#CHEMIN_IMAGE{initiative-del-24.png}|balise_img{<:initiative:supprimer_initiative:>}|concat{' ',#VAL{<:initiative:supprimer_initiative:>}|wrap{<b>}}|trim)],
 *             #URL_ACTION_AUTEUR{supprimer_initiative, #ID_INITIATIVE, #URL_ECRIRE{initiatives}},
 *             icone s24 horizontale danger initiative-del-24, <:initiative:confirmer_supprimer_initiative:>})]
 *     ]
 *     ```
 *
 * @example
 *     ```
 *     if (autoriser('supprimer', 'initiative', $id_initiative)) {
 *          $supprimer_initiative = charger_fonction('supprimer_initiative', 'action');
 *          $supprimer_initiative($id_initiative);
 *     }
 *     ```
 *
 * @param null|int $arg
 *     Identifiant à supprimer.
 *     En absence de id utilise l'argument de l'action sécurisée.
**/
function action_supprimer_initiative_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	$arg = intval($arg);

	// cas suppression
	if ($arg) {
		sql_delete('spip_initiatives',  'id_initiative=' . sql_quote($arg));
	}
	else {
		spip_log("action_supprimer_initiative_dist $arg pas compris");
	}
}
