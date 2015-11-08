<?php

$total=0;
function SecParser($second)
{
    $hours=floor($second/3600);
   // echo ' hours <br/> ';
   // printr($hours);
    $minutes=floor(($second-3600*$hours)/60);
    //printr($minutes);
    $seconds=$second-3600*$hours-60*$minutes;
    
    return $hours.":".$minutes.":".$seconds;
    
}
 require $_SERVER["DOCUMENT_ROOT"]."/function.php";


$query="truncate data_1";
$res=$db->query($query);
if ($res)
  echo "table clear";
  
$num=1;
$dir = opendir($_SERVER["DOCUMENT_ROOT"]."/import") or die("Маза фака");
chdir($_SERVER["DOCUMENT_ROOT"]."/import");

while ($fileName = readdir($dir)){
 if ($fileName != "." && $fileName != "..") {
  
  
$query="INSERT INTO `data_2` (`Date`,`Second`, `Altitude`, `Latitude`, `Longitude`, `B`, `L`, `D1`, `D2`, `D3`, `D4`, `D5`, `D6`, `D7`, `D8`, `MSGI 40`) VALUES";

$file=file($fileName);
$i=1;
$prevSec=-1;
foreach ($file as $key=>$value)
{
    preg_match_all("/\-*\d+\.*\d*/", $value, $matchesArr);
    //printr($matchesArr[0]);

    if ($i==1)
    {
       $day=$matchesArr[0][1];
       $month=$matchesArr[0][2];
       $year=$matchesArr[0][3];
       
    }
   // printr($base_date);
    if ($i>2)
    {
        $query.="(";
        $j=1;
        
        foreach($matchesArr[0] as $data)
        {
            //printr($data);
            
            if ($j==1)
            {
                if ($prevSec>$data) $day++;
                $query.="'".$year."-".$month."-".$day." ".SecParser($data)."',";
                $query.="'".$data."'";
                $prevSec=$data*1;
            }
                
            else
                 $query.="'".$data."'";
            if ($j++!=count($matchesArr[0]))
                $query.=",";

        }
        $query.=")";

    

   if ($i==count($file))
           $query.=";";
   else
       $query.=",\n";
    }
   //printr($query);
$i++;
 // if ($i>3) break;

}
//printr($query);
printr("номер обработанного файла - {$num}");
$num++;
   $res=$db->query($query);
   if ($res)
       echo "data add ".($i-2)." <br/>";
   $total=$total+$i-2;
  // if ($i>3) break;
 }
}
printr($total);
//printr($i);
//printr(count($file)-1*1);


?>
