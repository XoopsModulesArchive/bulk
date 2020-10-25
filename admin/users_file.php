<?php

//================================================================
// users: add bulk
// 2005-07-21 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/users_func.php';

$TITLE = _US_ADD_USERS;
$DSC = _US_ADD_USERS_DSC_1;
$FILE = 'japanese/users_tab_eucjp.txt';

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

    users_file($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
