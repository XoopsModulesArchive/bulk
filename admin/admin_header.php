<?php

//================================================================
// admin header
// 2005-07-21 K.OHWADA
//================================================================

require dirname(__DIR__, 3) . '/mainfile.php';
require dirname(__DIR__, 3) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once __DIR__ . '/admin_func.php';

$flag = false;
if ($xoopsUser && $xoopsUser->isAdmin()) {
    $flag = true;
}

if (!$flag) {
    redirect_header(XOOPS_URL, 2, _NOPERM);

    exit();
}
