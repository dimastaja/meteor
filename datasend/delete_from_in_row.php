<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";
foreach($_GET as $key => $value)
        $$key=($value?$value:'0');
$ID=$ID-1;
//printr($ID);
$query="delete from errupt_in_row where ID=$ID";
$res=$db->query($query);