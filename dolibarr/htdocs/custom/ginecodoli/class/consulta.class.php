<?php
/* Copyright (C) 2024 GinecoDoli Team. All Rights Reserved.
 * Licensed under GNU/GPL v3 or later.
 *
 * Clase: Consulta
 * Descripción: Gestión de consultas médicas ginecológicas
 */

require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';

/**
 * Class Consulta
 * Classe pour gérer les consultations gynécologiques
 */
class Consulta extends CommonObject
{
    const TABLE_NAME = 'ginecodoli_consulta';
    const STATUS_DRAFT = 1;
    const STATUS_VALIDATED = 2;
    const STATUS_CANCELLED = 0;

    /**
     * @var DoliDB Database handler
     */
    public $db;

    /**
     * @var string ID to identify founded object
     */
    public $element = 'consulta';

    /**
     * @var string Name of table without prefix where object is stored
     */
    public $table_element = 'ginecodoli_consulta';

    /**
     * @var int Is this a child of another entity
     */
    public $isextrafieldmanaged = 1;

    /**
     * @var string Name of icon for consulta
     */
    public $picto = 'consultation@ginecodoli';

    /**
     * @var string String with name of icon for consulta
     */
    protected $childtableoncascade = array();

    const ELEMENT_LABEL = 'Consulta';

    /**
     * Rowid
     * @var int
     */
    public $rowid;

    /**
     * Entity
     * @var int
     */
    public $entity;

    /**
     * Date creation
     * @var datetime
     */
    public $date_creation;

    /**
     * Date modification
     * @var timestamp
     */
    public $tms;

    /**
     * User creation
     * @var int
     */
    public $fk_user_creat;

    /**
     * User modification
     * @var int
     */
    public $fk_user_modif;

    /**
     * Patient ID (link to llx_societe)
     * @var int
     */
    public $fk_soc;

    /**
     * Link to actioncomm (cita)
     * @var int
     */
    public $fk_actioncomm;

    /**
     * Fecha de la consulta
     * @var date
     */
    public $fecha_consulta;

    /**
     * Tipo de consulta (código del diccionario)
     * @var string
     */
    public $tipo_consulta;

    /**
     * Motivo de consulta
     * @var text
     */
    public $motivo_consulta;

    /**
     * Fecha Última Menstruación
     * @var date
     */
    public $fum;

    /**
     * Fecha Última Papanicolaou
     * @var date
     */
    public $fup;

    /**
     * Tensión arterial sistólica
     * @var varchar
     */
    public $ta_sistolica;

    /**
     * Tensión arterial diastólica
     * @var varchar
     */
    public $ta_diastolica;

    /**
     * Peso en kg
     * @var decimal
     */
    public $peso;

    /**
     * Talla en cm
     * @var decimal
     */
    public $talla;

    /**
     * Índice de Masa Corporal
     * @var decimal
     */
    public $imc;

    /**
     * Hallazgos mamas - inspección
     * @var text
     */
    public $mama_inspeccion;

    /**
     * Hallazgos mamas - palpación
     * @var text
     */
    public $mama_palpacion;

    /**
     * Hallazgos cuello/vagina
     * @var text
     */
    public $especuloscopia;

    /**
     * Hallazgos útero/anexos
     * @var text
     */
    public $tacto_vaginal;

    /**
     * Posición del útero
     * @var varchar
     */
    public $utero_posicion;

    /**
     * Tamaño del útero
     * @var varchar
     */
    public $utero_tamano;

    /**
     * Hallazgos anexos
     * @var text
     */
    public $anexos;

    /**
     * Diagnóstico
     * @var text
     */
    public $diagnostico;

    /**
     * Plan de tratamiento
     * @var text
     */
    public $plan_tratamiento;

    /**
     * Medicamentos recetados
     * @var text
     */
    public $medicamentos;

    /**
     * Status
     * @var int
     */
    public $status;

    /**
     * Import key
     * @var string
     */
    public $import_key;


    /**
     * Constructor
     *
     * @param   DoliDB    $db     Database handler
     */
    public function __construct(DoliDB $db)
    {
        $this->db = $db;
    }

    /**
     * Create object in database
     *
     * @param   User    $user       User that creates
     * @param   int     $notrigger  0=launch triggers, 1=disable triggers
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function create(User $user, $notrigger = false)
    {
        $this->date_creation = dol_now();
        $this->fk_user_creat = $user->id;
        
        // Calcular IMC automáticamente si hay peso y talla
        if (!empty($this->peso) && !empty($this->talla) && $this->talla > 0) {
            $talla_m = $this->talla / 100;
            $this->imc = round($this->peso / ($talla_m * $talla_m), 2);
        }

        return $this->createCommon($user, $notrigger);
    }

    /**
     * Load object from database
     *
     * @param   int     $id         Id of object
     * @param   string  $ref        Reference
     * @param   string  $morewhere  More SQL filters
     * @return  int                 Return integer <0 if KO, 0 if not found, >0 if OK
     */
    public function fetch($id, $ref = null, $morewhere = '')
    {
        return $this->fetchCommon($id, $ref, $morewhere);
    }

    /**
     * Update object in database
     *
     * @param   User    $user       User that modifies
     * @param   int     $notrigger  0=launch triggers, 1=disable triggers
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function update(User $user, $notrigger = false)
    {
        $this->fk_user_modif = $user->id;
        
        // Recalcular IMC
        if (!empty($this->peso) && !empty($this->talla) && $this->talla > 0) {
            $talla_m = $this->talla / 100;
            $this->imc = round($this->peso / ($talla_m * $talla_m), 2);
        }

        return $this->updateCommon($user, $notrigger);
    }

    /**
     * Delete object in database
     *
     * @param   User    $user       User that deletes
     * @param   int     $notrigger  0=launch triggers, 1=disable triggers
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function delete(User $user, $notrigger = false)
    {
        return $this->deleteCommon($user, $notrigger);
    }

    /**
     * Set status
     *
     * @param   User    $user       User that sets status
     * @param   int     $status     Status
     * @param   string  $morewhere  More SQL filters
     * @return  int                 Return integer <0 if KO, >0 if OK
     */
    public function setStatus($user, $status, $morewhere = '')
    {
        return $this->setStatut($status, 0, '', $morewhere);
    }

    /**
     * Return the label of the status
     *
     * @param   int     $mode           0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
     * @return  string                  Label of status
     */
    public function getLibStatut($mode = 0)
    {
        return $this->LibStatut($this->status, $mode);
    }

    /**
     * Return the status label
     *
     * @param   int     $status         Id status
     * @param   int     $mode           0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
     * @return  string                  Label of status
     */
    public static function LibStatut($status, $mode = 0)
    {
        global $langs;

        if ($mode == 0) {
            $prefix = '';
            if ($status == self::STATUS_DRAFT) return $langs->trans('Draft');
            if ($status == self::STATUS_VALIDATED) return $langs->trans('Validated');
            if ($status == self::STATUS_CANCELLED) return $langs->trans('Cancelled');
        }
        if ($mode == 1) {
            if ($status == self::STATUS_DRAFT) return $langs->trans('Draft');
            if ($status == self::STATUS_VALIDATED) return $langs->trans('Validated');
            if ($status == self::STATUS_CANCELLED) return $langs->trans('Cancelled');
        }
        if ($mode == 2 || $mode == 3) {
            if ($status == self::STATUS_DRAFT) return img_picto('', 'statut0').' '.$langs->trans('Draft');
            if ($status == self::STATUS_VALIDATED) return img_picto('', 'statut4').' '.$langs->trans('Validated');
            if ($status == self::STATUS_CANCELLED) return img_picto('', 'statut9').' '.$langs->trans('Cancelled');
        }
        if ($mode == 4) {
            if ($status == self::STATUS_DRAFT) return img_picto('', 'statut0').' '.$langs->trans('Draft');
            if ($status == self::STATUS_VALIDATED) return img_picto('', 'statut4').' '.$langs->trans('Validated');
            if ($status == self::STATUS_CANCELLED) return img_picto('', 'statut9').' '.$langs->trans('Cancelled');
        }
        if ($mode == 5 || $mode == 6) {
            if ($status == self::STATUS_DRAFT) return $langs->trans('Draft').' '.img_picto('', 'statut0');
            if ($status == self::STATUS_VALIDATED) return $langs->trans('Validated').' '.img_picto('', 'statut4');
            if ($status == self::STATUS_CANCELLED) return $langs->trans('Cancelled').' '.img_picto('', 'statut9');
        }

        return '';
    }

    /**
     * Load an object from its id and create a new one in database
     *
     * @param   User    $user           User that creates
     * @param   int     $from_id        Id of object to clone
     * @return  int                     New id of clone
     */
    public function createFromClone(User $user, $from_id)
    {
        global $langs, $extrafields;

        $error = 0;

        dol_syslog(__METHOD__, LOG_DEBUG);

        $object = new Consulta($this->db);

        $this->db->begin();

        // Load source object
        $object->fetch($from_id);

        // Clear fields
        $object->id = 0;
        $object->rowid = 0;
        $object->status = self::STATUS_DRAFT;
        $object->date_creation = dol_now();
        $object->fk_user_creat = $user->id;
        $object->fk_user_modif = null;
        $object->import_key = null;

        // Create clone
        $result = $object->create($user);

        // Other options
        if ($result > 0) {
            $newid = $object->id;

            // Copy extrafields
            $extrakeys = $extrafields->extract_name_optionsfields($object->table_element);
            foreach ($extrakeys as $key) {
                $extrafields->add_value($key, $object->array_options['options_'.$key], $object->table_element, $newid);
            }

            // Copy categories
            //$objcat = new Categorie($this->db);
            //$objcat->fetch($from_id);
            //$categs = $objcat->containedInCategories('consulta');
            //foreach ($categs as $cat) {
            //    $objcat->addType($newid, 'consulta');
            //}
        } else {
            $error++;
            $this->error = $object->error;
            $this->errors = $object->errors;
        }

        unset($object);

        // End
        if (!$error) {
            $this->db->commit();
            return $newid;
        } else {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     * Initialise object with example values
     * Id must be 0 if object instance is a specimen
     *
     * @return  void
     */
    public function initAsSpecimen()
    {
        $this->id = 0;
        $this->entity = 1;
        $this->date_creation = dol_now();
        $this->fk_user_creat = 1;
        $this->fk_soc = 1;
        $this->fecha_consulta = dol_now();
        $this->tipo_consulta = 'GIN_GENERAL';
        $this->motivo_consulta = 'Chequeo rutinario';
        $this->status = self::STATUS_VALIDATED;
    }

    /**
     * Get URL to view object
     *
     * @param   string  $mode           'form', 'href', etc.
     * @return  string                  URL
     */
    public function getNomUrl($mode = 'form')
    {
        global $langs, $conf;

        $url = dol_buildpath('/ginecodoli/consulta/card.php?id='.$this->id, 1);

        $label = $langs->trans("Show").' '.$this->ref;
        $link = '<a href="'.$url.'" title="'.dol_escape_htmltag($label, 1).'" class="classfortooltip">';
        $linkend = '</a>';

        return $link.img_picto('', 'consultation@ginecodoli', 'class="pictofixedwidth"').$this->id.$linkend;
    }

    /**
     * Return formatted info for IMC
     *
     * @return  string  Formatted IMC with interpretation
     */
    public function getIMCLabel()
    {
        if (empty($this->imc)) return '';

        $imc = $this->imc;
        $label = number_format($imc, 1);

        if ($imc < 18.5) $label .= ' (Bajo peso)';
        elseif ($imc < 25) $label .= ' (Normal)';
        elseif ($imc < 30) $label .= ' (Sobrepeso)';
        else $label .= ' (Obesidad)';

        return $label;
    }
}
