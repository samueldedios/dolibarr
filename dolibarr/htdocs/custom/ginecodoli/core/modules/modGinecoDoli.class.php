<?php
/* Copyright (C) 2024 GinecoDoli Team. All Rights Reserved.
 * Licensed under GNU/GPL v3 or later.
 *
 * Class: modGinecoDoli
 * Description: Descriptor class for GinecoDoli module
 */

include_once DOL_DOCUMENT_ROOT.'/core/modules/DolibarrModules.class.php';

/**
 * Class to describe and configure GinecoDoli module
 */
class modGinecoDoli extends DolibarrModules
{
    /**
     * Constructor
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        $this->db = $db;

        // ID del módulo (usar rango alto para no colisionar)
        $this->numero = 100500;

        // Nombre técnico
        $this->name = preg_replace('/^mod/i', '', get_class($this));

        // Descripción
        $this->description = "Módulo de gestión ginecológica: pacientes, consultas, embarazos, anticoncepción y exámenes.";

        // Editor
        $this->editor_name = 'GinecoDoli Team';
        $this->editor_url = 'https://ginecodoli.example.com';

        // Versión
        $version = dol_get_version();
        $this->version = '1.0.0';

        // ID de permisos (rango alto)
        $this->rights_class = 'ginecodoli';

        // Menú
        $this->module_parts = array(
            'trigger' => 1,
            'css' => array('/ginecodoli/css/ginecodoli.css'),
            'js' => array(),
            'hooks' => array('thirdpartycard', 'agenda'),
            'tpl' => 0,
            'theme' => 0,
            'entities' => array(1)
        );

        // Carpetas de datos
        $this->dirs = array(
            "/ginecodoli/temp",
            "/ginecodoli/docs"
        );

        // Dependencias
        $this->depends = array();
        $this->requiredby = array();
        $this->conflictwith = array();
        $this->langfiles = array("ginecodoli@ginecodoli");

        // Constantes
        $this->const = array(
            0 => array(
                'GINECODOLI_VERSION',
                'chaine',
                '1.0.0',
                'Versión del módulo GinecoDoli',
                0,
                'current'
            ),
            1 => array(
                'GINECODOLI_PATIENT_CATEGORY',
                'chaine',
                'Pacientes Ginecología',
                'Nombre de categoría para pacientes',
                0,
                'current'
            )
        );

        // Cajas (Widgets Dashboard)
        $this->boxes = array(
            0 => array(
                'file' => 'box_ginecodoli_patients.php@ginecodoli',
                'note' => 'Widget: Total de pacientes ginecológicos'
            ),
            1 => array(
                'file' => 'box_ginecodoli_consults.php@ginecodoli',
                'note' => 'Widget: Consultas del mes'
            ),
            2 => array(
                'file' => 'box_ginecodoli_pregnancies.php@ginecodoli',
                'note' => 'Widget: Embarazos en curso'
            ),
            3 => array(
                'file' => 'box_ginecodoli_appointments.php@ginecodoli',
                'note' => 'Widget: Citas próximas'
            )
        );

        // Permisos
        $this->rights = array();
        $r = 0;

        // Permiso: Leer módulo
        $r++;
        $this->rights[$r][0] = 100501;
        $this->rights[$r][1] = 'Leer el módulo GinecoDoli';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'read';

        // Permiso: Ver dashboard
        $r++;
        $this->rights[$r][0] = 100502;
        $this->rights[$r][1] = 'Ver dashboard ginecológico';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'dashboard';

        // Permiso: Ver pacientes
        $r++;
        $this->rights[$r][0] = 100510;
        $this->rights[$r][1] = 'Ver pacientes';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'patient';
        $this->rights[$r][6] = 'lire';

        // Permiso: Crear/Editar pacientes
        $r++;
        $this->rights[$r][0] = 100511;
        $this->rights[$r][1] = 'Crear/Editar pacientes';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'patient';
        $this->rights[$r][6] = 'write';

        // Permiso: Ver consultas
        $r++;
        $this->rights[$r][0] = 100520;
        $this->rights[$r][1] = 'Ver consultas';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'consulta';
        $this->rights[$r][6] = 'lire';

        // Permiso: Crear/Editar consultas
        $r++;
        $this->rights[$r][0] = 100521;
        $this->rights[$r][1] = 'Crear/Editar consultas';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'consulta';
        $this->rights[$r][6] = 'write';

        // Permiso: Eliminar consultas
        $r++;
        $this->rights[$r][0] = 100522;
        $this->rights[$r][1] = 'Eliminar consultas';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'consulta';
        $this->rights[$r][6] = 'delete';

        // Permiso: Ver embarazos
        $r++;
        $this->rights[$r][0] = 100530;
        $this->rights[$r][1] = 'Ver embarazos';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'embarazo';
        $this->rights[$r][6] = 'lire';

        // Permiso: Crear/Editar embarazos
        $r++;
        $this->rights[$r][0] = 100531;
        $this->rights[$r][1] = 'Crear/Editar embarazos';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'embarazo';
        $this->rights[$r][6] = 'write';

        // Permiso: Ver exámenes
        $r++;
        $this->rights[$r][0] = 100540;
        $this->rights[$r][1] = 'Ver exámenes';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'examen';
        $this->rights[$r][6] = 'lire';

        // Permiso: Crear/Editar exámenes
        $r++;
        $this->rights[$r][0] = 100541;
        $this->rights[$r][1] = 'Crear/Editar exámenes';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'examen';
        $this->rights[$r][6] = 'write';

        // Permiso: Ver cirugías
        $r++;
        $this->rights[$r][0] = 100550;
        $this->rights[$r][1] = 'Ver cirugías/procedimientos';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'cirugia';
        $this->rights[$r][6] = 'lire';

        // Permiso: Crear/Editar cirugías
        $r++;
        $this->rights[$r][0] = 100551;
        $this->rights[$r][1] = 'Crear/Editar cirugías/procedimientos';
        $this->rights[$r][4] = 'ginecodoli';
        $this->rights[$r][5] = 'cirugia';
        $this->rights[$r][6] = 'write';

        // Menús
        $this->menu = array();

        // Menú principal (nivel 0)
        $h = 0;
        
        // Entrada al módulo (Dashboard)
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'GinecoDoli',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'ginecodoli',
            'url' => '/ginecodoli/index.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'read\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Dashboard
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Dashboard',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'dashboard',
            'url' => '/ginecodoli/index.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'dashboard\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Pacientes
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Patients',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'patients',
            'url' => '/ginecodoli/patient/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'patient\', \'lire\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Citas (Agenda nativa)
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Appointments',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'appointments',
            'url' => '/comm/action/index.php?mode=list&search_type=-1,-2',
            'langs' => 'agenda@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled && $conf->agenda->enabled',
            'perms' => '$user->hasRight(\'agenda\', \'myactions\', \'read\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Consultas
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Consultations',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'consultas',
            'url' => '/ginecodoli/consulta/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'consulta\', \'lire\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Embarazos
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Pregnancies',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'embarazos',
            'url' => '/ginecodoli/embarazo/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'embarazo\', \'lire\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Anticoncepción
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Contraception',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'anticoncepcion',
            'url' => '/ginecodoli/anticoncepcion/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'read\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Exámenes
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Exams',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'examenes',
            'url' => '/ginecodoli/examen/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'examen\', \'lire\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Cirugías
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Surgeries',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'cirugias',
            'url' => '/ginecodoli/cirugia/list.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'cirugia\', \'lire\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Reportes
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli',
            'type' => 'top',
            'titre' => 'Reports',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'reports',
            'url' => '/ginecodoli/report/index.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->hasRight(\'ginecodoli\', \'read\')',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Configuración (submenu)
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli,fk_leftmenu=settings',
            'type' => 'left',
            'titre' => 'Setup',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'settings',
            'url' => '/ginecodoli/admin/ginecodoli_setup.php',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2
        );
        $h++;

        // Diccionarios (submenu)
        $this->menu[$h] = array(
            'fk_menu' => 'fk_mainmenu=ginecodoli,fk_leftmenu=settings',
            'type' => 'left',
            'titre' => 'DictionarySetup',
            'mainmenu' => 'ginecodoli',
            'leftmenu' => 'dict',
            'url' => '/admin/dict.php?search_keyword=gynecology',
            'langs' => 'ginecodoli@ginecodoli',
            'position' => 100 + $h,
            'enabled' => '$conf->ginecodoli->enabled',
            'perms' => '$user->admin',
            'target' => '',
            'user' => 2
        );
        $h++;

        // SQL initialization
        $this->sql = array(
            0 => 'llx_ginecodoli.sql'
        );
    }

    /**
     * Function called when module is enabled
     *
     * @param   int     $options    Options
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function init($options = '')
    {
        global $user;

        $sql = array();

        // Crear carpeta de documentos
        dol_mkdir(DOL_DATA_ROOT.'/ginecodoli');

        return $this->_init($sql, $options);
    }

    /**
     * Function called when module is disabled
     *
     * @param   int     $options    Options
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function remove($options = '')
    {
        $sql = array();

        return $this->_remove($sql, $options);
    }
}
