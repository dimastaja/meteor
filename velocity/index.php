<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";
/*echo pow(10,2);
$query="SELECT * FROM `errupt_possibility` where possobility>0.5 and metka<1 order by Date asc";
$res=$db->query($query);
while($row =$res->fetch_assoc())
{
    $row['sec']=strtotime($row['Date']);
    $arItems[]=$row;
    
    
}
foreach($arItems as $index=> $item)
{
    //$sec=$item['sec'];
    $podryad=0;
    $arPodryad=array();
    for ($i=$index+1; $i<$index+100; $i++)
    {
        $diff=$arItems[$i]['sec']-$item['sec'];
        if (($diff>103*60)&&($diff<107*60))
        {
           
            
            $podryad++;
            $arPodryad[]=$item;
            $item=$arItems[$i];
            
                
            
            $prevCoolItem=$arItems[$i];
        }
        if ($diff>111*60)
            break;
    }
    if ($podryad>2)
    {
        $arPodryad[]=$item;
        //printr($arItems[$index]['Date']);
        //printr($arPodryad);    
        $arPodryadG=$arPodryad;
    }
        
}*/
for($i=15; $i<=20; $i++)
{
                                                 
$query="select * from errupt_in_row where ID>=$i order by ID asc limit 1";
	// составляем массив цветов
    
	//  бд
    //printr($query);
	$res=$db->query($query);
    $row =$res->fetch_assoc();
    define("RE",637130200);
    define("B0",0.27738015358128);
   //prijtr($ajP=$row['CNT']   j
    $arPoints=array();
    $arPodryad=array();   
    for($j=1; $j<=$row['CNT'];$j++)
    {  //printr($row['POINT'.$j]);
       $arPoints[]="'".$row['POINT'.$j]."'";
    }
    $datesStr=implode(',',$arPoints);
    $query="select * from errupt_possibility where Date in ($datesStr) order by Date asc";
    //printr($query);        
    $res2=$db->query($query);
    while($row =$res2->fetch_assoc())
    {
        $arPodryad[]=$row;
     
    }  
    
    $itemPr=$arPodryad[0];
    //printr($arPodryad);
    for($e=1; $e<count($arPodryad); $e++)//($arPodryadG); $i++)
    {
       // printr($item);
        $item=$arPodryad[$e];
        printr("Lpr = ".$itemPr['L']);
        printr("L = ".$item['L']);   
        //printr(sqrt($item['L'])-sqrt($itemPr['L']));
        $Ef=((1/pow($item['L'],2)-1/pow($itemPr['L'],2))*(RE)*B0)/(2000*($item['Second']-$itemPr['Second']));
        $dEf=$Ef*pow($item['L'],-1.5);
        $Ef2=$item['B']*0.001*RE*($item['L']-$itemPr['L'])/($item['Second']-$itemPr['Second']);
        $dEf2=$Ef2*pow($item['L'],-1.5);
        printr("Ef1 = ".$Ef.'; dEf1 = '.$dEf);
        //printr("Ef2 = ".$Ef2.'; dEf2 = '.$dEf2);
        $itemPr=$item;
    }      
    //printr($datesStr);             
        
       //// printr($item);
        //$item=$arPodryadG[$i];
        ////printr(sqrt($item['L'])-sqrt($itemPr['L']));
        //$Ef=((1/pow($item['L'],2)-1/pow($itemPr['L'],2))*(RE)*B0)/(2000*($item['Second']-$itemPr['Second']));
        //
        //$Ef2=$item['B']*0.001*RE*($item['L']-$itemPr['L'])/($item['Second']-$itemPr['Second']);
       // 
       // printr("Ef1 = ".$Ef);
        //printr("Ef2 = ".$Ef2);
       //$itemPr=$item;
}

$b0=0.15*2000*7200/RE;
//$b0=$b0/(1/pow(5,2)-1/pow(6,2));
echo $b0;
