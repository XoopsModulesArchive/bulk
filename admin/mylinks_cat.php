<?php

//=========================================================
// mylinks: add bulk category
// 2005-07-12 K.OHWADA
//=========================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/mylinks_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/mylinks_cat_sjis_1.txt';

$op = get_post_op();

if ('mylinksAddCats' == $op) {
    mylinksAddCats();
} else {
    mylinksAddCatsForm($FILE);
}

exit();
// =====

function mylinksAddCatsForm($file)
{
    global $mytree;

    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF');

    $selbox = mylinks_make_selbox(1); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _ML_ADD_BULK_CAT; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    &nbsp; <?php echo _MD_IN; ?> &nbsp; <?php echo $selbox; ?><br><br>
                    <?php echo _ML_ADD_BULK_CAT_DSC1; ?><br>
                    <?php echo _ML_ADD_BULK_CAT_DSC2; ?><br><br>
                    <textarea name='catlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='mylinksAddCats'><br><br>
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

function mylinksAddCats()
{
    $pid_first = get_post_int('cid');

    $line_arr = get_post_text_preg_split('catlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _ML_NO_CAT);

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _ML_ADD_BULK_CAT . "</h4>\n";

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

        [$depth, $title] = mylinks_get_cat($line);

        if (!mylinks_check_cat($title)) {
            continue;
        }

        // under one level, or above level

        if (($depth == ($depth_prev + 1)) || ($depth < $depth_prev)) {
            $pid = $pid_arr[$depth];
        } // under two or more level

        elseif ($depth > $depth_prev) {
            print_error(_ML_ERR_LAYER);

            $flag_error = 1;

            break;
        }

        $newid = mylinks_insert_cat($pid, $title);

        $pid_arr[$depth + 1] = $newid;

        $depth_prev = $depth;
    }

    echo "<br>\n";

    if ($flag_error) {
        echo '<b>' . _FINISH_FAULT . "</b><br>\n";
    } else {
        echo '<b>' . _MD_NEWCATADDED . "</b><br>\n";
    }

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
