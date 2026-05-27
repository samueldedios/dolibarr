<?php
/* Copyright (C) 2024 GinecoDoli Team. All Rights Reserved.
 * Licensed under GNU/GPL v3 or later.
 *
 * Dashboard principal del módulo GinecoDoli
 */

if (!defined('NOCSRFCHECK')) define('NOCSRFCHECK', 1);
if (!defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', 1);
if (!defined('NOREQUIREMENU')) define('NOREQUIREMENU', 1);
if (!defined('NOREQUIREHTML')) define('NOREQUIREHTML', 1);
if (!defined('NOREQUIREAJAX')) define('NOREQUIREAJAX', 1);

require_once '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/date.lib.php';
require_once __DIR__.'/lib/ginecodoli.lib.php';
require_once __DIR__.'/class/consulta.class.php';

// Verificar permisos
if (!$user->hasRight('ginecodoli', 'read')) {
    accessforbidden();
}

$langs->loadLangs(array('companies', 'members', 'agenda', 'bills'));
$langs->load('ginecodoli@ginecodoli');

$form = new Form($db);
$formcompany = new FormCompany($db);

// Obtener datos del dashboard
$dashboardData = getDashboardData($db, $user);

// ============================================================================
// HEADER Y TÍTULO
// ============================================================================

llxHeader('', $langs->trans("GinecoDoliDashboard"), '', '', '', '', array('/ginecodoli/css/ginecodoli.css'));

print load_fiche_titre($langs->trans("GinecoDoliDashboard"), '', 'consultation@ginecodoli');

// Mensaje de bienvenida personalizado
print '<div class="opacitymedium marginbottomonsmall">';
print $langs->trans("WelcomeDoctor", $user->getFullName($langs)).' - '.$langs->trans("Today").': '.dol_print_date(dol_now(), 'day').'<br>';
print '</div>';
print '<br>';

// ============================================================================
// WIDGETS KPI (ModeleBoxes style)
// ============================================================================

print '<div class="fiche center">';
print '<div class="fichehalfleft">';
print '<div class="boxcontainer">';

// Fila 1 de widgets
print '<div class="boxflexcontainer">';

// Widget 1: Total Pacientes
print '<div class="box boxwidget" style="width: 23%; min-width: 200px;">';
print '<div class="boxheader backgroundwhite">';
print '<span class="boxtitle">'.$langs->trans("TotalPatients").'</span>';
print '</div>';
print '<div class="boxcontent backgroundwhite centpercent">';
print '<div class="boxcontentvalue font-xl tcenter">'.$dashboardData['total_patients'].'</div>';
print '<div class="boxcontentlabel tcenter opacitylow">'.$langs->trans("ActivePatients").'</div>';
print '<div class="tcenter paddingsmall"><a href="'.DOL_URL_ROOT.'/custom/ginecodoli/patient/list.php">'.$langs->trans("ViewAll").'</a></div>';
print '</div>';
print '</div>';

// Widget 2: Consultas este mes
print '<div class="box boxwidget" style="width: 23%; min-width: 200px;">';
print '<div class="boxheader backgroundwhite">';
print '<span class="boxtitle">'.$langs->trans("ConsultationsThisMonth").'</span>';
print '</div>';
print '<div class="boxcontent backgroundwhite centpercent">';
print '<div class="boxcontentvalue font-xl tcenter">'.$dashboardData['consults_this_month'].'</div>';
print '<div class="boxcontentlabel tcenter opacitylow">'.dol_print_date(dol_now(), '%B %Y').'</div>';
print '<div class="tcenter paddingsmall"><a href="'.DOL_URL_ROOT.'/custom/ginecodoli/consulta/list.php">'.$langs->trans("ViewAll").'</a></div>';
print '</div>';
print '</div>';

// Widget 3: Embarazos en curso
print '<div class="box boxwidget" style="width: 23%; min-width: 200px;">';
print '<div class="boxheader backgroundwhite">';
print '<span class="boxtitle">'.$langs->trans("PregnanciesInProgress").'</span>';
print '</div>';
print '<div class="boxcontent backgroundwhite centpercent">';
print '<div class="boxcontentvalue font-xl tcenter">'.$dashboardData['active_pregnancies'].'</div>';
print '<div class="boxcontentlabel tcenter opacitylow">'.$langs->trans("ActivePregnancies").'</div>';
print '<div class="tcenter paddingsmall"><a href="'.DOL_URL_ROOT.'/custom/ginecodoli/embarazo/list.php?search_estado=En%20curso">'.$langs->trans("ViewAll").'</a></div>';
print '</div>';
print '</div>';

// Widget 4: Citas próximas
print '<div class="box boxwidget" style="width: 23%; min-width: 200px;">';
print '<div class="boxheader backgroundwhite">';
print '<span class="boxtitle">'.$langs->trans("UpcomingAppointments").'</span>';
print '</div>';
print '<div class="boxcontent backgroundwhite centpercent">';
print '<div class="boxcontentvalue font-xl tcenter">'.$dashboardData['upcoming_appointments'].'</div>';
print '<div class="boxcontentlabel tcenter opacitylow">'.$langs->trans("Next7Days").'</div>';
print '<div class="tcenter paddingsmall"><a href="'.DOL_URL_ROOT.'/comm/action/index.php?mode=list">'.$langs->trans("ViewAgenda").'</a></div>';
print '</div>';
print '</div>';

print '</div>'; // boxflexcontainer
print '</div>'; // boxcontainer
print '</div>'; // fichehalfleft
print '</div>'; // fiche

print '<div class="clearboth"></div>';
print '<br>';

// ============================================================================
// SECCIÓN PRINCIPAL: ACCESOS RÁPIDOS Y LISTADOS RECIENTES
// ============================================================================

print '<div class="fiche">';
print '<div class="fichehalfleft">';

// ----------------------------------------------------------------------------
// ACCIONES RÁPIDAS
// ----------------------------------------------------------------------------

print load_fiche_titre($langs->trans("QuickActions"), '', '');

print '<div class="div-table-responsive-no-min">';
print '<table class="noborder centpercent">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Action").'</td>';
print '<td class="right">'.$langs->trans("Description").'</td>';
print '</tr>';

// Nueva consulta
print '<tr class="oddeven">';
print '<td>';
if ($user->hasRight('ginecodoli', 'consulta', 'write')) {
    print '<a href="'.DOL_URL_ROOT.'/custom/ginecodoli/consulta/card.php?action=create" class="butActionNew">'.$langs->trans("NewConsultation").'</a>';
} else {
    print $langs->trans("NewConsultation");
}
print '</td>';
print '<td class="right opacitymedium">'.$langs->trans("NewConsultationDesc").'</td>';
print '</tr>';

// Nuevo paciente
print '<tr class="oddeven">';
print '<td>';
if ($user->hasRight('societe', 'creer')) {
    print '<a href="'.DOL_URL_ROOT.'/societe/card.php?action=create&private=1" class="butActionNew">'.$langs->trans("NewPatient").'</a>';
} else {
    print $langs->trans("NewPatient");
}
print '</td>';
print '<td class="right opacitymedium">'.$langs->trans("NewPatientDesc").'</td>';
print '</tr>';

// Nueva cita
print '<tr class="oddeven">';
print '<td>';
if ($user->hasRight('agenda', 'myactions', 'create')) {
    print '<a href="'.DOL_URL_ROOT.'/comm/action/card.php?action=create" class="butActionNew">'.$langs->trans("NewAppointment").'</a>';
} else {
    print $langs->trans("NewAppointment");
}
print '</td>';
print '<td class="right opacitymedium">'.$langs->trans("NewAppointmentDesc").'</td>';
print '</tr>';

// Nuevo embarazo
print '<tr class="oddeven">';
print '<td>';
if ($user->hasRight('ginecodoli', 'embarazo', 'write')) {
    print '<a href="'.DOL_URL_ROOT.'/custom/ginecodoli/embarazo/card.php?action=create" class="butActionNew">'.$langs->trans("NewPregnancy").'</a>';
} else {
    print $langs->trans("NewPregnancy");
}
print '</td>';
print '<td class="right opacitymedium">'.$langs->trans("NewPregnancyDesc").'</td>';
print '</tr>';

// Nuevo examen
print '<tr class="oddeven">';
print '<td>';
if ($user->hasRight('ginecodoli', 'examen', 'write')) {
    print '<a href="'.DOL_URL_ROOT.'/custom/ginecodoli/examen/card.php?action=create" class="butActionNew">'.$langs->trans("NewExam").'</a>';
} else {
    print $langs->trans("NewExam");
}
print '</td>';
print '<td class="right opacitymedium">'.$langs->trans("NewExamDesc").'</td>';
print '</tr>';

print '</table>';
print '</div>';

print '</div>'; // fichehalfleft

// ============================================================================
// COLUMNA DERECHA: CITAS PRÓXIMAS Y PENDIENTES
// ============================================================================

print '<div class="fichehalfright">';

// ----------------------------------------------------------------------------
// PRÓXIMAS CITAS (ActionComm)
// ----------------------------------------------------------------------------

print load_fiche_titre($langs->trans("NextAppointments"), '', 'action');

$sql = "SELECT a.id, a.label, a.datep, a.fk_soc, s.nom as socname";
$sql .= " FROM ".MAIN_DB_PREFIX."actioncomm as a";
$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON a.fk_soc = s.rowid";
$sql .= " WHERE a.entity = ".$conf->entity;
$sql .= " AND a.percentage != 100";
$sql .= " AND a.datep >= '".$db->idate(dol_now())."'";
$sql .= " ORDER BY a.datep ASC";
$sql .= " LIMIT 5";

$resql = $db->query($sql);
if ($resql) {
    $num = $db->num_rows($resql);
    
    print '<div class="div-table-responsive-no-min">';
    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre">';
    print '<td>'.$langs->trans("Date").'</td>';
    print '<td>'.$langs->trans("Label").'</td>';
    print '<td>'.$langs->trans("ThirdParty").'</td>';
    print '</tr>';
    
    if ($num > 0) {
        while ($obj = $db->fetch_object($resql)) {
            print '<tr class="oddeven">';
            print '<td>'.dol_print_date($db->jdate($obj->datep), 'dayhour').'</td>';
            print '<td><a href="'.DOL_URL_ROOT.'/comm/action/card.php?id='.$obj->id.'">'.dol_escape_htmltag($obj->label).'</a></td>';
            print '<td>';
            if ($obj->fk_soc > 0) {
                print '<a href="'.DOL_URL_ROOT.'/societe/card.php?socid='.$obj->fk_soc.'">'.dol_escape_htmltag($obj->socname).'</a>';
            } else {
                print '&nbsp;';
            }
            print '</td>';
            print '</tr>';
        }
    } else {
        print '<tr><td colspan="3" class="opacitymedium tcenter">'.$langs->trans("NoUpcomingAppointments").'</td></tr>';
    }
    
    print '</table>';
    print '</div>';
    
    $db->free($resql);
}

print '<br>';

// ----------------------------------------------------------------------------
// EXÁMENES PENDIENTES DE RESULTADO
// ----------------------------------------------------------------------------

print load_fiche_titre($langs->trans("PendingExamsResults"), '', '');

$sql = "SELECT e.rowid, e.tipo_examen, e.fecha_solicitud, e.fk_soc, s.nom as socname";
$sql .= " FROM ".MAIN_DB_PREFIX."ginecodoli_examen as e";
$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON e.fk_soc = s.rowid";
$sql .= " WHERE e.entity = ".$conf->entity;
$sql .= " AND e.status = 0";
$sql .= " ORDER BY e.fecha_solicitud DESC";
$sql .= " LIMIT 5";

$resql = $db->query($sql);
if ($resql) {
    $num = $db->num_rows($resql);
    
    print '<div class="div-table-responsive-no-min">';
    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre">';
    print '<td>'.$langs->trans("Date").'</td>';
    print '<td>'.$langs->trans("ExamType").'</td>';
    print '<td>'.$langs->trans("Patient").'</td>';
    print '</tr>';
    
    if ($num > 0) {
        while ($obj = $db->fetch_object($resql)) {
            print '<tr class="oddeven">';
            print '<td>'.dol_print_date($db->jdate($obj->fecha_solicitud), 'date').'</td>';
            print '<td>';
            $examTypes = getExamTypes($db);
            print isset($examTypes[$obj->tipo_examen]) ? $examTypes[$obj->tipo_examen] : $obj->tipo_examen;
            print '</td>';
            print '<td><a href="'.DOL_URL_ROOT.'/societe/card.php?socid='.$obj->fk_soc.'">'.dol_escape_htmltag($obj->socname).'</a></td>';
            print '</tr>';
        }
    } else {
        print '<tr><td colspan="3" class="opacitymedium tcenter">'.$langs->trans("NoPendingExams").'</td></tr>';
    }
    
    print '</table>';
    print '</div>';
    
    $db->free($resql);
}

print '</div>'; // fichehalfright
print '</div>'; // fiche

print '<div class="clearboth"></div>';
print '<br>';

// ============================================================================
// ÚLTIMAS CONSULTAS REALIZADAS
// ============================================================================

print load_fiche_titre($langs->trans("LastConsultations"), '', 'consultation@ginecodoli');

$sql = "SELECT c.rowid, c.fecha_consulta, c.tipo_consulta, c.fk_soc, s.nom as socname, c.status";
$sql .= " FROM ".MAIN_DB_PREFIX."ginecodoli_consulta as c";
$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON c.fk_soc = s.rowid";
$sql .= " WHERE c.entity = ".$conf->entity;
$sql .= " ORDER BY c.fecha_consulta DESC";
$sql .= " LIMIT 10";

$resql = $db->query($sql);
if ($resql) {
    $num = $db->num_rows($resql);
    
    print '<div class="div-table-responsive-no-min">';
    print '<table class="noborder centpercent">';
    print '<tr class="liste_titre">';
    print '<td>'.$langs->trans("Date").'</td>';
    print '<td>'.$langs->trans("Patient").'</td>';
    print '<td>'.$langs->trans("ConsultType").'</td>';
    print '<td class="center">'.$langs->trans("Status").'</td>';
    print '<td class="right">&nbsp;</td>';
    print '</tr>';
    
    if ($num > 0) {
        $consultTypes = getConsultTypes($db);
        
        while ($obj = $db->fetch_object($resql)) {
            $consulta = new Consulta($db);
            $consulta->id = $obj->rowid;
            $consulta->status = $obj->status;
            
            print '<tr class="oddeven">';
            print '<td>'.dol_print_date($db->jdate($obj->fecha_consulta), 'date').'</td>';
            print '<td><a href="'.DOL_URL_ROOT.'/societe/card.php?socid='.$obj->fk_soc.'">'.dol_escape_htmltag($obj->socname).'</a></td>';
            print '<td>';
            print isset($consultTypes[$obj->tipo_consulta]) ? $consultTypes[$obj->tipo_consulta] : dol_escape_htmltag($obj->tipo_consulta);
            print '</td>';
            print '<td class="center">'.$consulta->getLibStatut(3).'</td>';
            print '<td class="right"><a href="'.DOL_URL_ROOT.'/custom/ginecodoli/consulta/card.php?id='.$obj->rowid.'">'.img_picto($langs->trans("Show"), 'detail').'</a></td>';
            print '</tr>';
        }
    } else {
        print '<tr><td colspan="5" class="opacitymedium tcenter">'.$langs->trans("NoConsultationsYet").'</td></tr>';
    }
    
    print '</table>';
    print '</div>';
    
    $db->free($resql);
}

// ============================================================================
// FOOTER
// ============================================================================

llxFooter();
$db->close();
