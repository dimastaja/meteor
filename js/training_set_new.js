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

// задаем начальные значения переменных
var showLines=true;
var lastDate=0;
var firstDate=1022122940000;
var type=1;
var drop='';
var dropDescription;
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
    // полуаем данные для графиков
    // суть - посылаем запрос аяксом с определенными параметрами (мин-макс x и y, какие поля рисовать)
    function getData()
    {
        var getData;
        $.ajax({
            url: '/datasend/training_search_new.php?first='+lastDate,
            method: 'GET',
            async: false,
            dataType: 'json',
            success: function(series){
              getData=series;
              lastDate=series['LAST_DATE'];
               firstDate=series['FIRST_DATE'];
              type=series['TYPE'];
              drop=series['PLOT2'];
              dropDescription=series['DROP_DESCRIPTION'];
               
           }
        });
        // заново задаем опции для большого плота, для перерисовки оси X (ставим туда L)
        
       
    	// возвращаем массив с данными для графиков
        return getData["PLOT"];
     }
      
    $(".builder").click(function (){
    	
    
         startData=getData(); 
        // console.log(startData);
         $(".graphWrapper").css({"display":"block"});
         MainPlot=$.plot($("#placeholder1"), startData, options);
         SecondPlot=$.plot($("#placeholder2"), drop, options2);
         $('.dropDescription').html('Количество высыпавших точек - '+dropDescription['DROP_COUNT']+'<br/> Высота высыпания - '+dropDescription['PIK_HEIGHT']+'<br/> Место высыпания - '+dropDescription['PIK_PLACE']+'<br/> Количество высыпаний - '+dropDescription['PIK_COUNT'] );
         $('input:radio[name=type]').removeAttr('checked');   
         if ((dropDescription['PIK_COUNT']==1)&&(dropDescription['DROP_COUNT']==1))
            $('input:radio[name=type]:nth(0)').attr('checked',true);   
         else
            $('input:radio[name=type]:nth(1)').attr('checked',true);   
         
       
	});
     
    function getDataBack()
    {
        var getData;
        $.ajax({
            url: '/datasend/training_search_new.php?first='+firstDate+'&back=1',
            method: 'GET',
            async: false,
            dataType: 'json',
            success: function(series){
                getData=series;
                lastDate=series['LAST_DATE'];
                firstDate=series['FIRST_DATE'];
                type=series['TYPE'];
                drop=series['PLOT2'];
                dropDescription=series['DROP_DESCRIPTION'];
               
           }
        });
        // заново задаем опции для большого плота, для перерисовки оси X (ставим туда L)
        
       
    	// возвращаем массив с данными для графиков
        return getData["PLOT"];
     }
 
    
    $(".rebuilder").click(function (){
    	
    
         startData=getDataBack(); 
         //console.log(startData);
         $(".graphWrapper").css({"display":"block"});
         MainPlot=$.plot($("#placeholder1"), startData, options);
         SecondPlot=$.plot($("#placeholder2"), drop, options2);
        $('.dropDescription').html('Количество высыпавших точек - '+dropDescription['DROP_COUNT']+'<br/> Высота высыпания - '+dropDescription['PIK_HEIGHT']+'<br/> Место высыпания - '+dropDescription['PIK_PLACE']+'<br/> Количество высыпаний - '+dropDescription['PIK_COUNT'] );
        if ((dropDescription['PIK_COUNT']==1)&&(dropDescription['DROP_COUNT']==1))
            $('input:radio[name=type]:nth(0)').attr('checked',true);   
         else
            $('input:radio[name=type]:nth(1)').attr('checked',true);  
       
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
        //console.log(year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec);
        return (year+'-'+month+'-'+day+' '+hour+':'+min+':'+sec);
    }
    
    // организуем увелечение. Задаем событие plotselected. Определяем мин-макс координат, перерисовываем MainPlot, выделяем область на overview. 
    
    $('#accept').click(function ()
    {
       
        //data=JSON.stringify(startData[1]);
        var visType=$('input[name=type]').index($('input[name=type]:checked'));
        //console.log(visType);
      //  console.log(drop['data']);
        $.ajax({
            url: '/datasend/training_set_new.php?TYPE='+visType+'&DROP_COUNT='+dropDescription['DROP_COUNT'] +'&PIK_HEIGHT= '+dropDescription['PIK_HEIGHT']+'&PIK_PLACE='+dropDescription['PIK_PLACE']+'&PIK_COUNT='+dropDescription['PIK_COUNT']+'&IMP_POINT='+dropDescription['IMP_POINT_DATE'],
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
       
        //console.log(data);
        $.ajax({
            url: '/neural/check.php?DROP_COUNT='+dropDescription['DROP_COUNT'] +'&PIK_HEIGHT= '+dropDescription['PIK_HEIGHT']+'&PIK_PLACE='+dropDescription['PIK_PLACE']+'&PIK_COUNT='+dropDescription['PIK_COUNT'],
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
        $("#x").text("X (время):"+posX);
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
            
           // $("#clickdata").text("Точка: вdремя=" + date + " , " + item.series.label + "="+item.datapoint[1]);
            //plot.highlight(item.series, item.datapoint);
        }
    });  
        
       
        
});