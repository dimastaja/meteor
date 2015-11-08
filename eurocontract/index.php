<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";

define ('error',0.0000001);


for ($lyambda = 0; $lyambda<=1.001; $lyambda=$lyambda+0.001)
{
    printr('lyambda = '.$lyambda);
    printr('I1 = '.I(1,$lyambda));
    printr('L0 = '.L(0,$lyambda));
    printr('I0 = '.I(0,$lyambda));
    printr('L1 = '.L(1,$lyambda));
    
    $func = I(1,$lyambda)*L(0,$lyambda) - I(0,$lyambda)*L(1,$lyambda);
    printr($func);
    $arLyambda[]=array($lyambda,$func);
    $mn1[]=array($lyambda,I(1,$lyambda)*L(0,$lyambda));
    $mn2[]=array($lyambda,I(0,$lyambda)*L(1,$lyambda));
}

function I($nu, $x)
{
    
    $I=pow(($x/2),$nu);
    $sum=0;
    $k=0;
    $iter=100;
    while ($iter>error&&$k<100)
    {
        $iter=pow($x/2,2*$k)/(fact($k)*fact($nu+$k));
        $sum+=$iter;
        $k++;
    }
    //printr('iter I = '.$iter);
    
    return $I*$sum;
}

function L($nu, $x)
{
    $iter=100;
    $sum=0;
    while ($iter>error&&$k<100)
    {
        $iter=pow($x/2,(2*$k+$nu+1))/(G2($k+1)*G2($k+1+$nu));
        $sum+=$iter;
        $k++;
    }
    //printr('iter L = '.$iter);
    return $sum;
}

function G2($n)
{
    $g2=sqrt(pi())/pow(2,$n);
    
    for($i=1; $i<=(2*$n-1); $i=$i+2)
        $g2*=$i;
    return $g2;
}
function fact($n)
{
    $fact=1;
    for ($i=1; $i<=$n; $i++)
        $fact=$fact*$i;
    return $fact;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Создание обучающей выборки</title>
    <!-- стили -->
    
    <link href="/css/flot.css" rel="stylesheet" type="text/css" />
    <!-- скрипты -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.js"></script>
    
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

		var err1 = <?echo json_encode($arLyambda);?>;
			var mn1 = <?echo json_encode($mn1);?>;	
			var mn2 = <?echo json_encode($mn2);?>;	
            

//	err1=[1,2,4];


		
         console.log(err1);
		var plot = $.plot("#placeholder", [
			{ data: err1, label: "func"},
		     { data: mn1, label: "mn1"}, 
		      { data: mn2, label: "mn2"},
              
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
				max: 0.04
			}
		});
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
		function showTooltip(x, y, contents) {
			$("<div id='tooltip'>" + contents + "</div>").css({
				position: "absolute",
				display: "none",
				top: y + 5,
				left: x + 5,
				border: "1px solid #fdd",
				padding: "2px",
				"background-color": "#fee",
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}

		var previousPoint = null;
		$("#placeholder").bind("plothover", function (event, pos, item) {

			
				var str = "(" + pos.x.toFixed(5) + ", " + pos.y.toFixed(5) + ")";
				$("#hoverdata").text(str);
		

			
				if (item) {
					if (previousPoint != item.dataIndex) {

						previousPoint = item.dataIndex;

						$("#tooltip").remove();
						var x = item.datapoint[0].toFixed(5),
						y = item.datapoint[1].toFixed(5);

						showTooltip(item.pageX, item.pageY,
						    item.series.label + " of " + x + " = " + y);
					}
				} else {
					$("#tooltip").remove();
					previousPoint = null;            
				}
			
		});

		$("#placeholder").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});

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
       
            <div id='horizontalwrap' style="width: 33600px; float:left;">
                 <div id="placeholder" style="float:left;width:3600px;height:900px;"></div>
                
            </div>
            <div id='horizontalwrap' style="width: 33600px; float:left;">
                 <div id="placeholder2" style="float:left;width:11600px;height:900px;"></div>
                
            </div>
    </div>

</body>
</html>




