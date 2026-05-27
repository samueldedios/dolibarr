<?php
/* Copyright (C) 2024 Samuel de Dios
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file    ginecoplus/lib/ginecoplus.lib.php
 * \ingroup ginecoplus
 * \brief   Library functions for ginecoPlus module.
 */

/**
 * Prepare admin pages header
 *
 * @return string
 */
function ginecoPlusAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load("ginecoplus@ginecoplus");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/ginecoplus/admin/setup.php", 1);
    $head[$h][1] = $langs->trans("Settings");
    $head[$h][2] = 'settings';
    $h++;

    $head[$h][0] = dol_buildpath("/ginecoplus/admin/about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    return $head;
}

/**
 * Prepare user pages header
 *
 * @return string
 */
function ginecoPlusUserPrepareHead()
{
    global $langs, $conf;

    $langs->load("ginecoplus@ginecoplus");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/ginecoplus/ginecoplus_list.php", 1);
    $head[$h][1] = $langs->trans("List");
    $head[$h][2] = 'list';
    $h++;

    $head[$h][0] = dol_buildpath("/ginecoplus/ginecoplus_card.php", 1);
    $head[$h][1] = $langs->trans("New");
    $head[$h][2] = 'card';
    $h++;

    return $head;
}