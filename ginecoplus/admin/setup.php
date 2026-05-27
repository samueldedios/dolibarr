<?php
/* Copyright (C) 2024 Samuel de Dios
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 */

/**
 * \file    ginecoplus/admin/setup.php
 * \ingroup ginecoplus
 * \brief   ginecoPlus module setup page.
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php';
require_once DOL_DOCUMENT_ROOT . '/ginecoplus/lib/ginecoplus.lib.php';

if (!$user->admin) {
    accessforbidden();
}

$langs->loadLangs(array("admin", "ginecoplus@ginecoplus"));

$action = GETPOST('action', 'alpha');

if ($action == 'setvalue' && !empty($_POST['value'])) {
    dolibarr_set_const($db, GETPOST('param', 'alpha'), GETPOST('value', 'alpha'), 'chaine', 0, '', $conf->entity);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>GinecoPlus Module Setup</title>
</head>
<body>
<?php
llxHeader('', $langs->trans('ginecoplus_setup'));
?>

<div class="valign-top">
    <div class="div-double">
        <div class="firstcolumn">
            <h2><?php echo $langs->trans('ginecoplus_setup'); ?></h2>
            <p><?php echo $langs->trans('ginecoplus_description'); ?></p>
        </div>
    </div>
</div>

<?php
llxFooter();
?>
</body>
</html>