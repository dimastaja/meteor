<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";



for($i=0; $i<1024; $i++)
{
    $dateFrom=date('Y-m-d',mktime(0,0,5,2,26,2002)+86400*$i).' 00:00:00';
    $dateTo=date('Y-m-d',mktime(0,0,5,2,26,2002)+86400*$i).' 23:59:59';
    $query="SELECT  count(1) cnt FROM errupt_possibility where possobility>0.9 and metka<1 and date>='$dateFrom' and date<= '$dateTo'";
    $res=$db->query($query);
    $row =$res->fetch_assoc();
    //printr(date('Y-m-d',mktime(0,0,5,2,26,2002)+86400*$i));
    //printr($row);
    $arItemsDayErOnly[]=$row['cnt'];
}
$strDay=implode(',',$arItemsDayErOnly);
//echo $strDay;//die();

for ($i=-1; $i<=86801; $i=$i+3600)
{
    
    $upBorder=$i+3600;
    $query="SELECT count(* ) cnt
        FROM sec_2
        WHERE SECOND >$i
        AND SECOND <=$upBorder
         ";
        
        $res=$db->query($query);
        $row =$res->fetch_assoc();
        $arItems[]=array(($j),$row['cnt']);$j++;
        ;
  //  printr("$j час - {$row['cnt']} высыпаний "); 
}//printr($arItems);
$j=0;
    $query="SELECT concat(DAYOFMONTH(date),'-',MONTH(date),'-',Year(date)) dateE, count(1) cnt FROM errupt_possibility where possobility>0.5 and metka<1 group by dateE order by date asc limit 1024 ";
    $res=$db->query($query);
    $cnt=0;
    $summ=0;
    while($row =$res->fetch_assoc())
    {
        $arItemsDay[]=array(($j++),$row['cnt']);
        if ($row['cnt']>13)
        printr($row['dateE']. ' '. $row['cnt']);
        $cnt++;
        $sum+=$row['cnt'];
        
        $arItemsDayErOnly[]=$row['cnt'];
    }
    printr("average = ".round($sum/$cnt,3));
    //foreach ($arItemsDay as $day)
    $strDay=implode(',',$arItemsDayErOnly);
    //echo $strDay;
    //die();
    
    $query="SELECT concat(MONTH(date)) dateE, count(1) cnt FROM errupt_possibility where possobility>0.5 and metka<1 group by dateE order by date asc";
    $res=$db->query($query);
    $j=0;
    while($row =$res->fetch_assoc())
        $arItemsMONTH[++$j]=array($row['dateE'],$row['cnt']);
   // printr($arItemsMONTH);
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
    $query="SELECT concat(Year(date)) dateE, count(1) cnt FROM errupt_possibility where possobility>0.5 and metka<1 group by dateE order by date asc";
    $res=$db->query($query);
    $j=0;
    while($row =$res->fetch_assoc())
        $arItemsYear[]=array(($j++),$row['cnt']);
        //printr($arItems);
    //printr($arItemsYear);
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

		var statHour = <?echo json_encode($arItems);?>;
		var statDay = <?echo json_encode($arItemsDay);?>;	
        var statMonth = <?echo json_encode($arItemsMONTH);?>;	    
        var statYear = <?echo json_encode($arItemsYear);?>;
//	err1=[1,2,4];


		
		var plot = $.plot("#placeholder", [
			{ data: statHour }
			
    		],
            {
                xaxis: {
        				ticks: 24,
        				min: 0,
        				max: 24,
        				tickDecimals: 0
        			},
            }
		);
        var plot = $.plot("#placeholder2", [
			{ data: statDay, label: "распределение по дням"}
			
    		],
            {
                /*xaxis: {
        				ticks: 24,
        				min: 0,
        				max: 24,
        				tickDecimals: 0
        			},*/
            }
		);
        var plot = $.plot("#placeholder3", [{ data: statMonth, label: "распределение по дням"}]);
        var plot = $.plot("#placeholder4", [{ data: statYear, label: "распределение по дням"}]);
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
       
            <h1>    часы </h1>
            <p> Здесь и далее по оси Y отложено количество высыпаний. По оси x отложены часы от 1 до 24. Группировал за все вермя по часам.</p>
            <div id='horizontalwrap' style="width: 960px; float:left;">
                 <div id="placeholder" style="float:left;width:960px;height:900px;"></div>
                
            </div>
            
            <div id='horizontalwrap' style="width: 9600px; float:left;"><h1>дни </h1>
                <p>По оси x - дни,группировка шла по всем дням подряд, когда было хоть одно высыпание. Т.е. 0 - это 19 февраля 2002, 1075 - 12 июня 2005. </p>
                 <div id="placeholder2" style="float:left;width:9600px;height:900px;"></div>
                
            </div>
            
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