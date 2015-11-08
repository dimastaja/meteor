<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// установки нейронной сети
// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)




require $_SERVER["DOCUMENT_ROOT"]."/xor/network.php";
$arLaeryCount=array(2,2,  1);
// 

$neural=new NeuralNetwork($arLaeryCount, true);



/*for ($i=0; $i<round(count($arTrainingSets)/2);$i++)
{
    $arTrainingSetsNew[]=$arTrainingSets[$i];
    $arTrainingSetsNew[]=$arTrainingSets[$i+round(count($arTrainingSets)/2)];
    
}*/
$arTrainingSets=array();
$arTrainingSets[]=(array(array(0,0),array(0)));

$arTrainingSets[]=(array(array(1,0),array(1)));

$arTrainingSets[]=(array(array(0,1),array(1)));
$arTrainingSets[]=(array(array(1,1),array(1)));
//printr($arTrainingSets);
//die();

$neural->arTrainingSets=$arTrainingSets;


$neural->setWeights();
//$neural->setTraingingSet();
//$arTrainingSets=$neural->arTrainingSets;
//$neural->teaching(0.05, 0.9);
//printr($neural->arWeights['cur']);//die();
$name='neural';

printr($neural->forwardRun($arTrainingSets[0][0],$neural->arWeights['cur']));
printr($neural->forwardRun($arTrainingSets[1][0],$neural->arWeights['cur']));
printr($neural->forwardRun($arTrainingSets[2][0],$neural->arWeights['cur']));
printr($neural->forwardRun($arTrainingSets[3][0],$neural->arWeights['cur']));
die();

//printr($neural->arWeights);
/*foreach($arLaeryCount as $lc)
{
    $name.=$lc;
}
$fileName=$_SERVER['DOCUMENT_ROOT']."/xor/weights/".$name."_endweight_xor.txt";
if (false)///(is_file($fileName))
{
    $file=file($fileName);
    unset($neural->arWeights);
    $neural->arWeights['cur']=unserialize($file[0]);
}
else
{
    echo 'test';
    $i=0;
}
while ($neural->done>0)
{
    $neural->setWeights();$neural->teaching(0.00005, 0.3);
    printr('test2');
    printr($i++);
    if ($i>20)break;ScotlanD81


}
//ScotlanD81

}

$arFinalWeight=$neural->arWeights['cur'];
$arFinalWeight=array(1=>array(3=>'-11.62',4=>'12.88'),2=>array(3=>'10.99',4=>'-13.03'),3=>array(5=>'-13.34'),4=>array(5=>'13.13'));
printr($arFinalWeight);
//printr($neural->error);
//printr($neural->arWeights['cur']);
printr('summer');
printr($neural->forwardRun(array(0,0) ,$arFinalWeight));
printr($neural->forwardRun(array(1,0) ,$arFinalWeight));
printr($neural->forwardRun(array(0,1) ,$arFinalWeight));
printr($neural->forwardRun(array(1,1) ,$arFinalWeight));
*/