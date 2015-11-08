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
    <script language="javascript" type="text/javascript" src="/js/training_set.js"></script>
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
    <p><a class="accept" id="check">Проверить</a></p>
    <p><a class="builder" id="forward">Дальше </a>
    
    <p class="result"></p>
    
    </p>
    <div class="graphWrapper">
       
            <div id='horizontalwrap'>
                 <div id="placeholder1" style="width:500px;height:500px;"></div>
            </div>
       
    </div>
  
    
  
   
     <p>Выбранная точка: </p>
     <table class="mercury_data_table" id="selectedPoint"></table>
 
</body>
</html>
