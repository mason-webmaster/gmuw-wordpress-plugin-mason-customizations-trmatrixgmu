<?php

/**
 * Summary: php file which implements the theme initialization tasks
 */

function gmuw_trmatrixgmu_plugin_activate(){

  // Create custom tables
    gmuw_trmatrixgmu_create_custom_tables();

}

function gmuw_trmatrixgmu_create_custom_tables(){

  // Create custom tables needed for the plugin
    // students
      gmuw_trmatrixgmu_create_custom_table_coursedata();

}

function gmuw_trmatrixgmu_create_custom_table_coursedata(){

  // Get globals
    global $wpdb;

  // Include file that contains the dbDelta function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

  // Set table name, using the database prefix
    $table_name = $wpdb->prefix . "gmuw_trmatrixgmu_coursedata";

  // Write SQL statement to create table
    $sql = "CREATE TABLE $table_name (
	 SHRTATC_SBGI_CODE nvarchar(255) DEFAULT NULL,
	 SZBTATC_SBGI_DESC nvarchar(255) DEFAULT NULL,
	 SZBTATC_SBGI_CITY nvarchar(255) DEFAULT NULL,
	 SZBTATC_SBGI_STAT_CODE nvarchar(255) DEFAULT NULL,
	 SHRTATC_TERM_CODE_EFF_TRNS nvarchar(255) DEFAULT NULL,
	 SHRTATC_GROUP nvarchar(255) DEFAULT NULL,
	 SHRTATC_SUBJ_TRNS nvarchar(255) DEFAULT NULL,
	 SHRTATC_TRNS_TITLE nvarchar(255) DEFAULT NULL,
	 SZBTATC_TRNS_CREDITS nvarchar(255) DEFAULT NULL,
	 SZBTATC_SUBJ_INST nvarchar(255) DEFAULT NULL,
	 SZBTATC_INST_TITLE nvarchar(255) DEFAULT NULL,
	 SHRTATC_INST_CREDITS_USED nvarchar(255) DEFAULT NULL,
     PRIMARY KEY  (ID)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

  // Execute SQL
    dbDelta($sql);

}
