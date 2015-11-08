	<?php
  	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    
    
 
  	//ограничение по дате
    if ($_GET['first'])
        $first=date("YmdHis",$_GET["first"]/1000);
    else
    {
        $query="select * from data_2 order by  date asc limit 1";
        $res=$db->query($query);
        $row =$res->fetch_assoc();
        if ($row['DATE'])
            $first=$row['DATE'];
        
      }	
       $filterQuery.=" and d1>50 and L<6.6 and L>2.5 ";
    if ($_GET['back'])
    	$query="select date, d1 from data_2 where Date<'$first' ".$filterQuery." order by date desc limit 7200";
    else 
    	$query="select date, d1 from data_2 where Date>'$first' ".$filterQuery." order by date asc limit 7200";
	// составляем массив цветов
    $arColor["DROPS"]='#482525';
	$arColor["D1"]='#6db000';
	// делаем запрос к бд
	$res=$db->query($query);
	$i=1;
	$arTiks=array();
	// определяем как часто будем делать псевдонимы L1 для временной шкалы (путанно выразился, но суть ясна). 
	//$count=$res->num_rows;
	 //sprintr($count);
        //printr($_GET);
    
	//printr($interval);
	// определяем пороговые значения по вертикали
    $i=-1;
    $firstData=true;
    $arMax=array('SEC'=>0, 'LOG'=>0, 'D1'=>0);
    $done=false;
    
    $lastDate=$first;
    // перебираем всю выборку
	while ($row =$res->fetch_assoc())
	{
	    
       
        $i++;
        // преобразуем время в формат времени для js 
        $sec=(strtotime($row["date"]))*1000;
        // создаем переменную secpr при первом проходе 
        if ($firstData)
        {
            $secPr=$sec;
            $firstData=false;
        }
       
        
        // добавляем элемент в массив
        $arData[$i]=array('SEC'=>$sec,'LOG'=>round(log10($row['d1']),15));
        //$arData[i]=array('SEC'=>$sec, 'LOG')
        $lastDate=$arData[$i]['SEC'];
        // ищем максимальный элемент
        $difSec=$sec-$secPr;
        if ($_GET['back'])
            $difSec=-$sec+$secPr;
        // если текущий пояс закончился, то пытаемся найти высыпание
        if ((($difSec)/1000)>25)
        {
            
            
            
            // убираем последнюю точку (она ж уже из нового пояса)
            unset($arData[$i]);
            
           
            if ($_GET['back'])
                 $arData=array_reverse($arData);    
            $arTemp=array();
            foreach($arData as $data)
            {
                $arTemp[]=$data;
            } 
              $arData=$arTemp; 
            $first=true;
            // убираем лажовые концы (нах не нужны)
          //printr($arData);
          $doneL=true;
          $doneL=false;
         // die('jlk');
          $p=0;
          //printr('bla');
          while (!$doneL)
          {
            
            //printr($arData[0]);
            if ($p++>15)
                $doneL=true;
                
                if ((($arData[0]["LOG"]<$arData[1]["LOG"])&&($arData[1]["LOG"]<$arData[2]["LOG"]))&&(($arData[0]["LOG"]>2.2)||($arData[1]["LOG"]>2.1)||($arData[2]["LOG"]>2.1)))
                    $doneL=true;
                else
                if (($arData[0]["LOG"]<$arData[1]["LOG"])&&($arData[1]["LOG"]>$arData[2]["LOG"])&&($arData[1]["LOG"]>2.2))
                    $doneL=true;
                if (!$doneL)
                    array_shift($arData);   
            
          }
          //printr($arData);
          $doneR=true;
          $doneR=false;
         // die('jlk');
          $p=0;
          while (!$doneR)
          {
            $cnt=count($arData)-1;
            //printr($arData[0]);
            if ($p++>15)
                $doneR=true;
                
                if ((($arData[$cnt]["LOG"]<$arData[$cnt-1]["LOG"])&&($arData[1]["LOG"]<$arData[$cnt-2]["LOG"]))&&(($arData[$cnt]["LOG"]>2.1)||($arData[$cnt-1]["LOG"]>2.1)||($arData[$cnt-2]["LOG"]>2.1)))
                    $doneR=true;
                else
                if (($arData[$cnt]["LOG"]<$arData[$cnt-1]["LOG"])&&($arData[$cnt-1]["LOG"]>$arData[$cnt-2]["LOG"])&&($arData[$cnt-1]["LOG"]>2.1))
                    $doneR=true;
                if (!$doneR)
                    array_pop($arData);   
            
          }
          
            
            // выставляем ноль для предыдущего значения
            $arPrev['LOG']=0;
            
            // перебираем все точки пояса, и ищем те самые высыпания
            
            // точка максимума
            $arMax=array('SEC'=>0, 'LOG'=>0, 'D1'=>0);
            foreach($arData as $key => $arPoint)
                if ($arPoint['LOG']> $arMax['LOG'])
                    $arMax=$arPoint;
            foreach($arData as $key => $arPoint)
            {
                // считаем дельту значений
                $dif=$arPoint['LOG']-$arPrev['LOG'];
                // разницу посчитали - можно обновить предыдущее значение
                
              //    printr($arPrev);
                $arPrev=$arPoint;
                
                
                // проверяем дельту и нахождение относительно максимума
                if (($arPoint['LOG']>1.5)&&(($arPrev['LOG']>1.5)&&(($dif<0)&&($arPoint['SEC']<$arMax['SEC']))||(($arPoint['LOG']>1.5)&&($dif>0)&&($arPoint['SEC']>$arMax['SEC']))))
               // if ((($dif<0)&&($arPoint['SEC']<$arMax['SEC']))||(($dif>0)&&($arPoint['SEC']>$arMax['SEC'])))
                {
                 
                    // создаем кривую, содержащую высыпание
                    if ($dif<0)
                    {
                        $minus=4;
                        $plus=5;
                    }
                    else
                    {
                        $minus=5;
                        $plus=4;
                    }
                    $k=0;
                    
                    $firstDropDate=date("YmdHis",($arPoint["SEC"]/1000-12*$minus));
                    $query="select date, d1 from data_2 where date>=$firstDropDate order by date asc limit 9";
                    //printr($query);
                    $resDrop=$db->query($query);
                    $arDrop=array();
                    while($rowDrop =$resDrop->fetch_assoc())
                    {
                         $sec=(strtotime($rowDrop["date"]))*1000;
                         $arDrop[]=array($sec,round(log10($rowDrop['d1']),15));
                    }
                   // printr($arDrop);
                    
                    $done=true;
                    break;
                   
                }
                
            }
            
            if ($done)
            {
                break;
            }
            unset($arData);
            $i=0;
            $arData[$i]=array('SEC'=>$sec,'LOG'=>round(log10($row['d1']),15));
            
           
            $arMax=array('SEC'=>0, 'LOG'=>0);
        }
        $secPr=$sec;
    }

  
    foreach($arData as $key => $data)
    {
        unset($arData[$key]);
        $arData[$key][]=$data['SEC'];
        $arData[$key][]=$data['LOG'];
        
    }
    $dataSend["PLOT"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
	$firstDate=$arData[0][0];
    $lastDate=$arData[count($arData)-1][0];
     $color='red';
  
   //printr($arDrop);
   // точка минимума - пятая, т.е. arDrop[4] (так как нумерация идет с нуля)
   // посчитаем количество точек в высыпании.
    $dropCount=0;
  //  printr($arDrop);
    $impPoint=$arDrop[4][1];
    $impPointIndex=4;
    
    if ($dif<0)
        $pikPoint=$arDrop[3][1];
    else
        $pikPoint=$arDrop[5][1];
    
   // printr($impPoint);
    foreach($arDrop as $index=> $dropPoint)
    {
       // printr($index);
       // printr($dropPoint[1]);
        if (($dif<0)&&($index>4))
        {
            
            //printr($dropPoint[1]);
            if ($dropPoint[1]<=$impPoint)
            {
                $impPointIndex=$index;
                $impPoint=$dropPoint[1];
            }
             
           else 
            break;
        }
           
        
    }
    
    
    
    
    $pikPointIndex=5;
    foreach($arDrop as $index => $dropPoint)
    {
        if (($dif<0)&&($dropPoint[1]>=$impPoint)&&($index<$impPointIndex))
            $dropCount++; 
        if (($dif>0)&&($dropPoint[1]>=$impPoint)&&($index>4))
        {   
            $dropCount++;
            if ($dropPoint[1]>$pikPoint)
            {
                $pikPoint=$dropPoint[1];
                $pikPointIndex=$index;
            }
                
             
        }
    }
    
    
    
    
    $maxPoint=$arMax['LOG'];
    if ($arData[0][1]<$arData[count($arData)-1][1])
        $minPoint=$arData[0][1];
    else
        $minPoint=$arData[count($arData)-1][1]; 
        
      
    printr($arDrop);
    printr('max point '.$maxPoint);
    printr('min point '.$minPoint);
    printr('pik point '.$pikPoint);
    printr('imp point '.$impPoint);
        
    $pikHeight=round(($pikPoint-$impPoint)/($maxPoint-$minPoint),4);
    
    $pikPlace=round(($pikPoint-$minPoint)/($maxPoint-$minPoint),4);       
    
    $dataSend['DROP_DESCRIPTION']['DROP_COUNT']=$dropCount;
    $dataSend['DROP_DESCRIPTION']['PIK_HEIGHT']=$pikHeight;
    $dataSend['DROP_DESCRIPTION']['PIK_PLACE']=$pikPlace;
 // printr($arDrop);
    $first=true;
    
    $pikCount=1;
    if ($dif<0)
    {
        for ($i=$impPointIndex+2; $i<=8; $i++)
        {
           
            if ((($arDrop[$i][1]-$arDrop[$i-1][1])<0))
            {
                 printr($arDrop[$i][1]);
            printr($arDrop[$i-1][1]);
            printr($i);
                $pikCount++;
                break;
            }
            if ($arDrop[$i][1]==$maxPoint)
                break;
        }
    }
    if ($dif>0)
    {
        for ($i=$pikPointIndex+2; $i<=8; $i++)
        {
            printr($arDrop[$i][1]);
            printr($arDrop[$i-1][1]);
            if ($arDrop[$i][1]<=2.1)
                break;
            if ((($arDrop[$i][1]-$arDrop[$i-1][1])>0))
            {
                $pikCount++;
                break;
            }
            
        }
    }
    
    
    
     $dataSend['DROP_DESCRIPTION']['PIK_COUNT']=$pikCount;
     $dataSend['DROP_DESCRIPTION']['IMP_POINT_DATE']=$arDrop[$impPointIndex][0]/1000;
      $dataSend["TYPE"]= 0;
    $dataSend["PLOT"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => $color);//random_html_color());
    //round(log10($rowDrop['d1']),5));
    foreach($arData as $index => $data)
    {
        $arData[$index][1]=pow(10,$data['1']);
    }
    printr($arDrop);
    
    foreach($arDrop as $index => $data)
    {
        $arDrop[$index][1]=pow(10,$data['1']);
    }
      printr($arDrop);
    $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
    $dataSend["PLOT2"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');
    $dataSend["LAST_DATE"] = $lastDate;	
    $dataSend["FIRST_DATE"] = $firstDate;	
    //printr($dataSend);
   
	$dataSend=json_encode($dataSend);
	echo $dataSend;
	?>