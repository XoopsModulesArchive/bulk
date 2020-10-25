<?php

//================================================================
// set users table for JCAFE school site
// 2005-07-24 K.OHWADA
//================================================================

require __DIR__ . '/admin_func.php';

$file = $_POST['file'];

?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=Shift_JIS">
    <title><?php echo $file; ?></title>
</head>
<body>
<h3><?php echo $file; ?></h3>
<?php

if (print_error_file_not_exists($file)) {
    echo "<form>\n";

    echo "<textarea cols='60' rows='20'>";

    readfile($file);

    echo "</textarea>\n";

    echo "</form>\n";
}

?>
<input value='CLOSE' type='button' onclick='javascript:window.close();'>
</head>
</html>
<?php

?>
