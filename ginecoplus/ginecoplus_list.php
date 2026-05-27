<?php
/* Copyright (C) 2024 Samuel de Dios
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file    ginecoplus/ginecoplus_list.php
 * \ingroup ginecoplus
 * \brief   List page for ginecoPlus records.
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/ginecoplus/lib/ginecoplus.lib.php';

if (!$user->hasRight('ginecoplus', 'read')) {
    accessforbidden();
}

$langs->loadLangs(array("ginecoplus@ginecoplus"));

$page = GETPOST("page", "int") ?: 0;
$limit = $conf->liste_limit;
$offset = $page * $limit;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GinecoPlus List</title>
</head>
<body>
<?php
llxHeader('', $langs->trans('List'));
?>

<div class="valign-top">
    <div class="div-double">
        <div class="firstcolumn">
            <h2><?php echo $langs->trans('ginecoplus_list'); ?></h2>
            <p><?php echo $langs->trans('List of GinecoPlus records'); ?></p>
        </div>
    </div>
</div>

<?php
llxFooter();
?>
</body>
</html>