<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// установки нейронной сети
// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)
require $_SERVER["DOCUMENT_ROOT"]."/neural/network.php";
$arLaeryCount=array(5,10,5,2);
// 

$neural=new NeuralNetwork($arLaeryCount, true);
$query="SELECT * FROM `training_set_6`";// where id<90 and id!=50 and id!=49 and id!=62 ";
$res=$neural->db->query($query);
// мин и макс значения в выборке, нужны для нормализации
$i=-1;
while ($row =$res->fetch_assoc())
{
    if ($row['TYPE']==2)
    continue;
   $i++;
    $arTrainingSets[$i][]=array($row['DROP_COUNT']/10,$row['PIK_HEIGHT'],$row['PIK_PLACE'],$row['PIK_COUNT']/10, $row['DIFF']/100);
   
    if ($row['TYPE']==0)
    { 
        $arTrainingSets[$i][]=array(1,0);
    }
     if ($row['TYPE']==1)
    {
        $arTrainingSets[$i][]=array(0,1);
    }

     if ($row['TYPE']==2)
    {
    unset( $arTrainingSets[$i]);//    $arTrainingSets[$i][]=array(0,0,1);
    }

}


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
$fileName=$_SERVER['DOCUMENT_ROOT']."/neural/weights/".$name."_endweight.txt";
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


//printr($neural->error);
//printr($neural->arWeights['cur']);

