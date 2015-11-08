	<?php
    
    
	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    printr($_GET);
   // die();
    $arData=json_decode($_GET['data']);
    $values='NULL, ';
    foreach($arData->data as $data)
    {
       $date=date("Y-m-d H:i:s",$data[0]/1000);
    }
    $values.="'$date', ";
    printr($arData->data);
    foreach($arData->data as $data)
    {
       //$values.="'".round(pow(10,$data[1]),0)."', ";
    }
    for($i=0; $i<=6; $i++)
    {
        $val=($arData->data[$i][1]?$arData->data[$i][1]:1.2);
        $values.="'".round(pow(10,$val),0)."', ";
    }
    $values.="'".$_GET['type']."'";
   
    $query="INSERT INTO `meteor`.`training_set_3` (
`ID` ,

`DATE` ,
`D1_1` ,
`D1_2` ,
`D1_3` ,
`D1_4` ,
`D1_5` ,
`D1_6` ,
`D1_7` ,

`TYPE`
)
VALUES (
$values
);";
printr($query);
        $res=$db->query($query);
    /*
    
*/