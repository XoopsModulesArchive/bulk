<?php

//================================================================
// mydownloads: add bulk categories
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/mydownloads_func.php';

$TITLE = _MD_ADD_BULK_CAT;
$DSC = _MD_ADD_BULK_CAT_DSC_0 . '<br>' . _MD_ADD_BULK_CAT_DSC_1 . '<br>' . _MD_ADD_BULK_CAT_DSC2 . '<br>';
$FILE = 'japanese/mydownloads_cat_tab_eucjp.txt';

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

    mydownloads_file_cat($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
