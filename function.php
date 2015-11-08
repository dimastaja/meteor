<?php

require $_SERVER["DOCUMENT_ROOT"]."/dbconnect.php";
date_default_timezone_set("Europe/London");


function printr($arr)
{
    if ($_SERVER['PHP_SELF']=='/datasend/training_search_new.php'&&$_GET['debug']!='Y')
        return;
    if ($_SERVER['PHP_SELF']=='/datasend/training_search_linear.php'&&$_GET['debug']!='Y')
        return;
    if ($_SERVER['PHP_SELF']=='/datasend/show_event.php'&&$_GET['debug']!='Y')
        return;
    if ($_SERVER['PHP_SELF']=='/datasend/in_row_search.php'&&$_GET['debug']!='Y')
        return;
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function random_html_color()
{
    return sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
}

?>
