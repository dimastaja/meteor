	<?php
    
    
	//���������� ���� � ����� �������
	require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    
    
    
	//����������� �� ����
       // printr(DateTimeZone::getOffset());
       // printr($_GET);
    if ($_GET['first'])
        $first=date("YmdHis",$_GET["first"]/1000);
    else
    {
      //  printr($_GET);
        $query="select * from training_set_2 order by id desc limit 1";
        $res=$db->query($query);
        $row =$res->fetch_assoc();
      //  printr($row);
        if ($row['DATE'])
            $first=$row['DATE'];
        
      }	
  //   printr($first);  
       $filterQuery.=" and d1>0 and L<8 and L>3 ";
     
	$query="select date, d1 from data_1 where Date<'$first' ".$filterQuery." order by date desc limit 7200";
//	printr($query);
	// ������� ������ ���������, ������� ����� �� ������ �������� (��� D1 D2 � ��� �����)
	$arField=array();
	for ($i=1;  $i<=8; $i++)
	{
		$arField[]=$i;
	}
	// ���� ���� ����������� - ��������� ��
	if ($_GET["arField"]!=0)
	   $arField=$_GET["arField"];
	//printr($arField);
	// ���������� ������ ������
    $arColor["DROPS"]='#482525';
	$arColor["D1"]='#6db000';

	// ������ ������ � ��
	$res=$db->query($query);
	$i=1;
	$arTiks=array();
	// ���������� ��� ����� ����� ������ ���������� L1 ��� ��������� ����� (������� ���������, �� ���� ����). 
	//$count=$res->num_rows;
	 //sprintr($count);
        //printr($_GET);
    
	//printr($interval);
	// ���������� ��������� �������� �� ���������
    $i=-1;
    $firstData=true;
    $arMax=array('SEC'=>0, 'LOG'=>0, 'D1'=>0);
    $done=false;
    $arDrops=array();
    $lastDate=$first;
    // ���������� ��� �������
	while ($row =$res->fetch_assoc())
	{
	    
       
        $i++;
        // ����������� ����� � ������ ������� ��� js 
        $sec=(strtotime($row["date"]))*1000;
        // ������� ���������� secpr ��� ������ ������� 
        if ($firstData)
        {
            $secPr=$sec;
            $firstData=false;
        }
       
        
        // ��������� ������� � ������
        $arData[$i]=array('SEC'=>$sec,'LOG'=>round(log10($row['d1']),5));
        $lastDate=$arData[$i]['SEC'];
        // ���� ������������ �������
       
        // ���� ������� ���� ����������, �� �������� ����� ���������
        if (((-$sec+$secPr)/1000)>25)
        {
            
            
            
            // ������� ��������� ����� (��� � ��� �� ������ �����)
            unset($arData[$i]);
            $arData=array_reverse($arData);
            $first=true;
            // ������� ������� ����� (��� �� �����)
          // printr($arData);
           foreach($arData as $key => $arPoint)
            {
                if ($first) {$first=false;$arPrev=$arPoint; continue;}
                $dif=$arPoint['LOG']-$arPrev['LOG'];
                if ($dif<0.12)
                {
                  
                  
                   unset($arData[$key-1]);
                   
                   $arPrev=$arPoint; 
                   
                   //$unsetIndex=$key;
                   continue;
                }   
                else
                    break;
            }
            $arTemp=array();
            foreach($arData as $data)
            {
                $arTemp[]=$data;
            }
            $arData=$arTemp;
           // printr($arData);
           // printr(count($arData)-1);
            $arDataCount=count($arData)-1;
            for ($i=$arDataCount; $i>0; $i--)
            {
                $arPoint=$arData[$i];
                if ($i==$arDataCount) {$arPrev=$arPoint; continue;}
                
                $dif=$arPoint['LOG']-$arPrev['LOG'];
              //  printr($dif);
                if ($dif<0.12)
                {
                  
                  
                   unset($arData[$i+1]);
                   
                   $arPrev=$arPoint; 
                   
                   //$unsetIndex=$key;
                   continue;
                }   
                else
                    break;  
            }
            
            // ���������� ���� ��� ����������� ��������
            $arPrev['LOG']=0;
            
            // ���������� ��� ����� �����, � ���� �� ����� ���������
            $arMax=array('SEC'=>0, 'LOG'=>0, 'D1'=>0);
            foreach($arData as $key => $arPoint)
                if ($arPoint['LOG']> $arMax['LOG'])
                    $arMax=$arPoint;
            foreach($arData as $key => $arPoint)
            {
                // ������� ������ ��������
                $dif=$arPoint['LOG']-$arPrev['LOG'];
                // ������� ��������� - ����� �������� ���������� ��������
                
              //    printr($arPrev);
                $arPrev=$arPoint;
                
                
                // ��������� ������ � ���������� ������������ ���������
                if (($arPoint['LOG']>2)&&(($arPrev['LOG']>2.3)&&(($dif<0)&&($arPoint['SEC']<$arMax['SEC']))||(($arPoint['LOG']>2.3)&&($dif>0)&&($arPoint['SEC']>$arMax['SEC']))))
                {
                   
                  
                    if ($arPoint==$arData[1])
                        continue;
                   
                    if ($arPoint==$arData[count($arData)-1])
                        continue;
                    
                   // printr($arPoint['LOG']);
                    // ���� ������ "������������, �� ������� ������ ��������� - ���  ����� ��, ��� �����, ����� ���� ����."
                    if ($dif<0)
                    {
                        $minus=3;
                        $plus=4;
                    }
                    else
                    {
                        $minus=4;
                        $plus=3;
                    }
                    $k=0;
                    for ($i=($key-$minus); $i<=($key+$plus); $i++)
                    {
                        if (count($arData[$i]))
                        {
                            $arDrops[$k]=array($arData[$i]['SEC'],$arData[$i]['LOG']);
                            
                        }
                        $k++;
                     //   else
                            
                    }
                   
               //   printr($arDrops);
                   // printr($arPoint);
                  //  printr($arMax);
                   //  printr($dif);
                    
                    if ($dif<0)
                    {
                        $dataSend["TYPE"]=1;
                    }
                    else
                        $dataSend["TYPE"]=2;
                    $max=0;
                    $min=999999999;
                   // printr($arDrops);
                    $firstDr=true;
                    $neMon=false;
                    $plosk=false;
                    // ploskost'
                    if ($dif<0)
                    {
                        if ($arDrops[4][1]-$arDrops[1][1]<0.2)
                            $plosk=true;
                        if ($arDrops[3][1]-$arDrops[1][1]<0.1)
                            $plosk=true;
                    }
                    if ($dif>0)
                    {
                        if ($arDrops[4][1]-$arDrops[7][1]<0.2)
                            $plosk=true;
                        if ($arDrops[5][1]-$arDrops[7][1]<0.1)
                            $plosk=true;
                    }
                   // printr($dif);
                    //printr($arDrops);
                    // ������������
                    foreach($arDrops as $drop)
                    {
                        if ($drop[1]>$max) $max=$drop[1]; 
                        if ($drop[1]<$min) $min=$drop[1];         
                        if ($firstDr)
                        {
                            $firstDr=false;
                            $prevDrop=$drop;  
                            $prevDif=$dif;
                            continue;
                        }
                        
                        $dif=$drop[1]-$prevDrop[1];
                        if ($dif>0&&$prevDif<0)
                            $neMon=true;
                        
                        $prevDrop=$drop;  
                        $prevDif=$dif;           
                    }
                    //printr($max);
                    //printr($min);
                    if ((($max-$min)>0.6)&&$neMon&&!$plosk)
                    {
                       // printr($dif);
                      //  printr($arPoint);
                       // printr($arPrev);
                      //  printr($arMax);
                      //  printr($arDrops);
                        $done=true;
                        break;  
                    }
                    else
                        unset($arDrops);
                    
                }
                
            }
            
            if ($done)
            {
                break;
            }
            unset($arData);
            $i=0;
            $arData[$i]=array('SEC'=>$sec,'LOG'=>round(log10($row['d1']),5));
            
           
            $arMax=array('SEC'=>0, 'LOG'=>0);
            
           
        }
        $secPr=$sec;
        
        
    }
   // printr($arDrops);
    /* if (!count($arDrops[6]))
                    {    $arDrops[6][0]= $arDrops[5][0]+12000;
                        $arDrops[6][1]= 1.6;
                     }   
     if (!count($arDrops[7]))
                    {    $arDrops[7][0]= $arDrops[6][0]+12000;
                        $arDrops[7][1]= 1.2;
                     }   */
    //printr($arData);
    foreach($arDrops as $data)
    {
        $arDrop[]=$data;
    }
    foreach($arData as $key => $data)
    {
        unset($arData[$key]);
        $arData[$key][]=$data['SEC'];
        $arData[$key][]=$data['LOG'];
        
    }
    
  // printr($arDrops);
   // printr($arData);
    //printr($arDrops);
	
    $dataSend["PLOT"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor[D1]);
	$firstDate=$arData[0][0];
    $lastDate=$arData[count($arData)-1][0];
    $dataSend["PLOT"][] = array('label' => 'drop D1' , 'data' => $arDrop, 'color' => 'red');//random_html_color());
    $dataSend["LAST_DATE"] = $lastDate;	
    $dataSend["FIRST_DATE"] = $firstDate;	
    //printr($arData['PARAB']);
    //$dataSend["PLOT"][]=array('label'=>'External bell', 'data'=>$arData['PARAB'], 'color'=>random_html_color());
	//$dataSend["TICK"]=$arTiks;
   
	$dataSend=json_encode($dataSend);
	echo $dataSend;
	?>