<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";

$query="SELECT * FROM `errupt_possibility` where possobility>0.5 and metka<1 order by Date asc";
$res = $db-> query($query);
while($row =$res->fetch_assoc())
{
    $row['sec']=strtotime($row['Date']);
    $arItems[]=$row;
    
    
}
printr($arItems[0] );
foreach($arItems as $index=> $item)
{
    //$sec=$item['sec'];
    $podryad=0;
    $arInRow=array();
    for ($i=$index+1; $i<$index+100; $i++)
    {
        $diff=$arItems[$i]['sec']-$item['sec'];
        if (($diff>103*60)&&($diff<107*60)&&($arItems[$i]['SIDE']==$item['SIDE']))
        {
           
            $arInRow[]=$item;
            $podryad++;
            $item=$arItems[$i];
                
            
            $prevCoolItem=$arItems[$i];
        }
        if ($diff>111*60)
            break;
    }
    if ($podryad==1)
    {   
        $arInRow[]=$item;
        //printr($arInRow);
        printr($arInRow[0]['Date']);
        printr($podryad);
        $point1= $arInRow[0]['Date'];
        $point2= $arInRow[1]['Date'];
        $point3='0000-00-00 00:00:00';
        if ($arInRow[2]['Date'])
            $point3=$arInRow[2]['Date'];
        $point4='0000-00-00 00:00:00';
        if ($arInRow[3]['Date'])
            $point4=$arInRow[3]['Date'];
        $CNT=count($arInRow);
        $query="INSERT INTO `meteor`.`errupt_in_row` (`ID`, `POINT1`, `POINT2`, `POINT3`, `POINT4`, `CNT`) VALUES (NULL, '$point1', '$point2', '$point3', '$point4', '$CNT');";
        //$res=$db->query($query);
        $vsego++;
           
    }
        
}
printr($vsego);

// точно нет
//2003-11-07 15:04:51
// 2003-04-09 15:13:56 - полупример (просто на заметку его взять) и это тоже 2002-07-08 14:26:08
//printr($arItems);


