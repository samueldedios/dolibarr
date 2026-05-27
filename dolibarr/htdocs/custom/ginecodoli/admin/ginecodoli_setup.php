<?php
/* Copyright (C) 2024 GinecoDoli Team. All Rights Reserved.
 * Licensed under GNU/GPL v3 or later.
 *
 * Módulo: GinecoDoli - Gestión Ginecológica para Dolibarr
 * Descripción: Página de configuración del módulo
 */

if (!defined('NOCSRFCHECK')) define('NOCSRFCHECK', 1);
if (!defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', 1);
if (!defined('NOREQUIREMENU')) define('NOREQUIREMENU', 1);
if (!defined('NOREQUIREHTML')) define('NOREQUIREHTML', 1);
if (!defined('NOREQUIREAJAX')) define('NOREQUIREAJAX', 1);
if (!defined('NOLOGIN')) define('NOLOGIN', 1);
if (!defined('NOIPCHECK')) define('NOIPCHECK', '1');

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT.'/categories/class/categorie.class.php';

// Verificar permisos básicos
if (!$user->hasRight('ginecodoli', 'read')) {
    accessforbidden();
}

// Idioma
$langs->loadLangs(array('admin', 'companies', 'members', 'agenda'));
$langs->load('ginecodoli@ginecodoli');

// Inicialización de variables
$action = GETPOST('action', 'aZ09');
$confirm = GETPOST('confirm', 'alpha');
$id = GETPOST('id', 'int');
$backtopage = GETPOST('backtopage', 'alpha');

$form = new Form($db);

// ============================================================================
// VISTA DE CONFIGURACIÓN / SETUP DEL MÓDULO
// ============================================================================

$help_url = '';
$title = $langs->trans("GinecoDoliSetup");
llxHeader('', $title, $help_url);

print load_fiche_titre($langs->trans("GinecoDoliSetup"), '', 'setup');

print '<br>';

// Mensajes de confirmación
setEventMessages($langs->trans("GinecoDoliWelcome"), null, 'mesgs');

// ----------------------------------------------------------------------------
// Pestañas de configuración
// ----------------------------------------------------------------------------
$head = array();
$h = 0;

$head[$h][0] = dol_buildpath("/ginecodoli/admin/ginecodoli_setup.php", 1).'?action=setup';
$head[$h][1] = $langs->trans("Parameters");
$head[$h][2] = 'setup';
$h++;

$head[$h][0] = dol_buildpath("/ginecodoli/admin/ginecodoli_setup.php", 1).'?action=dict';
$head[$h][1] = $langs->trans("Dictionaries");
$head[$h][2] = 'dict';
$h++;

$head[$h][0] = dol_buildpath("/ginecodoli/admin/ginecodoli_setup.php", 1).'?action=extrafields';
$head[$h][1] = $langs->trans("ExtraFields");
$head[$h][2] = 'extrafields';
$h++;

print dol_get_fiche_head($head, 'setup', $langs->trans("ModuleSetup"), -1, 'module@ginecodoli');

// ============================================================================
// SECCIÓN 1: PARÁMETROS GENERALES
// ============================================================================

print load_fiche_titre($langs->trans("GeneralSetup"), '', '');
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameter").'</td>';
print '<td>'.$langs->trans("Value").'</td>';
print '<td>'.$langs->trans("Description").'</td>';
print '</tr>';

// Parámetro: Categoría por defecto para pacientes
print '<tr class="oddeven">';
print '<td>'.$langs->trans("DefaultPatientCategory").'</td>';
print '<td>';
// Buscamos la categoría "Pacientes Ginecología"
$sql = "SELECT rowid, label FROM ".MAIN_DB_PREFIX."categorie WHERE type = 2 AND label LIKE '%Ginecología%'";
$resql = $db->query($sql);
if ($resql && $db->num_rows($resql) > 0) {
    $obj = $db->fetch_object($resql);
    print img_picto('', 'category').$obj->label.' (ID: '.$obj->rowid.')';
} else {
    print colorBlock('orange', $langs->trans("CategoryNotFound"));
}
print '</td>';
print '<td class="opacitymedium">'.$langs->trans("CategoryForPatientsDesc").'</td>';
print '</tr>';

// Parámetro: Extrafields configurados
print '<tr class="oddeven">';
print '<td>'.$langs->trans("SocieteExtraFields").'</td>';
print '<td>';
$extrafields = new ExtraFields($db);
$ef_list = $extrafields->fetch_name_optionals_label('societe');
if (is_array($ef_list) && count($ef_list) > 0) {
    $gyn_fields = array();
    foreach ($ef_list as $key => $label) {
        if (preg_match('/(menarquia|fum|gpc|gestas|partos)/i', $key)) {
            $gyn_fields[] = $label;
        }
    }
    if (count($gyn_fields) > 0) {
        print implode(', ', $gyn_fields);
    } else {
        print $langs->trans("NoGynExtraFields");
    }
} else {
    print $langs->trans("NoExtraFields");
}
print '</td>';
print '<td class="opacitymedium">'.$langs->trans("ExtraFieldsForGynecology").'</td>';
print '</tr>';

print '</table>';

// ============================================================================
// SECCIÓN 2: DICCIONARIOS DISPONIBLES
// ============================================================================

print '<br>';
print load_fiche_titre($langs->trans("AvailableDictionaries"), '', '');

print '<div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Dictionary").'</td>';
print '<td>'.$langs->trans("Code").'</td>';
print '<td class="center">'.$langs->trans("Status").'</td>';
print '<td>&nbsp;</td>';
print '</tr>';

$dicts = array(
    'llx_c_gynecology_consult_type' => 'TiposConsulta',
    'llx_c_gynecology_contraceptive' => 'MetodosAnticonceptivos',
    'llx_c_gynecology_exam_type' => 'TiposExamenes',
    'llx_c_gynecology_surgery' => 'ProcedimientosQuirurgicos'
);

foreach ($dicts as $table => $label) {
    // Verificar si la tabla existe
    $sql = "SHOW TABLES LIKE '".$table."'";
    $resql = $db->query($sql);
    $exists = ($resql && $db->num_rows($resql) > 0);
    
    print '<tr class="oddeven">';
    print '<td>'.$langs->trans($label).'</td>';
    print '<td><span class="opacitymedium">'.$table.'</span></td>';
    print '<td class="center">'.($exists ? img_picto($langs->trans("Active"), 'switch_on', 'class="green"') : img_picto($langs->trans("Inactive"), 'switch_off', 'class="red"')).'</td>';
    print '<td class="right">';
    if ($exists) {
        print '<a href="'.DOL_URL_ROOT.'/admin/dict.php?search_keyword='.urlencode($table).'">'.img_picto('', 'edit').'</a>';
    }
    print '</td>';
    print '</tr>';
}

print '</table>';
print '</div>';

// ============================================================================
// SECCIÓN 3: BOTONES DE ACCIÓN RÁPIDA
// ============================================================================

print '<br>';
print load_fiche_titre($langs->trans("QuickActions"), '', '');

print '<div class="tabsAction">';

// Botón: Crear categoría si no existe
print '<a class="butAction" href="'.$_SERVER['PHP_SELF'].'?action=create_category&token='.newToken().'">'.$langs->trans("CreatePatientCategory").'</a>';

// Botón: Añadir extrafields si faltan
print '<a class="butAction" href="'.$_SERVER['PHP_SELF'].'?action=add_extrafields&token='.newToken().'">'.$langs->trans("AddGynExtraFields").'</a>';

// Botón: Ir a lista de pacientes
if ($user->hasRight('societe', 'lire')) {
    print '<a class="butAction" href="'.DOL_URL_ROOT.'/societe/list.php?search_category_all_list=Pacientes%20Ginecolog%C3%ADa">'.$langs->trans("ViewPatients").'</a>';
}

// Botón: Nueva consulta
if ($user->hasRight('ginecodoli', 'consulta', 'write')) {
    print '<a class="butAction" href="'.DOL_URL_ROOT.'/custom/ginecodoli/consulta/card.php?action=create">'.$langs->trans("NewConsultation").'</a>';
}

print '</div>';

print dol_get_fiche_end();

// ============================================================================
// PROCESAMIENTO DE ACCIONES
// ============================================================================

if ($action == 'create_category' && $user->admin) {
    $cat = new Categorie($db);
    $cat->label = 'Pacientes Ginecología';
    $cat->type = Categorie::TYPE_CUSTOMER; // Tipo 2 = Clientes/Pacientes
    $cat->color = '#FF6B6B'; // Color rosado médico
    $cat->description = 'Categoría automática para pacientes del módulo GinecoDoli';
    
    $result = $cat->create($user);
    if ($result > 0) {
        setEventMessages($langs->trans("CategoryCreated"), null, 'mesgs');
    } else {
        setEventMessages($cat->error, $cat->errors, 'errors');
    }
    redirect($_SERVER['PHP_SELF']);
}

if ($action == 'add_extrafields' && $user->admin) {
    $extrafields = new ExtraFields($db);
    
    // Definición de extrafields necesarios para Societe (Pacientes)
    $ef_defs = array(
        'gyn_menarquia' => array(
            'label' => 'Menarquia',
            'type' => 'integer',
            'size' => 3,
            'pos' => 1,
            'required' => 0,
            'computed' => '',
            'help' => 'Edad de la primera menstruación (años)'
        ),
        'gyn_fum' => array(
            'label' => 'FUM',
            'type' => 'date',
            'size' => '',
            'pos' => 2,
            'required' => 0,
            'computed' => '',
            'help' => 'Fecha de Última Menstruación'
        ),
        'gyn_gestas' => array(
            'label' => 'Gestas (G)',
            'type' => 'integer',
            'size' => 2,
            'pos' => 3,
            'required' => 0,
            'computed' => ''
        ),
        'gyn_partos' => array(
            'label' => 'Partos (P)',
            'type' => 'integer',
            'size' => 2,
            'pos' => 4,
            'required' => 0,
            'computed' => ''
        ),
        'gyn_cesareas' => array(
            'label' => 'Cesáreas (C)',
            'type' => 'integer',
            'size' => 2,
            'pos' => 5,
            'required' => 0,
            'computed' => ''
        ),
        'gyn_abortos' => array(
            'label' => 'Abortos (A)',
            'type' => 'integer',
            'size' => 2,
            'pos' => 6,
            'required' => 0,
            'computed' => ''
        ),
        'gyn_grupo_sanguineo' => array(
            'label' => 'Grupo Sanguíneo',
            'type' => 'varchar',
            'size' => 5,
            'pos' => 7,
            'required' => 0,
            'computed' => ''
        ),
        'gyn_factor_rh' => array(
            'label' => 'Factor RH',
            'type' => 'sellist',
            'size' => '',
            'pos' => 8,
            'required' => 0,
            'computed' => '',
            'list' => 'positive:negative'
        )
    );
    
    $count = 0;
    foreach ($ef_defs as $code => $def) {
        // Verificar si ya existe
        $existing = $extrafields->fetch_name_optionals_label('societe');
        if (!array_key_exists($code, $existing)) {
            $result = $extrafields->addExtraField($code, $def['label'], $def['type'], $def['pos'], 
                $def['size'], 'societe', $def['required'], $def['computed'], $def['help'], 
                '', 0, '', 1, '', '', $def['list'] ?? '');
            if ($result > 0) $count++;
        }
    }
    
    if ($count > 0) {
        setEventMessages($langs->trans("ExtraFieldsAdded", $count), null, 'mesgs');
    } else {
        setEventMessages($langs->trans("ExtraFieldsAlreadyExist"), null, 'warnings');
    }
    
    redirect($_SERVER['PHP_SELF']);
}

llxFooter();
$db->close();
