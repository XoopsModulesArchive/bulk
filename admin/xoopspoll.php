<?php

//================================================================
// xoopspoll: add bulk category & content
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/xoopspoll_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/xoopspoll_sjis_1.csv';

$op = get_post_op();

if ('xoopspollAddBulk' == $op) {
    xoopspollAddBulk();
} else {
    xoopspollAddBulkForm($FILE);
}

exit();
// =====

function xoopspollAddBulkForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF'); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _PL_ADD_BULK; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _PL_ADD_BULK_DSC1; ?><br>
                    <?php echo _PL_ADD_BULK_DSC2; ?><br>
                    <?php echo _PL_ADD_BULK_DSC3; ?><br><br>
                    <textarea name='polllist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='xoopspollAddBulk'><br><br>
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

function xoopspollAddBulk()
{
    $line_arr = get_post_text_preg_split('polllist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _PL_NO_QUESTION);

        exit();
    }

    xoops_cp_header();

    print_title(_PL_ADD_BULK);

    // categories

    $line = array_shift($line_arr);

    $line = trim($line);

    [$question, $multiple] = xoopspoll_get_desc($line);

    if (!xoopspoll_check_desc($question)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

        xoops_cp_footer();

        exit();
    }

    $poll_id = xoopspoll_insert_desc($question, $multiple);

    // contents

    $num = 0;

    xoopspoll_set_barcolor();

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        $option_text = xoopspoll_get_option($line);

        if (!xoopspoll_check_option($option_text)) {
            continue;
        }

        xoopspoll_insert_option($poll_id, $option_text, $num);

        $num++;
    }

    echo "<br>\n";

    echo '<b>' . _PL_POLL_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
