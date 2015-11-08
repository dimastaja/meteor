<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Энтегрум. Комплекс контроля конфигурация телекоммуникационного оборудования.Добавить сенсор.</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="main">
<div class="header"></div>
<? require('menu.html');?>
<div class="content">
<p><b>Добавление нового сенсора</b></p>

<form id="form1" name="form1" method="post" action="senseaddobr.php">
  <p>
  ID сенсора
  <br  />
      <input type="text" name="idsense" id="idsense" />
   </p>
  <p>
  MAC адрес сенсора
  <br /> 
      <input type="text" name="macaddress" id="macaddress" />
  </p>
  <p>IP адрес сенсора <br />
    <input type="text" name="ipaddress" id="ipaddress" />
  </p>
  <p>  Описание (реквизиты организации, отетственное лицо и т.д.)<br  />
    <textarea name="description" id="description" cols="45" rows="5"></textarea>
  </p>
  <p>
  Телефон для sms-оповещения (оставить пустым, если данная опция не требуется) <br />
    <input type="text" name="phonesms" id="phonesms" />
  </p>
  <p>  Электронная почта для email-оповещения (оставить пустым, если данная опция не требуется) <br />

    <input type="text" name="email" id="email" />
  </p>
  <p>
    <input type="checkbox" name="inman" id="inman" checked="checked" />
    Отправлять сообщение в IM</p>
  <p>
    <input type="checkbox" name="alertbrowse" id="alertbrowse" checked="checked" />
    Оповещать оператора в случае возникновения проблемы</p>
     <p>
    <input type="checkbox" name="cutoff" id="cutoff" checked="checked" />
    Отключать  в случае возникновения проблемы</p>
    <input name="" type="submit" value="Добавить сенсор" />
</form>
</div>
<? require('copyright.html');?>
</div>
</body>
</html>
