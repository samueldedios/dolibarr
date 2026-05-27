<?php
/* Copyright (C) 2024 Samuel de Dios
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \defgroup    ginecoplus     Module ginecoPlus
 * \brief       Ginecología Plus module for Dolibarr
 */

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

class modGinecoPlus extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, icons, etc.
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;
        $this->numero = 500000 + 1; // 500001
        $this->rights_class = 'ginecoplus';
        $this->family = "ecommerce";
        $this->module_position = '90';
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Ginecología Plus module";
        $this->version = '1.0.0';
        $this->author = "Samuel de Dios";
        $this->url_author = "https://github.com/samueldedios";
        $this->need_dolibarr_version = array(15, 0);
        $this->editors_name = array();
        $this->editors_url = array();
        $this->url_documentation = "";
        $this->url_bugtracker = "";
        $this->url_demo = "";
        $this->url_last_test = "";
        $this->module_parts = array(
            'models' => 0,
            'css' => array('/ginecoplus/css/ginecoplus.css'),
            'js' => array('/ginecoplus/js/ginecoplus.js'),
            'triggers' => 0,
            'login' => 0,
            'substitutions' => 0,
            'menus' => 1,
            'theme' => 0,
            'tpl' => 0,
            'barcode' => 0,
            'boxes' => 1,
            'permissions' => 1,
            'social' => 0,
            'modcontratos' => 0,
        );
        $this->config_page_url = array("setup.php@ginecoplus");
        $this->hidden = 0;
        $this->depends = array();
        $this->requiredby = array();
        $this->conflictwith = array();
        $this->langfiles = array("ginecoplus@ginecoplus");
        $this->const_name = 'MAIN_MODULES_GINECOPLUS';
        $this->const_orderby = 'GINECOPLUS_SORT_ORDER';

        if (!isset($conf->ginecoplus) || !isset($conf->ginecoplus->enabled)) {
            $conf->ginecoplus = new stdClass();
            $conf->ginecoplus->enabled = 0;
        }

        $this->rights = array();
        $r = 0;

        $this->rights[$r][0] = $this->numero + $r;
        $this->rights[$r][1] = 'Read ginecoPlus';
        $this->rights[$r][4] = 'read';
        $r++;

        $this->rights[$r][0] = $this->numero + $r;
        $this->rights[$r][1] = 'Create/Update ginecoPlus';
        $this->rights[$r][4] = 'write';
        $r++;

        $this->rights[$r][0] = $this->numero + $r;
        $this->rights[$r][1] = 'Delete ginecoPlus';
        $this->rights[$r][4] = 'delete';
        $r++;

        $this->menus = array();
        $r = 0;

        $this->menus[$r++] = array(
            'fk_menu' => 'fk_mainmenu=home',
            'type' => 'top',
            'titre' => 'GinecoPlus',
            'mainmenu' => 'ginecoplus',
            'leftmenu' => '',
            'url' => '/ginecoplus/ginecoplus_list.php',
            'langs' => 'ginecoplus@ginecoplus',
            'position' => 100,
            'enabled' => '1',
            'perms' => '1',
            'target' => '',
            'user' => 2,
        );
    }

    /**
     * Function called when module is enabled
     *
     * @return    int        1 if OK, 0 if KO
     */
    public function init()
    {
        $result = 0;

        // Permissions
        $this->remove($this->const_name);

        return $result;
    }

    /**
     * Function called when module is disabled
     *
     * @return    int        1 if OK, 0 if KO
     */
    public function remove()
    {
        $sql = array();

        return $this->deleteConfirm($sql);
    }
}