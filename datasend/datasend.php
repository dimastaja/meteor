	<?php
    
    
	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    
    
    
	//ограничение по дате
       // printr(DateTimeZone::getOffset());
       // printr($_GET);
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
    if ($_GET['showDrop'])
    {
       $filterQuery.=" and d1>100 and L<8 and L>3 ";
    }    
	$query="select * from data_2".$filterQuery." order by date asc";
	//printr($query);
	// создаем массив элементов, которые будем на график выводить (тип D1 D2 и так далее)
	$arField=array();
	for ($i=1;  $i<=8; $i++)
	{
		$arField[]=$i;
	}
	// если есть ограничения - принимаем их
	if ($_GET["arField"]!=0)
	   $arField=$_GET["arField"];
	//printr($arField);
	// составляем массив цветов
    $arColor["DROPS"]='#482525';
	$arColor["D1"]='#6db000';
	$arColor["D2"]='#ab251c';
	$arColor["D3"]='#0073cd';
	$arColor["D4"]='#ddf323';
	$arColor["D5"]='#f39d23';
	$arColor["D6"]='#56d3ff';
	$arColor["D7"]='#b123f3';
	$arColor["D8"]='#07f1de';
	// делаем запрос к бд
	$res=$db->query($query);
	$i=1;
	$arTiks=array();
	// определяем как часто будем делать псевдонимы L1 для временной шкалы (путанно выразился, но суть ясна). 
	$count=$res->num_rows;
	//sprintr($count);
        //printr($_GET);
	if ($_GET["pointCount"])
            $qauntity=$_GET["pointCount"];
        else
            $qauntity=500;
	if ($count>$qauntity)
		$interval=floor($count/$qauntity);
	else
		$interval=1;
	if ($count>30000)
		$count=floor($count/300);
	else
		$count=10;
	//printr($interval);
	// определяем пороговые значения по вертикали
	$min=$_GET["ymin"];
	$max=$_GET["ymax"]+1*1;
    if ($_GET['showDrop'])
        $interval=1;
	$j=$interval;
    
        //printr(strtotime("now"));
        $firstData=true;
        $dropTime=0;
        $countParab=0;
        //first parab
        $firstParab='true';
        $rowCount=0;
	while ($row =$res->fetch_assoc())
	{
	    $arRows[$rowCount]=$row;
        $rowCount++;
        // переводим дату в удобоваримый формат для js. Чтобы выводить временную шкалу.
	    $sec=(strtotime($row["Date"]))*1000;
            if ($firstData)
            {
                $secPr=$sec;
                $firstData=false;
            }
           //printr($sec);
           //  break;
	    if ($j==$interval)
		{
                    
			foreach ($arField as $field)
			{
				$field="D".$field;
				if ((log10($row[$field])>=$min)&&(log10($row[$field])<=$max))
				{
				  //printr($sec);
                                    if (($sec-$secPr)/1000>($interval*24+1)&&($_GET['skip']))
                                    {
                                       $dropTime=$dropTime+$sec-$secPr-48*$interval*1000;
                                       
                                    }
                                    //   printr($dropTime);
                                    $arData[$field][]=array(($sec-$dropTime),round(log10($row[$field]),2));
                                    
                                     // отслеживаем параболы
                                   // printr($sec);
                                   // printr($secPr);
                                  
                                    if ((($sec-$secPr)/1000)>25)
                                    {
                                        $countParab++;
                                        $firstParab=false;
                                    }
                                    elseif(!$firstParab)
                                    {
                                        $firstParab=true;
                                        if ($countParab>0)
                                        $arData['PARAB'][$countParab][]=array('SEC'=>($sec-12000),'LOG'=>round(log10($arRows[$rowCount-2][$field]),2),'D1'=>$arRows[$rowCount-2][$field]);
                                    }
                                        
                                    //printr($firstParab);
                                    
                                    if ($firstParab&&$field=='D1'&&$_GET['showDrop'])
                                    {
                                        
                                        $arData['PARAB'][$countParab][]=array('SEC'=>($sec-$dropTime),'LOG'=>round(log10($row[$field]),2),'D1'=>$row[$field]);
                                    }
                                    
                                    $secPr=$sec;
				   
                     // break;
                     // break;
                
                }
                
                
                
                
			}
			$j=0;
		}
        //break;
	    // добавляем шкалу L (вместо обычного времени))
		if ($i==$count)
		{
			$arTiks[]=array($sec-$dropTime, round($row["L"],1));
			$i=0;
		}
		$i++;
		$j++;
    }
    // количество выпаданий
    $countDrop=0;
   // printr($arData['PARAB']);
    // находим выпадания ...
    //1. ищем максимум
    if ($_GET['showDrop'])
    {
        foreach($arData['PARAB'] as $index =>$arParab)
        {
            $arMax[$index]=array('SEC'=>0, 'LOG'=>0, 'D1'=>0);
            foreach($arParab as $arPoint)
            {
                //printr($arPoint);
                if ($arPoint['D1']>$arMax[$index]['D1'])
                    $arMax[$index]=$arPoint;
            }
        }
        
       
        foreach($arData['PARAB'] as $index =>$arParab)
        {
            //if ($dropCount>0) break;
            $arPrev['D1']=0;
            foreach($arParab as $key => $arPoint)
            {
                //printr($arPoint);
                $dif=$arPoint['D1']-$arPrev['D1'];
                $arPrev=$arPoint;
                if (($dif<0)&&($arPoint['SEC']<$arMax[$index]['SEC'])&&($arPoint['D1']>200))
                {
                    
                    if ($arData['PARAB'][$index][$key+2]['LOG']-$arPoint['LOG']>=0.1)
                    {
                        $countDrop++;
                        //printr($arMax[$index]);
                        //printr($arPoint);
                        //printr($key);
                        for ($i=($key-3); $i<=($key+3); $i++)
                        {
                            if ($i>=0)
                            {
                                $arDrops[$index][]=array($arData['PARAB'][$index][$i]['SEC'],$arData['PARAB'][$index][$i]['LOG']);
                            }
                        }
                        break;
                    }
                }
                
                
                if (($dif>0)&&($arPoint['SEC']>$arMax[$index]['SEC'])&&($arPoint['D1']>200))
                {
                   
                   if (-$arData['PARAB'][$index][$key-2]['LOG']+$arPoint['LOG']>=0.1)
                   {
                        $countDrop++;
                        for ($i=$key-2; $i<=$key+2; $i++)
                        {
                            if ($i<count($arData['PARAB'][$index]))
                            {
                                $arDrops[$index][]=array($arData['PARAB'][$index][$i]['SEC'],$arData['PARAB'][$index][$i]['LOG']);
                            }
                        }
                        break;
                    }
                }
                
                
                
            }
        }
    }
    if (@$_GET['dev']=='true')
        printr($arDrops);
  //  printr($count);
	foreach($arData as $field => $data)
	{
		if ($field!='PARAB')
            $dataSend["PLOT"][] = array('label' => $field , 'data' => $data, 'color' => $arColor[$field]);
	}
    if ($_GET['showDrop'])
    {
        foreach($arDrops as $field => $data)
    	{
    		    
                $dataSend["PLOT"][] = array('label' => 'drop '.($field+1) , 'data' => $data, 'color' => '#ff0000');//random_html_color());
    	}  
    }
    
    //printr($arData['PARAB']);
    //$dataSend["PLOT"][]=array('label'=>'External bell', 'data'=>$arData['PARAB'], 'color'=>random_html_color());
	$dataSend["TICK"]=$arTiks;
    $dataSend['COUNT_DROP']=$countDrop;
	$dataSend=json_encode($dataSend);
	echo $dataSend;
	?>