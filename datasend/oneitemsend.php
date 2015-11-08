<?php

/* Проект Супова Дмитрия */


include $_SERVER["DOCUMENT_ROOT"].'/function.php';

$date=date("YmdHis",$_GET["date"]/1000);
$filterQuery=" where Date=".$date;
$query="select * from data_2".$filterQuery;
$res=$db->query($query);
$row =$res->fetch_assoc();
$dataSend=json_encode($row);
echo $dataSend;
?>
