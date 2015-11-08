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
    }
var options2 = {
        legend: {show: false},
        lines: {show: true},
        grid: {hoverable: true, clickable: true},
        points: {show: true},
        yaxis: {ticks: 10},
        selection: {mode: "xy", 
        color: 'green'
        }
    }

// задаем начальные значени€ переменных
var showLines=true;
var lastDate=0;
var firstDate=1022122940000;
var type=1;
var drop='';
$(function () {
	var startData;
    var MainPlot;
	var overview;
	function stringDate(date)
    {
        d=new Date(date);
       var stringDate= d.getUTCHours()+":"+d.getUTCMinutes()+":"+d.getUTCSeconds();//+"  "+d.getUTCDay()+"-"+d.getUTCMonth()+"-"+d.getUTCFullYear();
        return stringDate;
    }
    // полуаем данные дл€ графиков
    // суть - посылаем запрос а€ксом с определенными параметрами (мин-макс x и y, какие пол€ рисовать)
    function getData()
    {
        var getData;
        $.ajax({
            url: '/datasend/training_search.php?first='+lastDate,
            method: 'GET',
            async: false,
            dataType: 'json',
            success: function(series){
              getData=series;
              lastDate=series['LAST_DATE'];
               firstDate=series['FIRST_DATE'];
              type=series['TYPE'];
              drop=series['PLOT2'];
               
           }
        });
        // заново задаем опции дл€ большого плота, дл€ перерисовки оси X (ставим туда L)
        
       
    	// возвращаем массив с данными дл€ графиков
        return getData["PLOT"];
     }
      
    $(".builder").click(function (){
    	
    
         startData=getData(); 
         console.log(startData);
         $(".graphWrapper").css({"display":"block"});
         MainPlot=$.plot($("#placeholder1"), startData, options);
         SecondPlot=$.plot($("#placeholder2"), drop, options2);
       
	});
     
    function getDataBack()
    {
        var getData;
        $.ajax({
            url: '/datasend/training_search.php?first='+firstDate+'&back=1',
            method: 'GET',
            async: false,
            dataType: 'json',
            success: function(series){
                getData=series;
                lastDate=series['LAST_DATE'];
                firstDate=series['FIRST_DATE'];
                type=series['TYPE'];
                drop=series['PLOT2'];
               
           }
        });
        // заново задаем опции дл€ большого плота, дл€ перерисовки оси X (ставим туда L)
        
       
    	// возвращаем массив с данными дл€ графиков
        return getData["PLOT"];
     }
 
    
    $(".rebuilder").click(function (){
    	
    
         startData=getDataBack(); 
         console.log(startData);
         $(".graphWrapper").css({"display":"block"});
         MainPlot=$.plot($("#placeholder1"), startData, options);
         SecondPlot=$.plot($("#placeholder2"), drop, options2);
       
	});
    
    
    
    
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
    
    // организуем увелечение. «адаем событие plotselected. ќпредел€ем мин-макс координат, перерисовываем MainPlot, выдел€ем область на overview. 
    
    $('#accept').click(function ()
    {
        data=JSON.stringify(startData[1]);
       
        console.log(data);
        $.ajax({
            url: '/datasend/training_set.php?data='+data+'&type='+type,
            method: 'GET',
            dataType: 'text',
            success: function(text){
             
               $(".builder").click();
           }
        });
        
    })
    $('#check').click(function ()
    {
        data=JSON.stringify(startData[1]);
       
        console.log(data);
        $.ajax({
            url: '/neural/check.php?data='+data,
            method: 'GET',
            dataType: 'text',
            success: function(text){
             
               $(".result").html(text);
           }
        });
        
    })  
    
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
    $("#placeholder1,#placeholder2").bind("plothover", function (event, pos, item) {
        //alert(pos.x.toFixed(0));
        var posX=eval(pos.x.toFixed(0)+"");
        //alert(eval(posX));
        
        posX=stringDate(posX);
        //alert(posX)
        $("#x").text("X (врем€):"+posX);
        $("#y").text("Y (log(D):"+pos.y.toFixed(2));

        if (true) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                    $("#tooltip").remove();
                    var x = item.datapoint[0],
                        y = item.datapoint[1];
                    var date=stringDate(item.datapoint[0]);
                    showTooltip(item.pageX, item.pageY,
                               "Point: time=" + date + " , " + item.series.label + "="+item.datapoint[1]);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
        }
    });
    
    // клик по точке - показываем инфу по ней (необходимо выводить кусок таблицы!)
    $("#placeholder1,#placeholder2").bind("plotclick", function (event, pos, item) {
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
            
           // $("#clickdata").text("“очка: вdрем€=" + date + " , " + item.series.label + "="+item.datapoint[1]);
            //plot.highlight(item.series, item.datapoint);
        }
    });  
        
       
        
});