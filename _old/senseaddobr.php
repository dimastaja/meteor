<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>��������. �������� �������� ������������ ��������������������� ������������. �������</title>
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
	
	if (@$_POST['idsense']==$subdir) { $error=' ������ � ����� ID ��� ����������! '; echo $error; break;}
	}
	 
	 if ($error='')
	 {
	 echo ' ������ �������� ��������� '; 
	 }
	
	/*	
	if (($subdir!='.')&&($subdir!='..'))
	{
	$subdir = @opendir ("sensors\\".$subdir);
	if (!$subdir) {} else {
	while ($file = readdir ($subdir)) 
  {
  if (($file!='.')&&($file!='..'))
    echo " ����  "." $file<br>";
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
 // ������ 
$error='';
// ������������� ����� � ���������, � ������ �������, ������� �����. ������
$dir = opendir ("sensors");
while ($subdir = readdir ($dir)) 
	{ //echo $subdir.'<br/>';
 		if (@$_POST['idsense']==$subdir) { $error=' ������ � ����� ID ��� ����������! '; break;}
	}
closedir ($dir);

// ���� ��� ��, ���� ������

if ($error=='')
	{
		// ��������� � ����� � ���������, ������� ��� ���������� DIRname=IDsense
		chdir("sensors");
		$makedir=mkdir (@$_POST['idsense']);
		// ���� ������� ������� �������, �� ��������� �� ������� � ��������� ���-�� �� �������
		if ($makedir)
		{
			chdir(@$_POST['idsense']);
			$makedir=mkdir ('config');
			chdir("config");
			// ������� ���� � MAC �������
			$fp = fopen('macaddress.txt', 'w');
			$test = fwrite($fp,@$_POST['macaddress']);
			fclose($fp);
			// ������� ���� � ip
			$fp = fopen('ipaddress.txt', 'w');
			$test = fwrite($fp,@$_POST['ipaddress']);
			fclose($fp);
			// ������� ���� � ���������
			$fp = fopen('description.txt', 'w');
			$test = fwrite($fp,@$_POST['description']);
			fclose($fp);
			// ������� ���� � ��� ������� ��� sms ���������� (���� ����� ������)
			if (@$_POST['phonesms']!='')
			{
				$fp = fopen('pnonesms.txt', 'w');
				$test = fwrite($fp,@$_POST['phonesms']);
				fclose($fp);
			}
			// ������� ���� � email'om ��� email ���������� (���� email ������)
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
			// ������ ������ ��������
			$fp = fopen('active.txt', 'w');
			$test = fwrite($fp,'no');
			fclose($fp);
		}

		 echo ' ������ ��������! ';  
	}
else 	echo ' error '.$error.'<br/>';

	
	  ?>
 
  <p> <a href="index.php"> ��������� ��  ������� </a></p>
  <p>&nbsp;</p> 
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
