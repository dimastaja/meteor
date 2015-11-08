<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Энтегрум. Комплекс контроля конфигурация телекоммуникационного оборудования. Главная</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<?
 /*
$error='';
$dir = opendir ("sensors");
 // echo "Files:<br/>";
  while ($subdir = readdir ($dir)) 
  {
  //  echo "$subdir<br>";
	
	if (@$_POST['idsense']==$subdir) { $error=' Сенсор с таким ID уже существует! '; echo $error; break;}
	}
	 
	 if ($error='')
	 {
	 echo ' сейчас начнется обработка '; 
	 }
	
	/*	
	if (($subdir!='.')&&($subdir!='..'))
	{
	$subdir = @opendir ("sensors\\".$subdir);
	if (!$subdir) {} else {
	while ($file = readdir ($subdir)) 
  {
  if (($file!='.')&&($file!='..'))
    echo " файл  "." $file<br>";
  }
  closedir($subdir);
  }
	}*/
  
  

?>
<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">

  <p>&nbsp;</p>
 <?
 // ошибка 
$error='';
// просматриваем папку с сенсорами, в случае повтора, выводим соотв. ошибку
$dir = opendir ("sensors");
while ($subdir = readdir ($dir)) 
	{ //echo $subdir.'<br/>';
 		if (@$_POST['idsense']==$subdir) { $error=' Сенсор с таким ID уже существует! '; break;}
	}
closedir ($dir);

// если все ок, идем дальше

if ($error=='')
	{
		// переходим в папку с сенсорами, создаем там директорию DIRname=IDsense
		chdir("sensors");
		$makedir=mkdir (@$_POST['idsense']);
		// если папочка успешно создана, то наполняем ее файлами с различной инф-ей по сенсору
		if ($makedir)
		{
			chdir(@$_POST['idsense']);
			$makedir=mkdir ('config');
			chdir("config");
			// создаем файл с MAC адресом
			$fp = fopen('macaddress.txt', 'w');
			$test = fwrite($fp,@$_POST['macaddress']);
			fclose($fp);
			// создаем файл с ip
			$fp = fopen('ipaddress.txt', 'w');
			$test = fwrite($fp,@$_POST['ipaddress']);
			fclose($fp);
			// создаем файл с описанием
			$fp = fopen('description.txt', 'w');
			$test = fwrite($fp,@$_POST['description']);
			fclose($fp);
			// создаем файл с тел номером для sms оповещения (если номер введен)
			if (@$_POST['phonesms']!='')
			{
				$fp = fopen('pnonesms.txt', 'w');
				$test = fwrite($fp,@$_POST['phonesms']);
				fclose($fp);
			}
			// создаем файл с email'om для email оповещения (если email введен)
			if (@$_POST['email']!='')
			{
				$fp = fopen('email.txt', 'w');
				$test = fwrite($fp,@$_POST['email']);
				fclose($fp);
			}
			if (@$_POST['inman'])
			{
				$fp = fopen('inman.txt', 'w');
				fclose($fp);
			}
			if (@$_POST['alertbrowse'])
			{
				$fp = fopen('alertbrowse.txt', 'w');
				fclose($fp);
			}
			if (@$_POST['cutoff'])
			{
				$fp = fopen('cutoff.txt', 'w');
				fclose($fp);
			}
			// делаем сенсор активным
			$fp = fopen('active.txt', 'w');
			$test = fwrite($fp,'no');
			fclose($fp);
		}

		 echo ' Сенсор добавлен! ';  
	}
else 	echo ' error '.$error.'<br/>';

	
	  ?>
 
  <p> <a href="index.php"> Вернуться на  главную </a></p>
  <p>&nbsp;</p> 
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
