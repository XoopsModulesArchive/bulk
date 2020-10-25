<?php

//=========================================================
// newbb: add bulk topic
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/newbb_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/newbb_topic_sjis.csv';

$op = get_post_op();

if ('newbbAddTopic' == $op) {
    newbbAddTopic();
} else {
    newbbAddTopicForm($FILE);
}

exit();
// =====

function newbbAddTopicForm($file)
{
    global $mytree;

    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = newbb_make_selbox_forum(); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _BB_ADD_BULK_TOPIC; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _BB_FORUM; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _BB_ADD_BULK_TOPIC_DSC; ?><br><br>
                    <textarea name='topiclist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='newbbAddTopic'><br><br>
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

function newbbAddTopic()
{
    $forum_id = get_post_int('forum_id');

    $line_arr = get_post_text_preg_split('topiclist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _BB_NO_TOPIC_TITLE);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _BB_ADD_BULK_TOPIC . "</h4>\n";

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$subject, $post_text] = newbb_get_post($line);

        if (!newbb_check_post($subject, $post_text)) {
            continue;
        }

        $newid = newbb_insert_topic($forum_id, $subject);

        newbb_insert_post($forum_id, $newid, 0, $subject, $post_text);

        newbb_update_forum_topic($forum_id);
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_TOPIC_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
