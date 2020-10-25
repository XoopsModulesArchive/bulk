<?php

//=========================================================
// newbb: add bulk forum
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/newbb_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/newbb_forum_sjis.csv';

$op = get_post_op();

if ('newbbAddForum' == $op) {
    newbbAddForum();
} else {
    newbbAddForumForm($FILE);
}

exit();
// =====

function newbbAddForumForm($file)
{
    global $mytree;

    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = newbb_make_selbox_cat(); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _BB_ADD_BULK_FORUM; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _BB_CAT; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _BB_ADD_BULK_FORUM_DSC; ?><br><br>
                    <textarea name='forumlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='newbbAddForum'><br><br>
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

function newbbAddForum()
{
    $cat_id = get_post_int('cat_id');

    $line_arr = get_post_text_preg_split('forumlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _BB_NO_FORUM_NAME);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _BB_ADD_BULK_FORUM . "</h4>\n";

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$forum_name, $forum_desc] = newbb_get_forum($line);

        if (!newbb_check_forum($forum_name, $forum_desc)) {
            continue;
        }

        newbb_insert_forum($cat_id, $forum_name, $forum_desc);
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_FORUM_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
