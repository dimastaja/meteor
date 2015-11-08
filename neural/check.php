<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// установки нейронной сети
// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)
require $_SERVER["DOCUMENT_ROOT"]."/neural/network.php";
$arLaeryCount=array(5,10,5,2);
// 
$neural=new NeuralNetwork($arLaeryCount, false);
//$neural->setWeights();
//$neural->setTraingingSet();
//$arTrainingSets=$neural->arTrainingSets;
//$neural->teaching(0.05, 0.9);
$name='neural';
foreach($arLaeryCount as $lc)
{
    $name.=$lc;
}
$file=file($_SERVER['DOCUMENT_ROOT']."/neural/weights/".$name."_endweight.txt");
unset($neural->arWeights);
$neural->arWeights[0]=unserialize($file[0]);
$arFinalWeight=$neural->arWeights[count($neural->arWeights)-1];
//printr($neural->error);
//printr($neural->arWeights[count($neural->arWeights)-1]);



//$arData=json_decode($_GET['data']);

//foreach($arData->data as $data)
  //  $checkSet[]=$data[1];
//$checkSet=normolize($checkSet);

$checkSet=array($_GET['DROP_COUNT']/10,$_GET['PIK_HEIGHT'],$_GET['PIK_PLACE'],$_GET['PIK_COUNT']/10, $_GET['DIFF']/100);
$run=$neural->forwardRun($checkSet,$arFinalWeight);
//printr($run[count($run)-4]);
//printr($run[count($run)-3]);
//printr($run[count($run)-2]);
//printr($run[count($run)-1]);
echo $run[21];
//printr($checkSet);


function normolize($arr)
{
    $max=0;
    $min=10;
    foreach($arr as $data)
    {    
        if ($data>$max)
            $max= $data;
        if ($data<$min)
            $min=$data;
    }
    foreach($arr as $key => $value)
        $arr[$key]=round((($value-$min)/($max-$min)),5);
               
    return $arr;
}
//printr($neural->forwardRun(array(0.9,0.5,0.3),$arFinalWeight));
// зададим функцию активации - простая сигмоидальная с альфа = 1

