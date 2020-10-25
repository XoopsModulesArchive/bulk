<?php

//=========================================================
// news: add bulk category
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/news_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/news_topic_sjis.txt';

$op = get_post_op();

if ('newsAddTopics' == $op) {
    newsAddTopics();
} else {
    newsAddTopicsForm($FILE);
}

exit();
// =====

function newsAddTopicsForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = news_make_selbox(1); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _NW_ADD_BULK_TOPIC; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _AM_IN; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _NW_ADD_BULK_TOPIC_DSC1; ?><br>
                    <?php echo _NW_ADD_BULK_TOPIC_DSC2; ?><br><br>
                    <textarea name='catlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='newsAddTopics'><br><br>
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

function newsAddTopics()
{
    $pid_first = get_post_int('topic_id');

    $line_arr = get_post_text_preg_split('catlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _NW_NO_TOPIC);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _NW_ADD_BULK_TOPIC . "</h4>\n";

    $pid_arr = [];

    $pid_arr[0] = $pid_first;

    $pid = $pid_first;

    $depth_prev = 0;

    $flag_error = 0;

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$depth, $title] = news_get_topic($line);

        if (!news_check_topic($title)) {
            continue;
        }

        // under one level, or above level

        if (($depth == ($depth_prev + 1)) || ($depth < $depth_prev)) {
            $pid = $pid_arr[$depth];
        } // under two or more level

        elseif ($depth > $depth_prev) {
            print_error(_NW_ERR_LAYER);

            $flag_error = 1;

            break;
        }

        $newid = news_insert_topic($pid, $title);

        $pid_arr[$depth + 1] = $newid;

        $depth_prev = $depth;
    }

    echo "<br>\n";

    if ($flag_error) {
        echo '<b>' . _FINISH_FAULT . "</b><br>\n";
    } else {
        echo '<b>' . _NW_NEW_TOPIC_ADDED . "</b><br>\n";
    }

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
