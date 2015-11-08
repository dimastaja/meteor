	<?php
    $startTime=time();
    //die();
    //echo 'test'; 
  	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    
    //printr($_GET);
    //echo 'test2';
  	//ограничение по дате
    
   
        $query="select Date from errupt_possibility order by  date desc limit 1";
        $res=$db->query($query);
        $row =$res->fetch_assoc();
       // printr($row);
        if ($row['Date'])
            $first=(strtotime($row["Date"]))*1000;
            //$first=$row['Date'];
        //printr('test');
      //  printr($first);
     //die($first);
      
      
   // printr($first);
    //die();
    
    while ($row)
    {
        //printr($first);
        $pikPointAr=array();
        $first=date("YmdHis",$first/1000);
        
        $arData=array();
        $arDataAll=array();
        $arDrop=array();
        $arMax=array();
        $arDropAll=array();
        $arTotal=array();
        //$=array();
        $dataSend=array();
        
        
        
        
        
        
        
        
        
        
        
        
        
        $arPoint=array();
        $arPrev=array();
        $arPrev=array();
        $arTemp=array();
        $arTiks=array();
        $arPikPoint=array();
        $arDrops=array();
        $arParab=array();
        $arDateMin=array();
        $arForDel=array();
        $arDateMax=array();
       $filterQuery.=" and d1>0 and L<6.6 and L>2.5 ";
    
    	$query="select date, d1 from data_2 where Date>'$first' ".$filterQuery." order by date asc limit 7200";
    // printr($query);
	// составляем массив цветов
    $arColor["DROPS"]='#482525';
	$arColor["D1"]='#6db000';
	// делаем запрос к бд
    //printr($query);
	$res=$db->query($query);
	$i=1;
	$arTiks=array();
    //printr($arTiks); //die();
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
        $arData[$i]=array('SEC'=>$sec,'D1'=>$row['d1'],'LOG'=>round(log10($row['d1']),15));
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
                    $query="select date, d1, Second, Altitude, Latitude, Longitude, B, L from data_2 where date>=$firstDropDate order by date asc limit 9";
                    //printr($query);
                    $resDrop=$db->query($query);
                    $arDrop=array();
                    while($rowDrop =$resDrop->fetch_assoc())
                    {
                         $sec=(strtotime($rowDrop["date"]))*1000;
                         $arDrop[]=array('data'=>$rowDrop,'SEC'=>$sec,'D1'=>$rowDrop['d1'], "LOG"=>round(log10($rowDrop['d1']),15));
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
            $arData[$i]=array('SEC'=>$sec,'D1'=>$row['d1'],'LOG'=>round(log10($row['d1']),15));
            
           
            $arMax=array('SEC'=>0,'D1'=>0, 'LOG'=>0);
        }
        $secPr=$sec;
    }

    $arDataAll=$arData;
    unset($arData);
    foreach($arDataAll as $key => $data)
    {
       // unset($arData[$key]);
        $arData[$key][]=$data['SEC'];
        $arData[$key][]=$data['LOG'];
        
    }
    //printr("arData");
    //printr($arData);
    
   // $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
	$firstDate=$arData[0][0];
    $lastDate=$arData[count($arData)-1][0];
    $first=$lastDate;
    //printr($lastDate);
    $color='red';
  
   //printr($arDrop);
   // точка минимума - пятая, т.е. arDrop[4] (так как нумерация идет с нуля)
   // посчитаем количество точек в высыпании.
    $dropCount=0;
  //  printr($arDrop);
    $impPoint=$arDrop[4]['D1'];
    $impPointIndex=4;
    
    if ($dif<0)
        $pikPoint=$arDrop[3]['D1'];
    else
        $pikPoint=$arDrop[5]['D1'];
    
   // printr($impPoint);
    if (!count($arDrop))
        break;
    if ($konec++>100000)
        break;
       // ищем нижнюю точку
    foreach($arDrop as $index=> $dropPoint)
    {
       // printr($index);
       // printr($dropPoint[1]);
        if (($dif<0)&&($index>4))
        {
            
            //printr($dropPoint[1]);
            if ($dropPoint['D1']<=$impPoint)
            {
                $impPointIndex=$index;
                $impPoint=$dropPoint['D1'];
            }
             
           else 
            break;
        }
           
        
    }
    
    
    
    
    $pikPointIndex=5;
    $pikPointAr=$arDrop[5];
    foreach($arDrop as $index => $dropPoint)
    {
        if (($dif<0)&&($dropPoint['D1']>$impPoint)&&($index<$impPointIndex))
            $dropCount++; 
        if (($dif>0)&&($dropPoint['D1']>$impPoint)&&($index>4))
        {   
            $dropCount++;
            if ($dropPoint['D1']>$pikPoint)
            {
                $pikPoint=$dropPoint['D1'];
                $pikPointAr=$dropPoint;
                $pikPointIndex=$index;
            }
            
            if ($index>2&&$dropPoint['D1']<$arDrop[$index-1]['D1'])
                break;
                
             
        }
    }
    
    
    
    
    $maxPoint=$arMax['D1'];
    if ($arData[0][1]<$arData[count($arData)-1][1])
        $minPoint=$arDataAll[0]['D1'];
    else
        $minPoint=$arDataAll[count($arData)-1]['D1']; 
        
      
    //printr($arDrop);
    //printr('max point '.$maxPoint);
    //printr('min point '.$minPoint);
    
    //printr('imp point '.$impPoint);
      
    $pikHeight=round(($pikPoint-$impPoint)/($maxPoint-$minPoint),4);
   
    $pikPlace=round(($pikPoint-$minPoint)/($maxPoint-$minPoint),4);
    $koeff=-1;
    if ($dif<0)
    {
        $koeff=1;
        $pikPointIndex=3;
    }    
    
        
    $prev=$arDrop[$pikPointIndex-1*$koeff]['D1'];
    $next=$arDrop[$pikPointIndex+1*$koeff]['D1'];
    
    if ($prev>$next)
    {
        $prev1=$prev;
        $prev=$next;
        $next=$prev1;
    }
        
    $difference=(($pikPoint-$prev)-abs(($next-$prev))/2)/1000;
    $pikPointAr=$arDrop[$pikPointIndex];
    //printr(" ");
    //printr("pik point index ".$pikPointIndex);
    //printr("prev ".$prev);
    //printr('pik point '.$pikPoint); 
    //printr("next ".$next);
    //printr("dif ".$difference);
    $dataSend['DROP_DESCRIPTION']['DROP_COUNT']=$dropCount;
    $dataSend['DROP_DESCRIPTION']['PIK_HEIGHT']=$pikHeight;
    $dataSend['DROP_DESCRIPTION']['PIK_PLACE']=$pikPlace;
    $dataSend['DROP_DESCRIPTION']['DIFF']=$difference;
 // printr($arDrop);
    $first=true;
    
    
    // считаем количество высыпаний
    $pikCount=1;
    if ($dif<0)
    {
        for ($i=$impPointIndex+2; $i<=8; $i++)
        {
           
            if ((($arDrop[$i]['D1']-$arDrop[$i-1]['D1'])<0))
            {
                // printr($arDrop[$i]['D1']);
            //printr($arDrop[$i-1]['D1']);
           // printr($i);
                $pikCount++;
                break;
            }
            if ($arDrop[$i]['D1']==$maxPoint)
                break;
        }
    }
    if ($dif>0)
    {
        for ($i=$pikPointIndex+2; $i<=8; $i++)
        {
            //printr($arDrop[$i]['D1']);
           // printr($arDrop[$i-1]['D1']);
            if ($arDrop[$i]['D1']<=2.1)
                break;
            if ((($arDrop[$i]['D1']-$arDrop[$i-1]['D1'])>0))
            {
                $pikCount++;
                break;
            }
            
        }
    }
    
    
    //printr($arDrop[$impPointIndex+1]);
    //printr($arDrop);
    //printr($pikPointAr);
    $dataSend['DROP_DESCRIPTION']['PIK_COUNT']=$pikCount;
    $dataSend['DROP_DESCRIPTION']['IMP_POINT_DATE']=$arDrop[$impPointIndex][0]/1000;
  //  $_GET['DROP_COUNT']/10,$_GET['PIK_HEIGHT'],$_GET['PIK_PLACE'],$_GET['PIK_COUNT']/10, $_GET['DIFF']/100
   // printr($pikPointAr);
    $possibility=file_get_contents("http://meteor.ru/neural/check.php?DROP_COUNT={$dataSend['DROP_DESCRIPTION']['DROP_COUNT']}&PIK_HEIGHT={$dataSend['DROP_DESCRIPTION']['PIK_HEIGHT']}&PIK_PLACE={$dataSend['DROP_DESCRIPTION']['PIK_PLACE']}&PIK_COUNT={$dataSend['DROP_DESCRIPTION']['PIK_COUNT']}&DIFF={$dataSend['DROP_DESCRIPTION']['DIFF']}");
    
    //printr("<b>Poss = ".($possibility)." % </b>");
    $possibility=round($possibility,5);
    
    $query="INSERT INTO `meteor`.`errupt_possibility` (
        `Date` ,
        `Second` ,
        `Altitude` ,
        `Latitude` ,
        `Longitude` ,
        `B` ,
        `L` ,
        `D1` ,
        `Possobility`
        )
        VALUES (
        '{$pikPointAr['data']['date']}', '{$pikPointAr['data']['Second']}', '{$pikPointAr['data']['Altitude']}', '{$pikPointAr['data']['Latitude']}', '{$pikPointAr['data']['Longitude']}', '{$pikPointAr['data']['B']}', '{$pikPointAr['data']['L']}', '{$pikPointAr['data']['d1']}', '$possibility'
        );";
       // printr($query);
        $res=$db->query($query);
    printr($konec);
    $dataSend["TYPE"]= 0;
    $arDropAll=$arDrop;
    unset($arDrop);
    foreach($arDropAll as $key => $data)
    {
       // unset($arData[$key]);
        $arDrop[$key][]=$data['SEC'];
        $arDrop[$key][]=$data['LOG'];
        
    }
    $dataSend["PLOT2"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => $color);//random_html_color());
    //round(log10($rowDrop['d1']),5));
    foreach($arData as $index => $data)
    {
        $arData[$index][1]=pow(10,$data['1']);
    }
    //printr($arDrop);
    
    foreach($arDrop as $index => $data)
    {
        $arDrop[$index][1]=pow(10,$data['1']);
    }
    //printr($arDrop);
    $dataSend["PLOT"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
    $dataSend["PLOT"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');
    $dataSend["LAST_DATE"] = $lastDate;	
    $dataSend["FIRST_DATE"] = $firstDate;	
    //printr($dataSend);
   $first=$lastDate;
	$dataSend=json_encode($dataSend);
    if ($konec>21)
     
     {
        echo '</br>total time is '.(time()-$startTime);die();
     }   
	}
    //echo $dataSend;
	?>