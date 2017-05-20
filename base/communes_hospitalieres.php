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
			'description'        => 'text NOT NULL DEFAULT ""',
			'maj'                => 'TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_initiative',
		),
		'titre' => 'titre AS titre, "" AS lang',
		 #'date' => '',
		'champs_editables'  => array('titre', 'id_gis', 'type_initiative', 'date_conseil_communal', 'nom_contact', 'prenom_contact', 'email', 'telephone', 'description'),
		'champs_versionnes' => array('id_gis', 'type_initiative', 'date_conseil_communal', 'nom_contact', 'prenom_contact', 'email', 'telephone', 'description'),
		'rechercher_champs' => array("titre" => 4, "id_gis" => 4, "type_initiative" => 5, "nom_contact" => 8, "prenom_contact" => 8, "description" => 6),
		'tables_jointures'  => array(),


	);

	return $tables;
}
