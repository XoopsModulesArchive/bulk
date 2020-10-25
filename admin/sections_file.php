<?php

//================================================================
// sections: add bulk category & content
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/sections_func.php';

$TITLE = _SC_ADD_BULK;
$DSC = _SC_ADD_BULK_DSC1 . '<br>' . _SC_ADD_BULK_DSC_2 . '<br>' . _SC_ADD_BULK_DSC3 . '<br>' . _SC_ADD_BULK_DSC_4 . '<br>';
$FILE = 'japanese/sections_tab_eucjp.txt';

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

    sections_file($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
