<?php

//================================================================
// admin main
// 2005-07-21 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';

$op = get_post_op();

xoops_cp_header();
echo "<h3>$TITLE</h3>\n";

switch ($op) {
    case 'go':
        create();
        break;
    case 'menu':
    default:
        print_form_go();
        break;
}

xoops_cp_footer();
exit();
// =====
