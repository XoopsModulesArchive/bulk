<?php

//================================================================
// xoopspoll: add bulk category & content
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/xoopspoll_func.php';

$TITLE = _PL_ADD_BULK;
$DSC = _PL_ADD_BULK_DSC_1 . '<br>' . _PL_ADD_BULK_DSC2 . '<br>' . _PL_ADD_BULK_DSC3 . '<br>' . _PL_ADD_BULK_DSC_4 . '<br>';
$FILE = 'japanese/xoopspoll_tab_eucjp.txt';

$op = get_post_op();

switch ($op) {
    case 'go':
        create($FILE);
        break;
    case 'menu':
    default:
        xoops_cp_header();
        print_form_file($TITLE, $DSC, $FILE);
        xoops_cp_footer();
        break;
}

exit();
// =====

function create($file)
{
    xoops_cp_header();

    xoopspoll_file($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
