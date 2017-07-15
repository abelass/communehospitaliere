<?php
/**
 * Déclarations relatives à la base de données
 *
 * @plugin     Communes hospitalières
 * @copyright  2017
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Communes_hospitalieres\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Déclaration des alias de tables et filtres automatiques de champs
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function communes_hospitalieres_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['initiatives'] = 'initiatives';

	return $interfaces;
}


/**
 * Déclaration des objets éditoriaux
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function communes_hospitalieres_declarer_tables_objets_sql($tables) {

	$tables['spip_initiatives'] = array(
		'type' => 'initiative',
		'principale' => 'oui',
		'field'=> array(
			'id_initiative'      => 'bigint(21) NOT NULL',
			'titre'              => 'varchar(255) NOT NULL DEFAULT ""',
			'id_gis'             => 'bigint(21) NOT NULL DEFAULT 0',
			'type_initiative'    => 'varchar(25) NOT NULL DEFAULT ""',
			'date_conseil_communal' => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'nom_contact'        => 'varchar(255) NOT NULL DEFAULT ""',
			'prenom_contact'     => 'varchar(255) NOT NULL DEFAULT ""',
			'email'              => 'varchar(255) NOT NULL DEFAULT ""',
			'telephone'          => 'varchar(255) NOT NULL DEFAULT ""',
			'note'     					 => 'int(1) NOT NULL',
			'description'        => 'text NOT NULL DEFAULT ""',
			'statut'             => 'varchar(20)  DEFAULT "0" NOT NULL',
			'maj'                => 'TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_initiative',
			'KEY statut'         => 'statut',
			'KEY id_gis'         => 'is_gis',
		),
		'titre' => 'titre AS titre, "" AS lang',
		#'date' => '',
		'champs_editables'  => array('titre', 'id_gis', 'type_initiative', 'date_conseil_communal', 'nom_contact', 'prenom_contact', 'email', 'telephone', 'description', 'note'),
		'champs_versionnes' => array('id_gis', 'type_initiative', 'date_conseil_communal', 'nom_contact', 'prenom_contact', 'email', 'telephone', 'description', 'note'),
		'rechercher_champs' => array("titre" => 4, "id_gis" => 4, "type_initiative" => 5, "nom_contact" => 8, "prenom_contact" => 8, "description" => 6),
		'statut_textes_instituer' => array(
			'prepa'    => 'texte_statut_en_cours_redaction',
			'prop'     => 'texte_statut_propose_evaluation',
			'publie'   => 'texte_statut_publie',
			'refuse'   => 'texte_statut_refuse',
			'poubelle' => 'texte_statut_poubelle',
		),
		'statut'=> array(
			array(
				'champ'     => 'statut',
				'publie'    => 'publie',
				'previsu'   => 'publie,prop,prepa',
				'post_date' => 'date',
				'exception' => array('statut','tout')
			)
		),
		'texte_changer_statut' => 'initiative:texte_changer_statut_initiative',


	);

	return $tables;
}
