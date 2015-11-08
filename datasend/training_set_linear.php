	<?php
    
    
	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    printr($_GET);
  
   foreach ($_GET as $key => $value)
   {
    $$key=$value;
   }
   $IMP_POINT=date("YmdHis",$IMP_POINT);
    $query="INSERT INTO `meteor`.`training_set_6` (
            `DROP_COUNT` ,
            `PIK_HEIGHT` ,
            `PIK_PLACE` ,
            `PIK_COUNT` ,
            `DIFF` ,
            `IMP_POINT` ,
            `TYPE`
        )
        VALUES 
        ($DROP_COUNT,$PIK_HEIGHT,$PIK_PLACE,$PIK_COUNT,$DIFF,$IMP_POINT,$TYPE);";
printr($query);
        $res=$db->query($query);
    /*
    
*/