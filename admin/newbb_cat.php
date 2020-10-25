<?php

//=========================================================
// newbb: add bulk category
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/newbb_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/newbb_cat_sjis.txt';

$op = get_post_op();

if ('newbbAddCats' == $op) {
    newbbAddCats();
} else {
    newbbAddCatsForm($FILE);
}

exit();
// =====

function newbbAddCatsForm($file)
{
    global $mytree;

    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = newbb_make_selbox_cat(); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _BB_ADD_BULK_CAT; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _BB_ADD_BULK_CAT_DSC; ?><br><br>
                    <textarea name='catlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='newbbAddCats'><br><br>
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

function newbbAddCats()
{
    $line_arr = get_post_text_preg_split('catlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _BB_NO_CAT);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _BB_ADD_BULK_CAT . "</h4>\n";

    // category

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        $title = newbb_get_cat($line);

        if (!newbb_check_cat($title)) {
            continue;
        }

        newbb_insert_cat($title);
    }

    echo "<br>\n";

    echo '<b>' . _BB_NEW_CAT_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
