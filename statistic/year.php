<?

require $_SERVER["DOCUMENT_ROOT"]."/function.php";
    $arYear=array(2002,2003,2004,2005);
    foreach($arYear as $year)
    {
        $nextYear=$year+1;
        $query="SELECT concat(MONTH(date)) dateE, count(1) cnt FROM errupt_possibility where date >= '$year-01-01 00:00:00'
AND date < '$nextYear-01-01 00:00:00' and possobility>0.5 and metka<1 group by dateE order by date asc";
        $res=$db->query($query);
        $j=0;
        while($row =$res->fetch_assoc())
            $arItemsMONTH[$year][$row['dateE']]=array($row['dateE'],$row['cnt']);
        //printr($arItemsMONTH[$year]);
        //$arItemsMONTH[$year][0]=$arItemsMONTH[$j];
       // printr($j);
        //unset($arItemsMONTH[$year][$j]);
        
        if ($year==2002)
         $arItemsMONTH[$year][1]=array(1,0);
        
        ksort($arItemsMONTH[$year]);
        
           
    }
   // printr($arItemsMONTH);
    //printr($arItemsMONTH[2002]);
    $arMonthTotal[2002][1]=0;
    $arMonthTotal[2002][2]=16734;
    $arMonthTotal[2002][3]=172236;
    $arMonthTotal[2002][4]=164755;
    $arMonthTotal[2002][5]=194567;
    $arMonthTotal[2002][6]=177962;
    $arMonthTotal[2002][7]=179401;
    $arMonthTotal[2002][8]=208900;
    $arMonthTotal[2002][9]=186092;
    $arMonthTotal[2002][10]=164682;
    $arMonthTotal[2002][11]=186679;
    $arMonthTotal[2002][12]=148852;

    $arMonthTotal[2003][1]=104420;
    $arMonthTotal[2003][2]=189266;
    $arMonthTotal[2003][3]=207850;
    $arMonthTotal[2003][4]=198817;
    $arMonthTotal[2003][5]=219634;
    $arMonthTotal[2003][6]=159145;
    $arMonthTotal[2003][7]=211594;
    $arMonthTotal[2003][8]=211847;
    $arMonthTotal[2003][9]=212001;
    $arMonthTotal[2003][10]=136499;
    $arMonthTotal[2003][11]=213206;
    $arMonthTotal[2003][12]=195620;

    $arMonthTotal[2004][1]=170318;
    $arMonthTotal[2004][2]=179379;
    $arMonthTotal[2004][3]=172282;
    $arMonthTotal[2004][4]=206275;
    $arMonthTotal[2004][5]=198318;
    $arMonthTotal[2004][6]=192030;
    $arMonthTotal[2004][7]=208104;
    $arMonthTotal[2004][8]=220409;
    $arMonthTotal[2004][9]=213091;
    $arMonthTotal[2004][10]=219197;
    $arMonthTotal[2004][11]=213357;
    $arMonthTotal[2004][12]=220610;
    
    $arMonthTotal[2005][1]=220227;
    $arMonthTotal[2005][2]=198967;
    $arMonthTotal[2005][3]=213164;
    $arMonthTotal[2005][4]=213385;
    $arMonthTotal[2005][5]=220676;
    $arMonthTotal[2005][6]=78510;
    $arMonthTotal[2005][7]=0;
    $arMonthTotal[2005][8]=0;
    $arMonthTotal[2005][9]=0;
    $arMonthTotal[2005][10]=0;
    $arMonthTotal[2005][11]=0;
    $arMonthTotal[2005][12]=0;
    
    
    $arItemsMONTH[2005][7]=array(7,0);
    $arItemsMONTH[2005][8]=array(8,0);
    $arItemsMONTH[2005][9]=array(9,0);
    $arItemsMONTH[2005][10]=array(10,0);
    $arItemsMONTH[2005][11]=array(11,0);
    $arItemsMONTH[2005][12]=array(12,0);
    $max[2002]=208900;
    $max[2003]=213206;
    $max[2004]=220610;
    $max[2005]=220676;
    
    foreach($arYear as $year)
    foreach($arMonthTotal[$year] as $index => $count)
    {
        if ($count)
        $arMonthTotal[$year][$index]=$max[$year]/$count;
    }
    //printr($arMonthTotal);
    
    
    
    foreach($arYear as $year)
    foreach($arItemsMONTH[$year] as $index => $count)
    {
        
        $arItemsMONTH[$year][$index][1]=round($count[1]*$arMonthTotal[$year][$index]);
    }
    //printr($arItemsMONTH[2002]);
    foreach($arYear as $year)
    foreach($arItemsMONTH[$year] as $index => $count)
    {
        
        $arItemsMONTHnew[$year][$index-1]=$arItemsMONTH[$year][$index];
    }
    
    printr($arItemsMONTHnew);
   //die();
    
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

	
        var statMonth2002 = <?echo json_encode($arItemsMONTHnew[2002]);?>;
        var statMonth2003 = <?echo json_encode($arItemsMONTHnew[2003]);?>;
        var statMonth2004 = <?echo json_encode($arItemsMONTHnew[2004]);?>;
        var statMonth2005 = <?echo json_encode($arItemsMONTHnew[2005]);?>;	    

        var plot = $.plot("#placeholder2002", [{ data: statMonth2002, label: "распределение по дням"}]);
        var plot = $.plot("#placeholder2003", [{ data: statMonth2003, label: "распределение по дням"}]);
        var plot = $.plot("#placeholder2004", [{ data: statMonth2004, label: "распределение по дням"}]);
        var plot = $.plot("#placeholder2005", [{ data: statMonth2005, label: "распределение по дням"}]);
        
	});
</script>

</head>

<body>
    <p>
        Проект <a href="mailto:dmitriy.supov@gmail.com">Супова Дмитрия.</a>
    </p>
    <p>Статистика помесячная по годам.</p>
  
    <div class="" style="width: 1280px;">
       
            <h1>    2002 год.</h1>
            
            <div id='horizontalwrap' style="width: 960px; float:left;">
                 <div id="placeholder2002" style="float:left;width:960px;height:900px;"></div>
                
            </div>
            
            
            <div id='horizontalwrap' style="width: 960px; float:left;"><h1>    2003 год.</h1>
                 <div id="placeholder2003" style="float:left;width:960px;height:900px;"></div>
                
            </div>
            
            
            <div id='horizontalwrap' style="width: 960px; float:left;"><h1>    2004 год.</h1>
                 <div id="placeholder2004" style="float:left;width:960px;height:900px;"></div>
                
            </div>
            
            
            <div id='horizontalwrap' style="width: 460px; float:left;"><h1>    2005 год.</h1>
                 <div id="placeholder2005" style="float:left;width:460px;height:900px;"></div>
                
            </div>
    </div>

</body>
</html>