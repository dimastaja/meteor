<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>
    <input type="checkbox" name="box" id="box" />
</p>
  <p>
    <input type="submit" name="button" id="button" value="Submit" />
</p>
</form>
<?
if (@$_POST['box']) echo ' чек бокс jnvtxty'.@$_POST['box']; else echo ' ни хрена '.@$_POST['box'];
?>
</body>
</html>
