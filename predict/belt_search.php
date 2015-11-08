<script src="/js/jquery.js"></script><script src="/js/jquery.flot.js"></script>
<? 


require $_SERVER["DOCUMENT_ROOT"]."/function.php";
for ($i=0; $i<15; $i++)
    $array1[$i]=array($i,$i);

 $lastDate=0;
 $iter=1;
 $totalRun=322;
  	//ограничение по дате
for ($run=1; $run<=$totalRun; $run++)  
{   
    $queryIns="INSERT INTO `meteor`.`outer_belt` (`ID`, `Date`, `B`, `L`, `D1`) VALUES ";
        $dataSend=array();
        printr('lastdate='.$lastDate);
        if ($lastDate)
            $first=$lastDate;
        else
        {
            $query="select Date  from outer_belt order by  Date desc limit 1";
            $res=$db->query($query);
            $row =$res->fetch_assoc();
            if ($row['Date'])
                $first=$row['Date'];
        }	
        $query="select Second,Date,B,L, D1 from data_2 where Date>'$first' and d1>500 and L<6.6 and L>2.5 and Date>='2003-04-11 00:00:00' and Date<='2003-04-15 23:59:59' order by date asc limit 1000";
    	// составляем массив цветов
        
    	// делаем запрос к бд
    	$res=$db->query($query);
        $i=-1;
        $firstData=true;
        $arData=array();
        while ($row =$res->fetch_assoc())
    	{
    	    $i++;
            $sec=$row['Second'];
            if ($firstData)
            {
                $secPr=$sec;
                $firstData=false;
            }
            $arData[$i]=$row;
            //$arData[i]=array('SEC'=>$sec, 'LOG')
            $lastDate=$arData[$i]['Date'];
            // ищем максимальный элемент
            $difSec=$sec-$secPr;
           
            // если текущий пояс закончился, то пытаемся найти высыпание
            if ($difSec>25)
            {
               // printr($arData);
                
                
                // убираем последнюю точку (она ж уже из нового пояса)
                unset($arData[$i]);
               
                // точка максимума
                $arMax=array();
                foreach($arData as $key => $arPoint)
                    if ($arPoint['D1']> $arMax['D1'])
                        $arMax=$arPoint;
                if ($arMax['D1']>2000)
                    break;
                else
                {
                    $arData=array();
                    $i=-1;
                    $firstData=true;
                }
                 
             }
            $secPr=$sec;
        }
        $arDataPrint=array();
        foreach($arData as $key => $data)
        {
           // unset($arData[$key]);
            $arDataPrint[$key][]=$data['Second'];
            $arDataPrint[$key][]=$data['D1'];
            
        }
        //printr("arData");
      //printr($arData);
        
        $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arDataPrint, 'color' => $arColor['D1']);
    	
        //printr($arData);
        if ($arData[count($arData)-1]['Date'])
            $lastDate=$arData[count($arData)-1]['Date'];
        elseif ($arData[count($arData)-2]['Date'])
            $lastDate=$arData[count($arData)-2]['Date'];
        else
            $lastDate=$arData[count($arData)-3]['Date'];
        
        
        $nubmer++;
        
        //error_reporting(E_ALL);
        $queryIns.=" (NULL, '{$arMax['Date']}', '{$arMax['L']}', '{$arMax['B']}', '{$arMax['D1']}')";
       
       /*?>
        <script> $(function() { var startData=<?=json_encode($dataSend["PLOT2"]);?>; MainPlot=$.plot($("#placeholder<?=$nubmer?>"), startData,{points: {show: true},lines: {show: true}}); }) </script>
        <div class='placeholder' id="placeholder<?=$nubmer?>" style=" width:500px;height:500px;"></div> <br /><br />
        <?*/
    
    printr($queryIns);
   $db->query($queryIns);
}