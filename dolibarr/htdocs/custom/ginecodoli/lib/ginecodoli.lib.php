<?php
/* Copyright (C) 2024 GinecoDoli Team. All Rights Reserved.
 * Licensed under GNU/GPL v3 or later.
 *
 * Librería: ginecodoli.lib.php
 * Descripción: Funciones comunes y librerías para el módulo GinecoDoli
 */

/**
 * Preparar array de pestañas para ficha de consulta
 *
 * @param   Consulta    $object     Object Consulta
 * @return  array                   Array of tabs
 */
function consulta_prepare_head($object)
{
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    // Pestaña principal: Consulta
    $head[$h][0] = dol_buildpath('/ginecodoli/consulta/card.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("Consultation");
    $head[$h][2] = 'consultation';
    $h++;

    // Pestaña: Exámenes relacionados
    if ($user->hasRight('ginecodoli', 'examen', 'lire')) {
        $head[$h][0] = dol_buildpath('/ginecodoli/examen/list.php', 1).'?fk_consulta='.$object->id;
        $head[$h][1] = $langs->trans("Exams");
        $head[$h][2] = 'exams';
        $h++;
    }

    // Pestaña: Notas / Documentos
    $head[$h][0] = dol_buildpath('/ginecodoli/consulta/document.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("Documents");
    $head[$h][2] = 'documents';
    $h++;

    return $head;
}

/**
 * Return array of tabs for pregnancy object
 *
 * @param   Embarazo    $object     Object Embarazo
 * @return  array                   Array of tabs
 */
function embarazo_prepare_head($object)
{
    global $langs, $conf, $user;

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath('/ginecodoli/embarazo/card.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans("Pregnancy");
    $head[$h][2] = 'pregnancy';
    $h++;

    // Controles prenatales (consultas vinculadas)
    $head[$h][0] = dol_buildpath('/ginecodoli/consulta/list.php', 1).'?fk_embarazo='.$object->id;
    $head[$h][1] = $langs->trans("PrenatalControls");
    $head[$h][2] = 'controls';
    $h++;

    return $head;
}

/**
 * Get list of contraceptive methods from dictionary
 *
 * @param   DoliDB    $db         Database handler
 * @param   string    $type       Filter by type (Hormonal, DIU, etc.)
 * @return  array                 Array of methods
 */
function getContraceptiveMethods($db, $type = '')
{
    $methods = array();
    
    $sql = "SELECT rowid, code, label, type FROM ".MAIN_DB_PREFIX."c_gynecology_contraceptive";
    $sql .= " WHERE active = 1";
    if (!empty($type)) {
        $sql .= " AND type = '".$db->escape($type)."'";
    }
    $sql .= " ORDER BY position ASC, label ASC";
    
    $resql = $db->query($sql);
    if ($resql) {
        while ($obj = $db->fetch_object($resql)) {
            $methods[$obj->code] = $obj->label;
        }
        $db->free($resql);
    }
    
    return $methods;
}

/**
 * Get list of consult types from dictionary
 *
 * @param   DoliDB    $db         Database handler
 * @return  array                 Array of types
 */
function getConsultTypes($db)
{
    $types = array();
    
    $sql = "SELECT rowid, code, label FROM ".MAIN_DB_PREFIX."c_gynecology_consult_type";
    $sql .= " WHERE active = 1";
    $sql .= " ORDER BY position ASC, label ASC";
    
    $resql = $db->query($sql);
    if ($resql) {
        while ($obj = $db->fetch_object($resql)) {
            $types[$obj->code] = $obj->label;
        }
        $db->free($resql);
    }
    
    return $types;
}

/**
 * Get list of exam types from dictionary
 *
 * @param   DoliDB    $db         Database handler
 * @return  array                 Array of types
 */
function getExamTypes($db)
{
    $types = array();
    
    $sql = "SELECT rowid, code, label FROM ".MAIN_DB_PREFIX."c_gynecology_exam_type";
    $sql .= " WHERE active = 1";
    $sql .= " ORDER BY position ASC, label ASC";
    
    $resql = $db->query($sql);
    if ($resql) {
        while ($obj = $db->fetch_object($resql)) {
            $types[$obj->code] = $obj->label;
        }
        $db->free($resql);
    }
    
    return $types;
}

/**
 * Get list of surgery procedures from dictionary
 *
 * @param   DoliDB    $db         Database handler
 * @return  array                 Array of procedures
 */
function getSurgeryProcedures($db)
{
    $procedures = array();
    
    $sql = "SELECT rowid, code, label FROM ".MAIN_DB_PREFIX."c_gynecology_surgery";
    $sql .= " WHERE active = 1";
    $sql .= " ORDER BY position ASC, label ASC";
    
    $resql = $db->query($sql);
    if ($resql) {
        while ($obj = $db->fetch_object($resql)) {
            $procedures[$obj->code] = $obj->label;
        }
        $db->free($resql);
    }
    
    return $procedures;
}

/**
 * Calculate gestational age from FUM
 *
 * @param   string  $fum        FUM date (YYYY-MM-DD)
 * @param   string  $ref_date   Reference date (default today)
 * @return  array               Array with weeks and days
 */
function calculateGestationalAge($fum, $ref_date = '')
{
    if (empty($fum)) return array('weeks' => 0, 'days' => 0);
    
    if (empty($ref_date)) {
        $ref_date = dol_now();
    } elseif (!is_numeric($ref_date)) {
        $ref_date = strtotime($ref_date);
    }
    
    $fum_ts = strtotime($fum);
    if (!$fum_ts) return array('weeks' => 0, 'days' => 0);
    
    $diff_seconds = $ref_date - $fum_ts;
    $diff_days = floor($diff_seconds / (60 * 60 * 24));
    
    $weeks = floor($diff_days / 7);
    $days = $diff_days % 7;
    
    return array('weeks' => $weeks, 'days' => $days);
}

/**
 * Calculate estimated due date (FPP) from FUM
 *
 * @param   string  $fum    FUM date (YYYY-MM-DD)
 * @return  string          EDD date (YYYY-MM-DD) or empty
 */
function calculateEDD($fum)
{
    if (empty($fum)) return '';
    
    $fum_ts = strtotime($fum);
    if (!$fum_ts) return '';
    
    // Regla de Naegele: FUM + 280 días (40 semanas)
    $edd_ts = strtotime('+280 days', $fum_ts);
    return date('Y-m-d', $edd_ts);
}

/**
 * Format gestational age for display
 *
 * @param   int     $weeks      Weeks
 * @param   int     $days       Days
 * @return  string              Formatted string
 */
function formatGestationalAge($weeks, $days)
{
    global $langs;
    
    $text = $weeks.' '.$langs->trans("Weeks");
    if ($days > 0) {
        $text .= ' '.$days.' '.$langs->trans("Days");
    }
    
    return $text;
}

/**
 * Get IMC interpretation
 *
 * @param   float   $imc    IMC value
 * @return  string          Interpretation label
 */
function getIMCInterpretation($imc)
{
    global $langs;
    
    if ($imc < 18.5) return $langs->trans("Underweight");
    if ($imc < 25) return $langs->trans("NormalWeight");
    if ($imc < 30) return $langs->trans("Overweight");
    return $langs->trans("Obesity");
}

/**
 * Prepare array with dashboard widgets data
 *
 * @param   DoliDB    $db         Database handler
 * @param   User      $user       User object
 * @return  array                 Array of widget data
 */
function getDashboardData($db, $user)
{
    global $conf;
    
    $data = array();
    
    // Total pacientes (societe con categoría ginecología)
    $sql = "SELECT COUNT(DISTINCT s.rowid) as nb";
    $sql .= " FROM ".MAIN_DB_PREFIX."societe as s";
    $sql .= " LEFT JOIN ".MAIN_DB_PREFIX."categorie_societe as cs ON s.rowid = cs.fk_soc";
    $sql .= " LEFT JOIN ".MAIN_DB_PREFIX."categorie as c ON cs.fk_categorie = c.rowid";
    $sql .= " WHERE s.entity = ".$conf->entity;
    $sql .= " AND (c.label LIKE '%Ginecología%' OR s.client > 0)";
    
    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        $data['total_patients'] = $obj->nb;
        $db->free($resql);
    }
    
    // Consultas este mes
    $sql = "SELECT COUNT(*) as nb";
    $sql .= " FROM ".MAIN_DB_PREFIX."ginecodoli_consulta";
    $sql .= " WHERE entity = ".$conf->entity;
    $sql .= " AND MONTH(fecha_consulta) = ".date('m');
    $sql .= " AND YEAR(fecha_consulta) = ".date('Y');
    
    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        $data['consults_this_month'] = $obj->nb;
        $db->free($resql);
    }
    
    // Embarazos en curso
    $sql = "SELECT COUNT(*) as nb";
    $sql .= " FROM ".MAIN_DB_PREFIX."ginecodoli_embarazo";
    $sql .= " WHERE entity = ".$conf->entity;
    $sql .= " AND estado = 'En curso'";
    
    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        $data['active_pregnancies'] = $obj->nb;
        $db->free($resql);
    }
    
    // Citas próximas (actioncomm)
    $sql = "SELECT COUNT(*) as nb";
    $sql .= " FROM ".MAIN_DB_PREFIX."actioncomm";
    $sql .= " WHERE entity = ".$conf->entity";
    $sql .= " AND percentage != 100";
    $sql .= " AND datep >= '".$db->idate(dol_now())."'";
    
    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        $data['upcoming_appointments'] = $obj->nb;
        $db->free($resql);
    }
    
    // Exámenes pendientes de resultado
    $sql = "SELECT COUNT(*) as nb";
    $sql .= " FROM ".MAIN_DB_PREFIX."ginecodoli_examen";
    $sql .= " WHERE entity = ".$conf->entity;
    $sql .= " AND status = 0";
    
    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        $data['pending_exams'] = $obj->nb;
        $db->free($resql);
    }
    
    return $data;
}

/**
 * Check if patient has active pregnancy
 *
 * @param   DoliDB    $db         Database handler
 * @param   int       $soc_id     Society ID
 * @return  bool                  True if pregnant
 */
function patientIsPregnant($db, $soc_id)
{
    $sql = "SELECT rowid FROM ".MAIN_DB_PREFIX."ginecodoli_embarazo";
    $sql .= " WHERE fk_soc = ".((int) $soc_id);
    $sql .= " AND estado = 'En curso'";
    $sql .= " AND status = 1";
    
    $resql = $db->query($sql);
    if ($resql && $db->num_rows($resql) > 0) {
        return true;
    }
    
    return false;
}

/**
 * Get last consultation for patient
 *
 * @param   DoliDB    $db         Database handler
 * @param   int       $soc_id     Society ID
 * @return  Consulta|null         Last consultation object or null
 */
function getLastConsultation($db, $soc_id)
{
    require_once __DIR__.'/../class/consulta.class.php';
    
    $sql = "SELECT rowid FROM ".MAIN_DB_PREFIX."ginecodoli_consulta";
    $sql .= " WHERE fk_soc = ".((int) $soc_id);
    $sql .= " ORDER BY fecha_consulta DESC";
    $sql .= " LIMIT 1";
    
    $resql = $db->query($sql);
    if ($resql && $db->num_rows($resql) > 0) {
        $obj = $db->fetch_object($resql);
        $consulta = new Consulta($db);
        $consulta->fetch($obj->rowid);
        return $consulta;
    }
    
    return null;
}
