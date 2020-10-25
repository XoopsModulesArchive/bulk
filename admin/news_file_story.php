<?php

//================================================================
// news: add bulk downloads
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/news_func.php';

$TITLE = _NW_ADD_BULK_STORY;
$DSC = _NW_ADD_BULK_STORY_DSC_0 . '<br>' . _NW_ADD_BULK_STORY_DSC_1 . '<br>' . _NW_ADD_BULK_STORY_DSC_2 . '<br>';
$FILE = 'japanese/news_story_tab_eucjp.txt';

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

    news_file_story($file);

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}
