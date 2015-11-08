	<?php
    //echo 'test'; 
  	//подключаем базу и общие функции
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    
    $possMin=0;
    $possMax=2;
    $znak='>';
    $order='asc';
    //$Date='2003-07-08 14:16:08';
    foreach($_GET as $key => $value)
        $$key=($value?$value:'0');
    //echo 'test2';
    
    if (!$Date)
       $Date='2004-10-19 19:41:56';
    
  	//ограничение по дате
    // 2003-05-27 14:22:08 странное поведение по l
    // 2005-03-13 11:11:20 
    if ($back)
    {
        $order='desc';
        $znak='<';
    }
    printr($Date);
       //$possMin
    $query="select Date, Possobility from errupt_possibility where Date $znak '$Date' and Possobility>=0.5 and metka<1 and Possobility<=$possMax  order by date $order limit 1";
	// составляем массив цветов
    
	// делаем запрос к бд
    printr($query);
	$res=$db->query($query);
    $row =$res->fetch_assoc();
    $Date=$row['Date'];
    $Poss=$row['Possobility'];
    
    $timestam=strtotime($Date);
    //printr($timestam);
    $minDate=date('Y-m-d H:i:s',$timestam-12*40);
    $maxDate=date('Y-m-d H:i:s',$timestam+12*40);
    
    $query="select date, d1, L from data_2 where Date>'$minDate' and Date<'$maxDate' and L>1.8 and L<8 and D1>90";
    $res=$db->query($query);
    //die();
	$i=1;
	$arTiks=array();
    printr($query); //die();
	// определяем как часто будем делать псевдонимы L1 для временной шкалы (путанно выразился, но суть ясна). 
	//$count=$res->num_rows;
	 //sprintr($count);
        //printr($_GET);
    
	//printr($interval);
	// определяем пороговые значения по вертикали
    $arColor["DROPS"]='#482525';
	$arColor["D1"]='#6db000';
    
    
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
        
       
        
        // добавляем элемент в массив
        $arData[$i]=array('data'=>$row,'SEC'=>$sec,'D1'=>$row['d1'],'LOG'=>round(log10($row['d1']),15));
        
    }
    //printr($arData);
    //die();
    $arDataAll=$arData;
    unset($arData);
    foreach($arDataAll as $key => $data)
    {
        if ($Date==$data['data']['date'])
        {
            $pikPointIndex=$key;
            //printr($key);
           // printr($data);
        }
       // unset($arData[$key]);
        $arData[$key][]=$data['SEC'];
        $arData[$key][]=$data['LOG'];
        
    }
    for($i=$pikPointIndex-3; $i<=$pikPointIndex+4; $i++)
    {
        $arDrop[]=$arData[$i];
    }
    printr("arData");
    //printr($arData);
    
    $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
	
      
   // printr($arDrop);
    
      
    
    ///foreach($arDropAll as $key => $data)
    //{
       // unset($arData[$key]);
     //   $arDrop[$key][]=$data['SEC'];
     //   $arDrop[$key][]=$data['LOG'];
        
    //}
    $dataSend["PLOT2"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');//random_html_color());
    //round(log10($rowDrop['d1']),5));
    foreach($arData as $index => $data)
    {
        $arData[$index][1]=pow(10,$data['1']);
    }
    printr($arData);
    
    foreach($arDrop as $index => $data)
    {
        $arDrop[$index][1]=pow(10,$data['1']);
    }
    printr("arDrop");
    printr($arDrop);
    $dataSend["PLOT"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
    $dataSend["PLOT"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');
     //printr($dataSend);
    $dataSend["Date"] = $Date;	
	$dataSend=json_encode($dataSend);
	echo $dataSend;
	?>