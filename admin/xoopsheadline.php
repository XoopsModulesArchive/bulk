<?php

//================================================================
// xoopsheadline: add bulk headline
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_header.php';
require __DIR__ . '/xoopsheadline_func.php';

$FILE = XOOPS_ROOT_PATH . '/modules/bulk/datas/japanese/xoopsheadline_sjis.csv';

$op = get_post_op();

if ('xoopsheadlineAddBulk' == $op) {
    xoopsheadlineAddBulk();
} else {
    xoopsheadlineAddBulkForm($FILE);
}

exit();
// =====

function xoopsheadlineAddBulkForm($file)
{
    xoops_cp_header();

    $action = xoops_getenv('PHP_SELF'); ?>
    <table width='100%' border='0' cellspacing='1' class='outer'>
        <tr class='odd'>
            <td>
                <h4><?php echo _HL_ADD_BULK; ?></h4>
                <form action='<?php echo $action; ?>' method='post'>
                    <?php echo _HL_ADD_BULK_DSC; ?><br><br>
                    <textarea name='headlinelist' cols='60' rows='20'></textarea>
                    <input type='hidden' name='op' value='xoopsheadlineAddBulk'><br><br>
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

function xoopsheadlineAddBulk()
{
    $line_arr = get_post_text_preg_split('headlinelist');

    if (0 == count($line_arr)) {
        redirect_header('index.php', 2, _HL_NO_NAME);

        exit();
    }

    xoops_cp_header();

    print_title(_HL_ADD_BULK);

    // headline

    foreach ($line_arr as $line) {
        $line = trim($line);

        if (empty($line)) {
            continue;
        }

        [$headline_name, $headline_url, $headline_rssurl, $headline_encoding] = xoopsheadline_get_xoopsheadline($line);

        if (!xoopsheadline_check_xoopsheadline($headline_name, $headline_url, $headline_rssurl)) {
            continue;
        }

        xoopsheadline_insert_xoopsheadline($headline_name, $headline_url, $headline_rssurl, $headline_encoding);
    }

    echo "<br>\n";

    echo '<b>' . _HL_HEADLINE_ADDED . "</b><br>\n";

    echo "<h4><a href='index.php'>" . _MAIN . "</a></h4>\n";

    xoops_cp_footer();
}

?>
