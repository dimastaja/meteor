<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";


    //echo $strDay;
    //die();
    
    $query="SELECT concat(MONTH(date)) dateE, count(1) cnt FROM errupt_possibility where possobility>0.5 and metka<1 and date<'2003-01-01' group by dateE order by date asc";
    $res=$db->query($query);
    $j=0;
    while($row =$res->fetch_assoc())
        $arItemsMONTH[++$j]=array($row['dateE'],$row['cnt']);
    printr($arItemsMONTH);
    $arItemsMONTH[0]=$arItemsMONTH[$j];
   // printr($j);
    unset($arItemsMONTH[$j]);
    ksort($arItemsMONTH);
    
    $arMonthTotal[1]=494965;
    $arMonthTotal[2]=584346;
    $arMonthTotal[3]=765532;
    $arMonthTotal[4]=783232;
    $arMonthTotal[5]=833195;
    $arMonthTotal[6]=607647;
    $arMonthTotal[7]=599099;
    $arMonthTotal[8]=641156;
    $arMonthTotal[9]=611184;
    $arMonthTotal[10]=520378;
    $arMonthTotal[11]=613242;
    $arMonthTotal[12]=565082;
    
    $max=833195;
    foreach($arMonthTotal as $index => $count)
    {
        $arMonthTotal[$index]=$count/$max;
    }
    printr($arMonthTotal);
    printr($arItemsMONTH);
    foreach($arItemsMONTH as $index => $count)
    {
        $arItemsMONTH[$index][1]=round($count[1]/$arMonthTotal[$index+1]);
    }
    printr($arItemsMONTH);
    
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

	
        var statMonth = <?echo json_encode($arItemsMONTH);?>;	    
    
        
		
		
        var plot = $.plot("#placeholder3", [{ data: statMonth, label: "распределение по мес"}]);
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