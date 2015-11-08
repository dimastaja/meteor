<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Энтегрум. Комплекс контроля конфигурация телекоммуникационного оборудования. Активация-деактивация сенсоров</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<script>
function deAct(dir)
{
alert(' сенсор  '+dir+' деактивирован ');
}
function act(dir)
{
alert(' сенсор  '+dir+' активирован ');
}
</script>

<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">
<p>Активация-деактивация сенсоров</p>
<p>Выберете сенсор (выбор по ID) </p>
<div class="actdeact">
<?
 $dir = opendir ("sensors");
 // echo "Files:<br/>";
 $classtr='odd';
while ($subdir = readdir ($dir)) 
	{
   // echo "$subdir<br>";
   		
		if (($subdir!='.')&&($subdir!='..')&&($subdir!='emerge')&&(filetype("sensors/".$subdir)=='dir'))
		{	echo '<div id="'.$subdir.'">';
			echo '<a>'.$subdir.'</a><br>';
			$fp = fopen('sensors/'.$subdir.'/config/active.txt', 'r');
			$active = fgets($fp, 20);
			fclose($fp);
			$id=$subdir;
			//echo $id;
			if ($active=="yes") echo '<span onclick="deAct(\''.$id.'\');"> Деактивировать сенсор </span><br>'; else echo '<span class="act" onclick="act(\''.$id.'\');"> Активировать сенсор </span><br>';
			echo '</div>';
			
		}
	}
?>
</div>
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
