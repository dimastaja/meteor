<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<? require $_SERVER["DOCUMENT_ROOT"]."/function.php";

?>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Создание обучающей выборки</title>
    <!-- стили -->
    
    <link href="/css/flot.css" rel="stylesheet" type="text/css" />
    <!-- скрипты -->
    <script type="text/javascript" src="/js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="/js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="/js/training_set_new.js"></script>
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
    <p>Всего выборок: <span id="total_training"></span></p>
  
    
    <input type="radio" name="type" value="1" checked="checked" />Выс.<br /><br />
    <input type="radio" name="type" value="2"/>НЕ Выс.<br /><br />
    <input type="radio" name="type" value="3"/>Вероятно<br /><br />

   <?/* <input type="radio" name="type" value="3"/>Дв.Грб.Лев.
    <input type="radio" name="type" value="4"/>Дв.Грб.Пр.
    
    <input type="radio" name="type" value="6"/>Плск.Лев.<br />
    <input type="radio" name="type" value="7"/> Плск.Пр. 
    <input type="radio" name="type" value="8"/> Мини.Лев.
    <input type="radio" name="type" value="9"/>Мини.Пр.
    <input type="radio" name="type" value="10"/> Хрень.Лев. 
    <input type="radio" name="type" value="11"/> Хрень.Пр.  */?>
    <p class="dropDescription"></p>
    <p class="result"></p>
    <p><a class="check" id="check">Проверить</a></p>
    
    <p><a class="accept" id="accept">Принять</a></p>
    
    <p><a class="builder" id="forward">Дальше </a>
    <p><a class="rebuilder" id="backward">назад </a>
    
    
    </p>
    <div class="graphWrapper" style="width: 1280px;">
       
            <div id='horizontalwrap' style="width: 600px; float:left;">
                 <div id="placeholder1" style="float:left;width:500px;height:500px;"></div>
                
            </div>
            <div id='horizontalwrap' style="width: 600px; float:left;">
                 <div id="placeholder2" style="float:left;width:500px;height:500px;"></div>
                
            </div>
    </div>
  
    
  
   
     <p>Выбранная точка: </p>
     <table class="mercury_data_table" id="selectedPoint"></table>
 
</body>
</html>
