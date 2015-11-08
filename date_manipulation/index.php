<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";

?>
<?
$y = 2002;
$m = 2;
$d = 20;


$step=5;

for ($i=0; $i<220; $i++)
{
    $startDate=date('Y-m-d',mktime(0,0,0,$m,$d+($i*$step),$y)).' 00:00:00';
    $lastDate=date('Y-m-d',mktime(0,0,0,$m,$d+(($i+1)*$step)-1,$y)).' 23:59:59';       
    
    $query="select count(1) cnt from data_2 where Date>='$startDate' and Date<='$lastDate'";
    $res=$db->query($query);
    $row =$res->fetch_assoc();
    //printr($row);
    $cnt=$row["cnt"];
    if ((7200*$step-$cnt)<=40*$step)
    printr($i.': '.date('Y-m-d',mktime(0,0,0,$m,$d+($i*$step),$y)).' - '.date('Y-m-d',mktime(0,0,0,$m,$d+(($i+1)*$step)-1,$y)).' cnt left: <strong style="font-weight:bold; font-size:14px;">'.(7200*$step-$cnt). '</strong> points');
}

//printr($maxDate);
//printr($minDate);

?>