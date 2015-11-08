<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";


    //echo $strDay;
    //die();
    
    $query="SELECT concat(MONTH(date),'-', Year(date)) dateE, count(1) cnt FROM errupt_possibility where possobility>0.5 and metka<1  group by dateE order by date asc";
    $res=$db->query($query);
    $j=0;
    while($row =$res->fetch_assoc())
        $arItemsMONTH[++$j]=array($row['dateE'],$row['cnt']);
    unset($arItemsMONTH[1]);
   // $arMonthTotal[1]=494965;
    $arMonthTotal['2-2002']=16734;
    $arMonthTotal['3-2002']=172236;
    $arMonthTotal['4-2002']=164755;
    $arMonthTotal['5-2002']=194567;
    $arMonthTotal['6-2002']=177962;
    $arMonthTotal['7-2002']=179401;
    $arMonthTotal['8-2002']=208900;
    $arMonthTotal['9-2002']=186092;
    $arMonthTotal['10-2002']=164682;
    $arMonthTotal['11-2002']=186679;
    $arMonthTotal['12-2002']=148852;
    $arMonthTotal['1-2003']=104420;
    $arMonthTotal['2-2003']=189266;
    $arMonthTotal['3-2003']=207850;
    $arMonthTotal['4-2003']=198817;
    $arMonthTotal['5-2003']=219634;
    $arMonthTotal['6-2003']=159145;
    $arMonthTotal['7-2003']=211594;
    $arMonthTotal['8-2003']=211847;
    $arMonthTotal['9-2003']=212001;
    $arMonthTotal['10-2003']=136499;
    $arMonthTotal['11-2003']=213206;
    $arMonthTotal['12-2003']=195620;
    $arMonthTotal['1-2004']=170318;
    $arMonthTotal['2-2004']=179379;
    $arMonthTotal['3-2004']=172282;
    $arMonthTotal['4-2004']=206275;
    $arMonthTotal['5-2004']=198318;
    $arMonthTotal['6-2004']=192030;
    $arMonthTotal['7-2004']=208104;
    $arMonthTotal['8-2004']=220409;
    $arMonthTotal['9-2004']=213091;
    $arMonthTotal['10-2004']=219197;
    $arMonthTotal['11-2004']=213357;
    $arMonthTotal['12-2004']=220610;
    $arMonthTotal['1-2005']=220227;
    $arMonthTotal['2-2005']=198967;
    $arMonthTotal['3-2005']=213164;
    $arMonthTotal['4-2005']=213385;
    $arMonthTotal['5-2005']=220676;
    $arMonthTotal['6-2005']=78510;

    
    $max=220676;
    foreach($arMonthTotal as $index => $count)
    {
        $arMonthTotal[$index]=$count/$max;
    }
    printr($arMonthTotal);
    printr($arItemsMONTH);
    
    foreach($arItemsMONTH as $index => $count)
    {
        printr($count[0]);
        $arItemsMONTHNew[]=array(++$i,round($count[1]/$arMonthTotal[$count[0]]));
    }
    printr($arItemsMONTHNew);//die();
    $arColor["D1"]='#6db000';
    $data[]=array('label' => 'events' , 'data' => $arItemsMONTHNew, 'color' => $arColor[D1]);;
    //$arWolf[]=array(1,107.4);
    $arWolf[]=array(1,98.4);
$arWolf[]=array(2,120.7);
$arWolf[]=array(3,120.8);
$arWolf[]=array(4,88.3);
$arWolf[]=array(5,99.6);
$arWolf[]=array(6,116.4);
$arWolf[]=array(7,109.6);
$arWolf[]=array(8,97.5);
$arWolf[]=array(9,95.5);
$arWolf[]=array(10,80.8);
$arWolf[]=array(11,79.7);
$arWolf[]=array(12,46.0);
$arWolf[]=array(13,61.1);
$arWolf[]=array(14,60.0);
$arWolf[]=array(15,54.6);
$arWolf[]=array(16,77.4);
$arWolf[]=array(17,83.3);
$arWolf[]=array(18,72.7);
$arWolf[]=array(19,48.7);
$arWolf[]=array(20,65.5);
$arWolf[]=array(21,67.3);
$arWolf[]=array(22,46.5);
$arWolf[]=array(23,37.3);
$arWolf[]=array(24,45.8);
$arWolf[]=array(25,49.1);
$arWolf[]=array(26,39.3);
$arWolf[]=array(27,41.5);
$arWolf[]=array(28,43.2);
$arWolf[]=array(29,51.1);
$arWolf[]=array(30,40.9);
$arWolf[]=array(31,27.7);
$arWolf[]=array(32,48.0);
$arWolf[]=array(33,43.5);
$arWolf[]=array(34,17.9);
$arWolf[]=array(35,31.3);
$arWolf[]=array(36,29.1);

    foreach($arWolf as $index=> $item)
        $arWolf[$index][1]=$item[1]*2;
    $data[]=array('label' => 'wolf' , 'data' => $arWolf, 'color' => 'red');;
    

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>данные со спутника метеорsdfs</title>
    <!-- стили -->
    
    <link href="/css/flot.css" rel="stylesheet" type="text/css" />
    <!-- скрипты -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.js"></script>
    <!--<script language="javascript" type="text/javascript" src="/js/statistic.js"></script>-->
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.selection.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.symbol.js"></script>
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
    <!-- Скрипты и стили для календаря -->
    <script src="/js/jscal2.js"></script>
    <script src="/js/ru.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/jscal2.css" />
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    
    
<script>
$(function() {

	
        var statMonth = <?echo json_encode($data);?>;	    
    
        
		
		
        var plot = $.plot("#placeholder3", statMonth);
        /*var plot = $.plot("#placeholder2", [
			{ data: err1, label: "error1"},
			{ data: err2, label: "error2"},
			 
		], {
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			yaxis: {
				min: 0,
				max: 2
			}
		});*/
	

		// Add the Flot version string to the footer

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});
</script>

</head>

<body>
    <p>
        Проект <a href="mailto:dmitriy.supov@gmail.com">Супова Дмитрия.</a>
    </p>
    <p>Ошибки</p>
  
    <div class="" style="width: 1280px;">
       
            
            
            <div id='horizontalwrap' style="width: 2600px; float:left;"><h1>месяцы</h1>
                <p> Группировка по месяц. 0 - это февраль 2002, 40 - июнь 2005</p>
                 <div id="placeholder3" style="float:left;width:2600px;height:900px;"></div>
                
            </div>
           
            <div id='horizontalwrap' style="width: 260px; float:left;"> <h1>года</h1>
                <p>Группировка по годам. 0 - 2002, 3 - 2005. </p>
                 <div id="placeholder4" style="float:left;width:260px;height:900px;"></div>
                
            </div>
    </div>

</body>
</html>