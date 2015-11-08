

<?php
$start_num = 1;
$end_num = 10;
?>
<HTML>
<HEAD>
<TITLE> A division table</TITLE>
</head>
<body>
<h2> A division table </h2>
<table border=1>
<?php
print("<tr>");
print ("<th></th>");
for($count_1 = $start_num;
 $count_1 <= $end_num;
 $count_1++)
  print("<th>$count_1</th>");
  print("</tr>");
for($count_1 = $start_num ;
 $count_1 <= $end_num ;
 $count_1++)
{
 print"<tr><th>$count_1</th>" ;
 for($count_2 = $start_num ;
  $count_2 <= $end_num;
  $count_2++)
  {
   $result = $count_1 / $count_2;
   echo "<td> $result</td>";
  }
   print("</tr>\n");
 
}
?>
</table>
</BODY>
</HTML>
<?
die();

 require $_SERVER["DOCUMENT_ROOT"]."/function.php";
$a=1;
$b=&$a;
$c=&$b;

printr("a={$a}; b={$b}; c={$c};");

$a=$b+$c;
printr("a={$a}; b={$b}; c={$c};");
$b=$a+$c;
printr("a={$a}; b={$b}; c={$c};");
$c=$a+$b;
printr("a={$a}; b={$b}; c={$c};");

?>
