<?php

//================================================================
// xoopsfaq: add bulk category & content
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/xoopsfaq_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/xoopsfaq_sjis_1.csv';

$op = get_post_op();

if ('xoopsfaqAddBulk' == $op) {
    xoopsfaqAddBulk();
} else {
    xoopsfaqAddBulkForm($FILE);
}

exit();
// =====

function xoopsfaqAddBulkForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF'); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _XD_ADD_BULK; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _XD_ADD_BULK_DSC1; ?><br>
                    <?php echo _XD_ADD_BULK_DSC2; ?><br>
                    <?php echo _XD_ADD_BULK_DSC3; ?><br><br>
                    <textarea name='faqlist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='xoopsfaqAddBulk'><br><br>
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

function xoopsfaqAddBulk()
{
    $line_arr = get_post_text_preg_split('faqlist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _XD_NO_QUESTION);

        exit();
    }

    xoops_cp_header();

    print_title(_XD_ADD_BULK);

    // categories

    $line = array_shift($line_arr);

    $line = trim($line);

    $category_title = xoopsfaq_get_categories($line);

    if (!xoopsfaq_check_categories($category_title)) {
        echo "<br>\n";

        echo '<b>' . _FINISH_FAULT . "</b><br>\n";

        echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

        xoops_cp_footer();

        exit();
    }

    $category_id = xoopsfaq_insert_categories($category_title);

    // contents

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$contents_title, $contents_contents] = xoopsfaq_get_contents($line);

        if (!xoopsfaq_check_contents($contents_title, $contents_contents)) {
            continue;
        }

        xoopsfaq_insert_contents($category_id, $contents_title, $contents_contents);
    }

    echo "<br>\n";

    echo '<b>' . _XD_FAQ_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
