<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";

?>
<?
$query="select min(date) as min, max(date) as max from data_2";
$res=$db->query($query);
$row =$res->fetch_assoc();
//printr($row);
$arDateMin=explode(" ",$row["min"]);
$minDate=  str_replace("-", "", $arDateMin[0]);
$arDateMax=explode(" ",$row["max"]);
$maxDate=  str_replace("-", "", $arDateMax[0]);
//printr($maxDate);
//printr($minDate);

?>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>данные со спутника метеорsdfs</title>
    <!-- стили -->
    
    <link href="/css/flot.css" rel="stylesheet" type="text/css" />
    <!-- скрипты -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="/js/script.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.selection.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.symbol.js"></script>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
    <!-- Скрипты и стили для календаря -->
    <script src="/js/jscal2.js"></script>
    <script src="/js/ru.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/jscal2.css" />
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    
    


</head>

<body>
    <p>
        Проект <a href="mailto:dmitriy.supov@gmail.com">Супова Дмитрия.</a>
    </p>
    
    <div>
        <div class="left_col">
            <p>Дата с: <input size="15" id="first_date" value="2002-03-10 21:50:00"/><button id="f_btn1">...</button></p>
            <p>по: <span style="color: white;">aaa</span><input size="15" id="last_date" value="2002-03-11 02:50:00"/><button id="f_btn2">...</button><br /></p>
        </div>
        <div class="right_col">
            <select multiple="multiple" id="date_history" size="4" name="select">
               
                
            </select>
        </div>
        <div class="clear"></div>
    </div>
    <!--<a id="showsec" style="cursor: pointer" >showsec</a>-->
    <!--задаем календарные опции-->
    <script type="text/javascript">//<![CDATA[
    
    var tmp = new Date(); //alert(tmp);
      var cal = Calendar.setup({
    	  date: <?echo $minDate;?>,
                  min:<?echo $minDate;?>,
                  max:<?echo $maxDate;?>,
    	  onSelect: function(cal) { cal.hide() },
    	  showTime   : 24,
          bottomBar:false
         
      });
      cal.manageFields("f_btn1", "first_date","%Y-%m-%d %H:%M:%S ");
      cal.manageFields("f_btn2", "last_date","%Y-%m-%d %H:%M:%S ");
    
    
    //]]></script>
    <div id="filters">
    <p> Фильтры (независимые переменные) </p>
    <table class="filters_table" id="independed">
     <tr>
         
         <td>Высота</td> 
        <td>Широта</td> 
        <td>Долгота</td> 
        <td>B</td> 
        <td>L</td> 
        
     </tr>
     <tr>
         <td>
              От <input size="3" id="Altitude_1" /> до <input size="3" id="Altitude_2" />
         </td>
         <td>
              От <input size="3" id="Latitude_1" /> до <input size="3" id="Latitude_2" />
         </td>
         <td>
              От <input size="3" id="Longitude_1" /> до <input size="3" id="Longitude_2" />
         </td>
         <td>
              От <input size="3" id="B_1" /> до <input size="3" id="B_2" />
         </td>
         <td>
              От <input size="3" id="L_1" /> до <input size="3" id="L_2" />
         </td>
     </tr>
     
    </table>
    
    
    <p> Фильтры (зависимые переменные) </p>
    <table class="filters_table" id="depended">
     <tr>
         
         <td>D1</td> 
        <td>D2</td> 
        <td>D3</td> 
        <td>D4</td> 
        <td>D5</td> 
        <td>D6</td> 
        <td>D7</td> 
        <td>D8</td> 
        
     </tr>
     <tr>
         <td>
              От <input size="2" id="D1_1" /> до <input size="3" id="D1_2" />
         </td>
         <td>
              От <input size="2" id="D2_1" /> до <input size="3" id="D2_2" />
         </td>
         <td>
              От <input size="2" id="D3_1" /> до <input size="3" id="D3_2" />
         </td>
         <td>
              От <input size="2" id="D4_1" /> до <input size="3" id="D4_2" />
         </td>
         <td>
              От <input size="2" id="D5_1" /> до <input size="3" id="D5_2" />
         </td>
         <td>
              От <input size="2" id="D6_1" /> до <input size="3" id="D6_2" />
         </td>
         <td>
              От <input size="2" id="D7_1" /> до <input size="3" id="D7_2" />
         </td>
         <td>
              От <input size="2" id="D8_1" /> до <input size="3" id="D8_2" />
         </td>
         
     </tr>
     
    </table>
    
    </div>
    <p id="choices">Показывать:
    <input type="checkbox" checked="checked" name="1"/>
    	D1	
    <input type="checkbox"  name="2"/>
    	D2	
    <input type="checkbox"  name="3"/>
    	D3	
    <input type="checkbox"  name="4"/>
    	D4	
    <input type="checkbox"  name="5"/>
    	D5	
    <input type="checkbox"  name="6"/>
    	D6	
    <input type="checkbox"  name="7"/>
    	D7	
    <input type="checkbox"  name="8"/>
    	D8	
    
    </p>
    <p>Соединять точки линиями <input type="checkbox" id="showLines" checked="checked"/></p>
    <p>Убрать промежутки <input type="checkbox" id="skipSpace"/></p>
    <p>Показывать (искать) высыпания<input type="checkbox" id="showDrop"/> <span id="countDrop"></span></p>
    <p><input id="enableTooltip" type="checkbox"/>Включить подсказки</p>
    <p> <input class="graph_width" type="text"/>Ширина графика </p>
    <p> <input class="point_count" value="500" type="text"/>Количество точек </p>
    <p><a class="builder">Построить график </a>
    
    
    
    </p>
    <div class="graphWrapper">
     <div id='mycustomscroll' class='flexcroll'>
    	<div id='horizontalwrap'>
     <div id="placeholder1" style="width:20000px;height:500px;"></div>
    </div>
    </div>
    <p></p>
    
    <div id="miniature" style="">
    
      <div id="overview1" style="width:1200px;height:100px"></div>
    
     
    </div>
     <p id="overviewLegend" style="margin-left:10px"></p>
    <p id="hoverdata">Координаты курсора
    (<span id="x">0</span>, <span id="y">0</span>). <span id="clickdata"></span></p>
    
     <p>Выбранная точка: </p>
     <table class="mercury_data_table" id="selectedPoint"></table>
    </div>
    
    
    
    
    
    
    
    
    <?/*
    
    $query="select * from data_2 limit 1000";
    $res=$db->query($query);?>
    <table class="mercury_data_table">
    
    <?
    $i=0;
    while ($row =$res->fetch_assoc())
    {?>
    <tr>
    
    <?
    if ($i==0) echo "<thead>";
    foreach($row as $colName=>$value)
    {
        ?>
        <td>
            <?if ($i==0)
            {
                echo $colName;
               
            }
              else
                  echo $value;?>           
        </td>
      <?}?>
    </tr>
    <?
    if ($i==0) echo "</thead>";
    $i++;}?>
    </table>*/?>
    
    <p>   <a class="excel" href="createexcel.php"> Выгрузить данные в excel </a></p>
</body>
</html>
