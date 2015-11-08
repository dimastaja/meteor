<script src="/js/jquery.js"></script><script src="/js/jquery.flot.js"></script>

<?

require $_SERVER["DOCUMENT_ROOT"]."/function.php";
  
// определяем максимум и минимум (лишний запрос)
$query="SELECT  max( d1 ) max, min( d1 ) min FROM `outer_belt` ";
$res=$db->query($query);
$row =$res->fetch_assoc();
$max=$row['max'];
$min=$row['min'];
$query="SELECT D1 FROM `outer_belt` order by id asc limit 10";
$normDel=$max-$min;
$res=$db->query($query);
$i=0;

$FirstLayerCount=7;
$j=1;
$numTS=0;
while ($row =$res->fetch_assoc())
{
    
    
    $D1=$row['D1'];
    $arData[$i][1]=($D1-$min)/$normDel;
    
    
    $arData[$i++][0]=$i;
}


// создаем обучающую выборку
$TSCount=0;
for($i=0; $i<count($arData)-$FirstLayerCount; $i++ )
{
    for ($j=0;  $j<$FirstLayerCount ; $j++)
    {
        $arTrainingSets[$TSCount][0][]=$arData[$i+$j][1];
    }
    $arTrainingSets[$TSCount][1][]=$arData[$i+$j][1];
    $TSCount++;
  
}


/*<table><tr><td style="vertical-align: top;"><?printr($arItems);?></td><td><?printr($arTrainingSets);?></td></tr></table>
*/



$arLaeryCount=array($FirstLayerCount,14,5,1);
// 
require $_SERVER["DOCUMENT_ROOT"]."/predict/network.php";
$neural=new NeuralNetwork($arLaeryCount, true);
// мин и макс значения в выборке, нужны для нормализации



/*for ($i=0; $i<round(count($arTrainingSets)/2);$i++)
{
    $arTrainingSetsNew[]=$arTrainingSets[$i];
    $arTrainingSetsNew[]=$arTrainingSets[$i+round(count($arTrainingSets)/2)];
    
}*/
//printr($arTrainingSetsNew);
//die();

$neural->arTrainingSets=$arTrainingSets;


$neural->setWeights();
//$neural->setTraingingSet();
//$arTrainingSets=$neural->arTrainingSets;
//$neural->teaching(0.05, 0.9);
$name='neural';


//printr($neural->arWeights);
foreach($arLaeryCount as $lc)
{
    $name.=$lc;
}
$fileName=$_SERVER['DOCUMENT_ROOT']."/predict/weights/".$name."_endweight.txt";
if (false)///(is_file($fileName))
{
    $file=file($fileName);
    unset($neural->arWeights);
    $neural->arWeights['cur']=unserialize($file[0]);
}
else
{
    $neural->teaching(0.05, 0.9);
}

$arFinalWeight=$neural->arWeights['cur'];


printr($neural->error);
printr($neural->arWeights['cur']);





//printr($arData);

$dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => 'green');
    	
       
       ?>
        <script> $(function() { var startData=<?=json_encode($dataSend["PLOT2"]);?>; MainPlot=$.plot($("#placeholder<?=$nubmer?>"), startData,{points: {show: true},lines: {show: true}}); }) </script>
        <div class='placeholder' id="placeholder<?=$nubmer?>" style=" width:11500px;height:500px;"></div> <br /><br />
      
    
