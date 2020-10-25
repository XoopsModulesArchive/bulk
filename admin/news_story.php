<?php

//=========================================================
// news: add bulk downloads
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/news_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/news_story_sjis.csv';

$op = get_post_op();

if ('newsAddStories' == $op) {
    newsAddStories();
} else {
    newsAddStoriesForm($FILE);
}

exit();
// =====

function newsAddStoriesForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = news_make_selbox(0); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _NW_ADD_BULK_STORY; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _AM_IN; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _NW_ADD_BULK_STORY_DSC; ?><br><br>
                    <textarea name='newslist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='newsAddStories'><br><br>
                    <input type='submit' value=' <?php echo _ADD; ?> '>
                    <input type='button' value='<?php echo _BACK; ?>' onclick='javascript:history.go(-1)'><br>
                </form>
                <form action='view_sjis_file.php' method='post' target='_blank'>
                    <input type='hidden' name='file' value='<?php echo $file; ?>'><br><br>
                    <input type='submit' value=' <?php echo _SAMPLE; ?> '><br>
                </form>
            </td>
        </tr>
    </table>
    <br>
    <?php

    xoops_cp_footer();
}

function newsAddStories()
{
    $topicid = get_post_int('topic_id');

    $line_arr = get_post_text_preg_split('newslist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _NW_NO_TITLE);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _NW_ADD_BULK_STORY . "</h4>\n";

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$title, $hometext] = news_get_story($line);

        if (!news_check_story($title, $hometext)) {
            continue;
        }

        news_insert_story($topicid, $title, $hometext);
    }

    echo "<br>\n";

    echo '<b>' . _NW_NEW_STORY_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
