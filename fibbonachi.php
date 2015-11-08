<?php

/* Проект Супова Дмитрия */

require $_SERVER["DOCUMENT_ROOT"].'/function.php';

function fibbonachi ($number)
{
    
    return $number;
}
//$fib=fibbonachi(10);
//printr($fib);

/*function scan($dir)
{
        $d = array();
        $arr = opendir($dir);

        while($v = readdir($arr))
        {
                if($v == '.' or $v == '..')
                    continue;
                
                if(!is_dir($dir.DIRECTORY_SEPARATOR.$v))
                    $d[] = $v;
                
                if(is_dir($dir.DIRECTORY_SEPARATOR.$v))
                    $d[$v] = scan($dir.DIRECTORY_SEPARATOR.$v);
   }

        return $d;
}


printr(scan($_SERVER["DOCUMENT_ROOT"]."/css"));*/
$time=microtime();
$count=25;
$first=0;
$second=1;
for ($i=1; $i<=$count; $i++)
{
    echo $first."<br/>";
    $temp=$first;
    $first=$second;
    $second=$second+$temp;
}
echo(microtime()-$time)."<br/>";
?>

<?php
$time=microtime();
function fibonacci($n)
{
if ($n < 3) {
return 1;
}
else {
return fibonacci($n-1) + fibonacci($n-2);
}
}
for ($n = 1; $n <= $count; $n++) {
echo(fibonacci($n) .'<br/>');
}
echo(microtime()-$time)."<br/>";
?>


