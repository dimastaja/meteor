<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Энтегрум. Комплекс контроля конфигурация телекоммуникационного оборудования. Мониторинг</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">
<p>Матрица инцидентов</p>
<div class="monitoring">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="col">ID</th>
    <th scope="col">sms</th>
    <th scope="col">e-mail</th>
    <th scope="col">alert</th>
    <th scope="col">cut off</th>
    <th scope="col">send mess to IM</th>
      
  </tr>
  <?
 // создаем таблицу мониторинга
$dir = opendir ("sensors");
 // echo "Files:<br/>";
 $classtr='odd';
while ($subdir = readdir ($dir)) 
	{
   // echo "$subdir<br>";
   		
		if (($subdir!='.')&&($subdir!='..')&&($subdir!='emerge')&&(filetype("sensors/".$subdir)=='dir'))
		{
		
			// выводим конфиги
			// выводим id
			echo '<tr class='.$classtr.'><td>'.$subdir.'</td>';
			// выводим инфу об sms
			chdir("sensors/".$subdir."/config");
			if (file_exists('pnonesms.txt')) $sms=' yes '; else $sms=' no ';
			echo '<td>'.$sms.'</td>';
					
			// выводим инфу об email
			if (file_exists('email.txt')) $email=' yes '; else $email=' no ';
			echo '<td>'.$email.'</td>';
			
			// выводим инфу об alerte
			if (file_exists('alertbrowse.txt')) $alert=' yes '; else $alert=' no ';
			echo '<td>'.$alert.'</td>';
			
			// выводим инфу об cutof
			if (file_exists('cutoff.txt')) $cutoff=' yes '; else $cutoff=' no ';
			echo '<td>'.$cutoff.'</td>';
			
			// выводим инфу об IM
			if (file_exists('inman.txt')) $inman=' yes '; else $inman=' no ';
			echo '<td>'.$inman.'</td>';
			
			
			echo '</tr>';
					chdir("..");
			chdir("..");
			chdir("..");
		
	

			if ($classtr=='odd') $classtr='even'; else $classtr='odd';
		}


	}
  
  
  ?>
</table>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><a href="sensedel.php"></a></p>
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
