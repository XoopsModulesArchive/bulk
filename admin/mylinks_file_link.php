<?php

//================================================================
// mylinks: add bulk links
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/mylinks_func.php';

$TITLE = _ML_ADD_BULK_LINK;
$DSC = _ML_ADD_BULK_LINK_DSC_0 . '<br>' . _ML_ADD_BULK_LINK_DSC_1 . '<br>' . _ML_ADD_BULK_LINK_DSC_2 . '<br>';
$FILE = 'japanese/mylinks_link_tab_eucjp.txt';

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

    mylinks_file_links($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
