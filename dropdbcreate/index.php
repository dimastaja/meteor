<script src="/js/jquery.js"></script><script src="/js/jquery.flot.js"></script>
<? 
die();

require $_SERVER["DOCUMENT_ROOT"]."/function.php";
for ($i=0; $i<15; $i++)
    $array1[$i]=array($i,$i);

 $lastDate=0;
 $iter=1;
 $totalRun=50;
  	//ограничение по дате
for ($run=1; $run<=$totalRun; $run++)  
{   
    $queryIns="INSERT INTO `meteor`.`eruptions` (`ID`, `PROBABILITY`, `DATE`, `SECOND`, `ALTITUDE`, `LATITUDE`, `LONGITUDE`, `B`, `L`, `D1`, `FIRSTPOINT`, `LASTPOINT`) VALUES ";
    for($nubmer=1; $nubmer<=$iter; $nubmer++)
    {
        printr($nubmer);
        $dataSend=array();
       // printr('lastdate='.$lastDate);
        if ($lastDate)
            $first=date("YmdHis",$lastDate/1000);
        else
        {
            $query="select LASTPOINT  from eruptions order by  LASTPOINT desc limit 1";
            $res=$db->query($query);
            $row =$res->fetch_assoc();
            if ($row['LASTPOINT'])
                $first=$row['LASTPOINT'];
            
          }	
           $filterQuery.=" and d1>500 and L<6.6 and L>2.5 ";
        
        	$query="select date, d1 from data_1 where Date>'$first' ".$filterQuery." order by date asc limit 12000";
    	// составляем массив цветов
        
    	// делаем запрос к бд
    	$res=$db->query($query);
    	$i=1;
    	$arTiks=array();
    	 
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
           
            // если текущий пояс закончился, то пытаемся найти высыпание
            if ((($difSec)/1000)>25)
            {
                
                
                
                // убираем последнюю точку (она ж уже из нового пояса)
                unset($arData[$i]);
                
               
               
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
                        $query="select date, d1, second, altitude, latitude, longitude, b, l  from data_1 where date>=$firstDropDate order by date asc limit 9";
                        //printr($query);
                        $resDrop=$db->query($query);
                        $arDrop=array();
                        while($rowDrop =$resDrop->fetch_assoc())
                        {
                             $sec=(strtotime($rowDrop["date"]))*1000;
                             $arDrop[]=array('SEC'=>$sec,'D1'=>$rowDrop['d1'], "LOG"=>round(log10($rowDrop['d1']),15), 'second'=>$rowDrop['second'], 'altitude'=>$rowDrop['altitude'], 'latitude'=>$rowDrop['latitude'], 'longitude'=> $rowDrop['longitude'], 'b'=> $rowDrop['b'], 'l'=> $rowDrop['l'], 'date'=>$rowDrop['date']);
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
      // printr($arData);
        
        $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor['D1']);
    	$firstDate=$arData[0][0];
        //printr($arData);
        if ($arData[count($arData)-1][0])
            $lastDate=$arData[count($arData)-1][0];
        elseif ($arData[count($arData)-2][0])
            $lastDate=$arData[count($arData)-2][0];
        else
            $lastDate=$arData[count($arData)-3][0];
        
        $color='red';
      
       //printr($arDrop);
       // точка минимума - пятая, т.е. arDrop[4] (так как нумерация идет с нуля)
       // посчитаем количество точек в высыпании.
        $dropCount=0;
      //  printr($arDrop);
        $impPoint=$arDrop[4]['D1'];
        $impPointIndex=4;
        
        if ($dif<0)
        {
            $pikPoint=$arDrop[3]['D1'];
            $arPikPoint=array('DATE'=>$arDrop[3]['date'],'SECOND'=>$arDrop[3]['second'],'ALTITUDE'=>$arDrop[3]['altitude'],'LATITUDE'=>$arDrop[3]['latitude'],'LONGITUDE'=>$arDrop[3]['longitude'],'B'=>$arDrop[3]['b'],'L'=>$arDrop[3]['l'], 'D1'=>$arDrop[3]['D1']);
        }
           
        else
        {
            $pikPoint=$arDrop[5]['D1'];
            $arPikPoint=array('DATE'=>$arDrop[5]['date'],'SECOND'=>$arDrop[5]['second'],'ALTITUDE'=>$arDrop[5]['altitude'],'LATITUDE'=>$arDrop[5]['latitude'],'LONGITUDE'=>$arDrop[5]['longitude'],'B'=>$arDrop[5]['b'],'L'=>$arDrop[5]['l'], 'D1'=>$arDrop[5]['D1']);
        
        }
            
        
       // printr($impPoint);
       
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
       // printr('max point '.$maxPoint);
       // printr('min point '.$minPoint);
        
       // printr('imp point '.$impPoint);
          
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
        
        /*printr(" ");
        printr("pik point index ".$pikPointIndex);
        printr("prev ".$prev);
        printr('pik point '.$pikPoint); 
         printr("next ".$next);
         printr("dif ".$difference);*/
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
        
        
        
        $dataSend['DROP_DESCRIPTION']['PIK_COUNT']=$pikCount;
        $dataSend['DROP_DESCRIPTION']['PIK_POINT']=$arPikPoint;//$arDrop[$impPointIndex][0]/1000;
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
        //($arDrop);
       // 
        foreach($arDrop as $index => $data)
        {
            $arDrop[$index][1]=pow(10,$data['1']);
        }
        //printr($arDrop);
        $arColor["DROPS"]='#482525';
    	$arColor["D1"]='#6db000';
        $dataSend["PLOT"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor['D1']);
        //$dataSend["PLOT"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');
        $dataSend["LAST_DATE"] = $lastDate;	
        $dataSend["FIRST_DATE"] = $firstDate;
        $dataSend['DROP_DESCRIPTION']["FIRST_DATE"] = date("Y-m-d H:i:s",$firstDate/1000);
        $dataSend['DROP_DESCRIPTION']["LAST_DATE"] = date("Y-m-d H:i:s",$lastDate/1000);		
        printr($dataSend['DROP_DESCRIPTION']["FIRST_DATE"]);
        printr($dataSend['DROP_DESCRIPTION']["LAST_DATE"]);
        if (!$lastDate||( $dataSend['DROP_DESCRIPTION']["LAST_DATE"]<$dataSend['DROP_DESCRIPTION']['PIK_POINT']['DATE']))
           $dataSend['DROP_DESCRIPTION']["LAST_DATE"]=$dataSend['DROP_DESCRIPTION']['PIK_POINT']['DATE']; 		
        //printr($dataSend);
        $first=$firstDate;
    //	$dataSend=json_encode($dataSend);
    	//echo $dataSend;
    	
        
    
        $dataSend['DROP_DESCRIPTION']['PROBABILITY']=trim(file_get_contents( "http://meteor.ru/neural/check.php?DROP_COUNT={$dataSend['DROP_DESCRIPTION']['DROP_COUNT']}&PIK_HEIGHT={$dataSend['DROP_DESCRIPTION']['PIK_HEIGHT']}&PIK_PLACE={$dataSend['DROP_DESCRIPTION']['PIK_PLACE']}&PIK_COUNT={$dataSend['DROP_DESCRIPTION']['PIK_COUNT']}&DIFF={$dataSend['DROP_DESCRIPTION']['DIFF']}"));
       // printr($dataSend['DROP_DESCRIPTION']);
        
        
        
        //error_reporting(E_ALL);
        $queryIns.=" (NULL, '{$dataSend['DROP_DESCRIPTION']['PROBABILITY']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['DATE']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['SECOND']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['ALTITUDE']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['LATITUDE']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['LONGITUDE']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['B']}', '{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['L']}','{$dataSend['DROP_DESCRIPTION']['PIK_POINT']['D1']}', '{$dataSend['DROP_DESCRIPTION']["FIRST_DATE"]}', '{$dataSend['DROP_DESCRIPTION']["LAST_DATE"]}')";
        if ($nubmer!=$iter)
            $queryIns.=' , ';
        //printr($a)
       	
        //$dataSend=array();
        /*
        INSERT INTO `meteor`.`eruptions` (`ID`, `PROBABILITY`, `DATE`, `SECOND`, `ALTITUDE`, `LATITUDE`, `LONGITUDE`, `B`, `L`, `D1`, `FIRSTPOINT`, `LASTPOINT`) VALUES (NULL, '1', '2014-03-12 01:28:06', '12312', '1', '1', '1', '1', '1', '1', '2014-03-13 00:00:00', '2014-03-17 00:00:00');
        
        */
        
        //$dataSend[] = array('label' => 'D1' , 'data' => $array1, 'color' => 'green');
        //$array2=array_slice($array1,4,5);
        //$dataSend[] = array('label' => 'drop D1' , 'data' => $array2, 'color' => 'red');
        /*?>
        <script> $(function() { var startData=<?=json_encode($dataSend["PLOT"]);?>; MainPlot=$.plot($("#placeholder<?=$nubmer?>"), startData,{points: {show: true},lines: {show: true}}); }) </script>
        <div class='placeholder' id="placeholder<?=$nubmer?>" style=" width:500px;height:500px;"></div> <br /><br />
        <?*/
    }
    //printr($queryIns);
   $db->query($queryIns);
}