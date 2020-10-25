<?php

//================================================================
// newbb: add bulk topic
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/newbb_func.php';

$TITLE = _BB_ADD_BULK_TOPIC;
$DSC = _BB_ADD_BULK_TOPIC_DSC_0 . '<br>' . _BB_ADD_BULK_TOPIC_DSC_1 . '<br>' . _BB_ADD_BULK_TOPIC_DSC_2 . '<br>';
$FILE = 'japanese/newbb_topic_tab_eucjp.txt';

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

    newbb_file_topic($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
