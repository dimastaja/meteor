$(document).ready(function(){
$("table.mercury_data_table tr:odd ").addClass("odd");

$('#date_history').click(function ()
    {
        var arDate = $('option:selected',this).html().split('|-|');
        $("#first_date").val(arDate[0]);
        $("#last_date").val(arDate[1]);
         
    });

});


    // задаем начальные значение свойств (опций) графиков
var options = {
    legend: {show: false},
    lines: {show: true},
     grid: {hoverable: true, clickable: true},
    points: {show: true},
    yaxis: {ticks: 10},
    selection: {mode: "xy", 
    color: 'green'
    }
};
            
            
var options2={
    legend: {show: true, container: $("#overviewLegend")},
   lines: {show: true, lineWidth: 1},
    shadowSize: 0,
    points: {show: true,symbol: "circle"},
    xaxis: {mode: "time",ticks: 10},
    yaxis: {ticks: 3, max:6},
    grid: {color: "#999"},
    selection: {mode: "xy", 
    color: 'green'}
};
// задаем начальные значения переменных
var showLines=true;

$(function () {
	
	$("#showsec").click(function(){
		
		var perehod = $("#first_date").val();
		var date= new Date(perehod.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
                var curDate=new Date;
		alert(curDate.getHours());
		alert($("#first_date").val());})
	
	var startData;
        
        
	var choiceContainer = $("#choices");
	 
	var MainPlot;
	
	var overview;
	
	var previousPoint = null;
        
        // Фильтры
        
        var firstDate=0;
        
        var lastDate=10155374560000;
        
        var Lfirst=1;
        
        var Llast=99;
    
    
    
    function createFilter()
    {
        // фильтрация по L;
        var url="";
        $("#filters input").each(function ()
        {
           url+="&filter["+$(this).attr("id")+"]="+$(this).val();
        }
        );
        return(url);
        
        
        
    }
    
    function createStartFilter()
    {
         firstDate=0;
         lastDate=10155374560000;
    	 var firstDateVal = $("#first_date").val();
    	 if (firstDateVal)
    	 {
    	 	var first= new Date(firstDateVal.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
    	 	firstDate=first.getTime()-first.getTimezoneOffset()*60000;
               // alert(first);
    	 }
    	 var last=1015537456000;
    	 var lastDateVal = $("#last_date").val();
    	 if (lastDateVal)
    	 {
    	 	var last= new Date(lastDateVal.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
    	 	lastDate=last.getTime()-last.getTimezoneOffset()*60000;
    	 }
    	 
    }


    function stringDate(date)
    {
        d=new Date(date);
       var stringDate= d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//+"  "+d.getUTCDay()+"-"+d.getUTCMonth()+"-"+d.getUTCFullYear();
        return stringDate;
    }
    
    
    // полуаем данные для графиков
    // суть - посылаем запрос аяксом с определенными параметрами (мин-макс x и y, какие поля рисовать)
    function getData(first, last, ymin, ymax)
    {
        
        
        //alert("/datasend/datasend_te.php?first="+first+"&last="+last+"&ymin="+ymin+"&ymax="+ymax+arField);
        // сам запрос 
        // Задаем поля для построения
        var arField="";
        $("#choices").find("input:checked").each(function () {
            arField+="&arField[]="+$(this).attr("name");
            
        });
        
       
        //createFilter();
        $('#date_history').append('<option>'+formatDate(first)+'|-|'+formatDate(last)+'</option>');
       
        first=first-12000*1;
        last=last+12000*1;
        
        var url="";
        url=createFilter();
        url +="&first="+first+"&last="+last+"&ymin="+ymin+"&ymax="+ymax+arField;
        url="/datasend/datasend_te.php?a=b"+url+"&pointCount="+$(".point_count").val();
        
        // убираем пробелы большие??
        if (document.getElementById("skipSpace").checked)
        {
            url+='&skip=true';
        }
        else
            url+='&skip=0';
        if (document.getElementById("showDrop").checked)
        {
            url+='&showDrop=true';
        }
        else
            url+='&showDrop=0';
        
        var getData;
        $.ajax({
            url: url,
            method: 'GET',
            async: false,
            dataType: 'json',
            success: function(series){
              getData=series;
              if (document.getElementById("showDrop").checked)
              {
                $('#countDrop').html(' Всего найдено высыпаний - '+getData['COUNT_DROP']+' шт.');
              }
              else
              {
                $('#countDrop').html('');
              }
             // alert(getData);
              
               
           }
        });
        // заново задаем опции для большого плота, для перерисовки оси X (ставим туда L)
        
        if (document.getElementById("showLines").checked)
        {
            options = {
                    legend: {show: true},
                    lines: {show: true},
                    grid: {hoverable: true, clickable: true},
                    points: {show: true,symbol: "circle"},
                    yaxis: {ticks: 10},
                    // конкретно вот здесь это делаем
                    xaxes: [  {ticks: getData["TICK"]}],
                    selection: {mode: "xy", 
                    color: 'green'}
            }; 
        }
        else
       {
           options = {
                    legend: {show: true},
                  
                    grid: {hoverable: true, clickable: true},
                    points: {show: true,symbol: "circle"},
                    yaxis: {ticks: 10},
                    // конкретно вот здесь это делаем
                    xaxes: [  {ticks: getData["TICK"]}],
                    selection: {mode: "xy", 
                    color: 'green'}
            };
       }
    	// возвращаем массив с данными для графиков
        return getData["PLOT"];
     }
    
    function formatDecade(num)
    {
        if (num<10)
            num='0'+num;
        return num;
    }
    // нормальный формат даты (боже, как убог js в этом плане...')
    
    function formatDate (miliSec)
    {
        //console.log(miliSec);
        var d=new Date();
        d.setTime(miliSec);        
        
        var year=d.getUTCFullYear();
        var month=d.getUTCMonth();
        month++;
        month=formatDecade(month);
        var day=formatDecade(d.getUTCDate());
        
        var hour=formatDecade(d.getUTCHours());
        var min=formatDecade(d.getMinutes());
        var sec=formatDecade(d.getSeconds());
        
        //2002-03-17 17:50:00 
        console.log(year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec);
        return (year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec);
    }
    
    // организуем увелечение. Задаем событие plotselected. Определяем мин-макс координат, перерисовываем MainPlot, выделяем область на overview. 
    
    $("#placeholder1").bind("plotselected", function (event, ranges) {
        // clamp the zooming to prevent eternal zoom
        if (ranges.xaxis.to - ranges.xaxis.from < 0.00001)
           ranges.xaxis.to = ranges.xaxis.from + 0.00001;
        if (ranges.yaxis.to - ranges.yaxis.from < 0.00001)
          ranges.yaxis.to = ranges.yaxis.from + 0.00001;
        // alert(ranges.yaxis.to);
        // do the zooming
        
        
        
         $("#first_date").val(formatDate(ranges.xaxis.from.toFixed(0)));
         $("#last_date").val(formatDate(ranges.xaxis.to.toFixed(0)));
         
    	 // (lastDateVal)
    	 //{
    	// 	var last= new Date(lastDateVal.replace(/(\d+)-(\d+)-(\d+)/, '$2/$3/$1'));
    	// 	lastDate=last.getTime()-last.getTimezoneOffset()*60000;
    	 //}
        xMin=ranges.xaxis.from.toFixed(3);
        xMax=ranges.xaxis.to.toFixed(3);
        
        yMin=ranges.yaxis.from.toFixed(3);
        yMax=ranges.yaxis.to.toFixed(3);
        //alert(yMin);
        //alert(yMax);
        
        MainPlot = $.plot($("#placeholder1"), getData(ranges.xaxis.from, ranges.xaxis.to, yMin, yMax), options);

        // don't fire event on the overview to prevent eternal loop
        overview.setSelection(ranges, true);
    });
    // при выделении overview - вызываем событие выделения MainPlot.
    $("#overview1").bind("plotselected", function (event, ranges) {
        MainPlot.setSelection(ranges);
    });
    
    // включаем выключаем графики.
    function plotAccordingToChoices() {
       /* var data = [];
        //var axes=MainPlot.getAxes();
        
        choiceContainer.find("input:checked").each(function () {
            var key = $(this).attr("name")-1;
            alert(key);
           
            if (key && startData[key])
                data.push(startData[key]);
        });
        
        if (data.length > 0)
            $.plot($("#overview1"), data, options2);*/
        var axes=MainPlot.getAxes();
        MainPlot=$.plot($("#placeholder1"), getData(axes.xaxis.min, axes.xaxis.max, axes.yaxis.min, axes.yaxis.max), options);
        
        
    }
     $("#showLines").click(function()
     {
        if (showLines)
            showLines=false;
        else
            showLines=true;
        var axes=MainPlot.getAxes();
        MainPlot=$.plot($("#placeholder1"), getData(axes.xaxis.min, axes.xaxis.max, axes.yaxis.min, axes.yaxis.max), options);
        
     });
    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
    
   
    
    
    // подсказки и и наведение на plot
    $("#placeholder1").bind("plothover", function (event, pos, item) {
        //alert(pos.x.toFixed(0));
        var posX=eval(pos.x.toFixed(0)+"");
        //alert(eval(posX));
        
        posX=stringDate(posX);
        //alert(posX)
        $("#x").text("X (время):"+posX);
        $("#y").text("Y (log(D):"+pos.y.toFixed(2));

        if ($("#enableTooltip:checked").length > 0) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0],
                        y = item.datapoint[1];
                    var date=stringDate(item.datapoint[0]);
                    showTooltip(item.pageX, item.pageY,
                               "Точка: время=" + date + " , " + item.series.label + "="+item.datapoint[1]);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
        }
    });
    
    // клик по точке - показываем инфу по ней (необходимо выводить кусок таблицы!)
    $("#placeholder1").bind("plotclick", function (event, pos, item) {
        if (item) {
            
            var date=stringDate(item.datapoint[0]);
            
             $.ajax({
            url: "/datasend/oneitemsend.php?date="+item.datapoint[0],
            method: 'GET',
          
            dataType: 'json',
            success: function(data){
                
              $("#selectedPoint tr, #selectedPoint thead").remove();
              var NewTr='<thead>';
              for (var key in data) {
                  var val = data [key];
                  //alert (key+' = '+val);

                   NewTr+='<td>'+key+'</td>';
					
                }
                NewTr+="</thead>";
                var tr=$(NewTr);
                $(tr).appendTo("#selectedPoint");
                NewTr='<tr>';
                for (var key in data) {
                  var val = data [key];
                  //alert (key+' = '+val);

                   NewTr+='<td>'+val+'</td>';
					
                }
                NewTr+="</tr>";
                 tr=$(NewTr);
                $(tr).appendTo("#selectedPoint");
                
             // alert(getData);
              
               
           }
        });
            
           // $("#clickdata").text("Точка: вdремя=" + date + " , " + item.series.label + "="+item.datapoint[1]);
            //plot.highlight(item.series, item.datapoint);
        }
    });

    choiceContainer.find("input").click(plotAccordingToChoices);
    //alert(startData);
    $(".builder").click(function (){
    	
    	 createStartFilter();
		 
	var graphWidth = $(".graph_width").val();
    	 if (!graphWidth)
            graphWidth =1200;
        $("#placeholder1").css({"width":graphWidth});
		 
		 
		 
         startData=getData(firstDate,lastDate, 0,20 ); 
         $(".graphWrapper").css({"display":"block"});
         MainPlot=$.plot($("#placeholder1"), startData, options);
         overview = $.plot($("#overview1"), startData, options2);
	});
        
        
        // выгрузка в excel
        $(".excel").click(function (){
            createStartFilter();
            //createFilter();
            var url="";
        url=createFilter();
        url +="&first="+firstDate+"&last="+lastDate;
        url="createexcel.php?a=b"+url;
        console.log(url);
            $(this).attr("href",url);
          //  return false;
        });
        
        
});