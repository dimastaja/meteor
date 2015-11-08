<?php
// testing github
/* Проект Супова Дмитрия */


include $_SERVER["DOCUMENT_ROOT"].'/function.php';
//printr(get_include_path());
set_include_path($_SERVER["DOCUMENT_ROOT"].'/excel/Classes/');
//printr(get_include_path());
//подключаем и создаем класс PHPExcel
include_once 'PHPExcel.php';
$objPHPExcel = new PHPExcel();
// устанавливаем метаданные
 $first=date("YmdHis",$_GET["first"]/1000);
 $last=date("YmdHis",$_GET["last"]/1000);
 $Lfirst=$_GET["Lfirst"];
 $Llast=$_GET["Llast"];
$objPHPExcel->getProperties()->setCreator("PHP")
->setLastModifiedBy("Супов Дмитрий")
->setTitle("Выгрузка из базы. Дата с ".$first." по ".$last."; L=".$Lfirst."..".$Llast);

$objPHPExcel->setActiveSheetIndex(0);

$aSheet = $objPHPExcel->getActiveSheet();
$aSheet->setTitle('Лист № 1');
//устанавливаем данные
//номера по порядку

//устанавливаем ширину
$aSheet->getColumnDimension('A')->setWidth(25);
//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
//настройки для шрифтов
$baseFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'10',
		'bold'=>false
	)
);
$boldFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'10',
		'bold'=>true
	)
);
//и позиционирование
$center = array(
	'alignment'=>array(
		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
	)
);
//установим жирный шрифт для заголовков
//и заодно отцентрируем


//и основной шрифт для всех остальных






        $first=date("YmdHis",$_GET["first"]/1000);
	$last=date("YmdHis",$_GET["last"]/1000);
        //$Lfirst=$_GET["Lfirst"];
	//$Llast=$_GET["Llast"];
	//printr($first);
	// создаем запрос (выбираем все значения от до)
	$filterQuery=" where Date>".$first." and Date<".$last;
        foreach($_GET["filter"] as $key => $value)
        {
            $arKey=explode("_",$key);
            $znak=($arKey["1"]==1) ? ">"  : "<";
            if (($znak==">")&&($value==""))
                $value=-999;
            if (($znak=="<")&&($value==""))
                $value=999999;
            $filterQuery.=" and ".$arKey["0"].$znak.$value;
            
           // ." and L>".$Lfirst." and L<".$Llast
        }
        
	$query="select * from data_1".$filterQuery;
	//printr($query);
	// создаем массив элементов, которые будем на график выводить (тип D1 D2 и так далее)
	
	// делаем запрос к бд
	$res=$db->query($query);

	//sprintr($count);
	$countField=$res->field_count;
	
	// определяем порогвые значения по вертикали
	$min=$_GET["ymin"];
	$max=$_GET["ymax"]+1*1;
	
        //printr(strtotime("now"));
        $aSheet->setCellValue('A1','№');

$chrA=65;
$i=1;
	while ($row =$res->fetch_assoc())
	{
	    $j=0;
            foreach($row as $colName => $value)
            {
                if ($i==1)
                {
                    $aSheet->setCellValue(chr($chrA+$j*1).$i,$colName);
                    $aSheet->getStyle(chr($chrA+$j*1).$i)->applyFromArray($boldFont)
->applyFromArray($center);
                }
                 $aSheet->setCellValue(chr($chrA+$j*1).($i+1*1),$value);
                 $aSheet->getStyle(chr($chrA+$j*1).($i+1*1))->applyFromArray($center);
                 $j++;
            }
            $i++;
            /*
            for ($i=$chrA; $i<=($chrA+$countField); $i++)
            {
                printr(chr($i));
            }*/
         }
	
	



for ($i=$chrA; $i<=($chrA+$countField); $i++)
{
    //printr(chr($i));
}
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.date("Y-m-d_H:i",$_GET["first"]/1000).'-'.date("Y-m-d_H:i",$_GET["last"]/1000).'_L='.$Lfirst.'-'.$Llast.'.xls');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

?> 



