<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>��������. �������� �������� ������������ ��������������������� ������������. ����������</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">
<p>���������� ��������� ��������</p>
<div class="monitoring">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th scope="col">ID</th>
    <th scope="col">MAC</th>
    <th scope="col">IP</th>
    <th scope="col">���������� �����</th>
    <th scope="col">OtherInf</th>
    <th scope="col">����� ������������</th>
        <th scope="col">����������</th>
  </tr>
  <?
 // ������� ������� �����������
$dir = opendir ("sensors");
 // echo "Files:<br/>";
 $classtr='odd';
 
 

 
while ($subdir = readdir ($dir)) 
	{
   // echo "$subdir<br>";
   	//	echo "sensors\\".$subdir."\\<br>";
		if (($subdir!='.')&&($subdir!='..')&&($subdir!='emerge')&&(filetype("sensors/".$subdir."/")=='dir'))
		{
		
			// ������� �������
			// ������� id
			echo '<tr class='.$classtr.'><td>'.$subdir.'</td>';
			// ������� MAC
			chdir("sensors/".$subdir."/config");
			$fp = fopen('macaddress.txt', 'r');
			$mac = fgets($fp, 20);
			echo '<td>'.$mac.'</td>';
			fclose($fp);
			
			// ������� ip
			
			$fp = fopen('ipaddress.txt', 'r');
			$ip = fgets($fp, 20);
			echo '<td>'.$ip.'</td>';
			fclose($fp);
			
			// ����������
			
			$fp = fopen('active.txt', 'r');
			$active = fgets($fp, 20);
			//echo '<td>'.$active.'</td>';
			fclose($fp);
			chdir("..");
			chdir("..");
			// ������� ������, ���������� � ��������
			chdir($subdir);
			
						date_default_timezone_set("Europe/Moscow");
$time='n/a';
			// ����������� �����
			if (file_exists('controlsum.txt')){
			$fp = fopen('controlsum.txt', 'r');
			$controlsum = fgets($fp, 20);
				fclose($fp);
				$time= date("F d Y H:i:s.", filemtime('controlsum.txt'));
			} else $controlsum='n/a';
			echo '<td>'.$controlsum.'</td>';
		
				// otherinf
			if (file_exists('otherinf.txt')){
			$fp = fopen('otherinf.txt', 'r');
			$otherinf = fgets($fp, 20);
			fclose($fp);}
			else $otherinf='n/a';
			
			echo '<td>'.$otherinf.'</td>';
			
			
			// ����� ������������ (�� control sum)
			//$time= date("F d Y H:i:s.", filemtime('controlsum.txt'));
			
			echo '<td>'.$time.'</td>';
			
			
			
			echo '<td>'.$active.'</td></tr>';
			
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
