<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>��������. �������� �������� ������������ ��������������������� ������������.�������� ������.</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">
<p><b>���������� ������ �������</b></p>

<form id="form1" name="form1" method="post" action="senseaddobr.php">
  <p>
  ID �������
  <br  />
      <input type="text" name="idsense" id="idsense" />
   </p>
  <p>
  MAC ����� �������
  <br /> 
      <input type="text" name="macaddress" id="macaddress" />
  </p>
  <p>IP ����� ������� <br />
    <input type="text" name="ipaddress" id="ipaddress" />
  </p>
  <p>  �������� (��������� �����������, ������������ ���� � �.�.)<br  />
    <textarea name="description" id="description" cols="45" rows="5"></textarea>
  </p>
  <p>
  ������� ��� sms-���������� (�������� ������, ���� ������ ����� �� ���������) <br />
    <input type="text" name="phonesms" id="phonesms" />
  </p>
  <p>  ����������� ����� ��� email-���������� (�������� ������, ���� ������ ����� �� ���������) <br />

    <input type="text" name="email" id="email" />
  </p>
  <p>
    <input type="checkbox" name="inman" id="inman" checked="checked" />
    ���������� ��������� � IM</p>
  <p>
    <input type="checkbox" name="alertbrowse" id="alertbrowse" checked="checked" />
    ��������� ��������� � ������ ������������� ��������</p>
     <p>
    <input type="checkbox" name="cutoff" id="cutoff" checked="checked" />
    ���������  � ������ ������������� ��������</p>
    <input name="" type="submit" value="�������� ������" />
</form>
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
