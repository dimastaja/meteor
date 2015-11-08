<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// установки нейронной сети
// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)
require $_SERVER["DOCUMENT_ROOT"]."/neural/network.php";
$arLaeryCount=array(7,15,8,4);
// 

$neural=new NeuralNetwork($arLaeryCount, false);
$db = new mysqli("localhost", "root", "123", "meteor");
$query="SELECT D1_1 X1,D1_2 X2,D1_3 X3,D1_4 X4,D1_5 X5,D1_6 X6,D1_7 X7 ,TYPE FROM `training_set_3` order by rand()";
$res=$db->query($query);
// мин и макс значения в выборке, нужны для нормализации
$max=0;
$min=10;
while ($row =$res->fetch_assoc())
{
   //s printr($row);
    for($i=1; $i<=7; $i++)
    {
        // сразу же логарифмируем,  чтобы не было огромных разниц, тем более, что выбиралось на основе именно логорифмов
        $row['X'.$i]=round(log10($row['X'.$i]),5);
        // зодно ищим минимум и максимум
        if ($row['X'.$i]>$max)
            $max= $row['X'.$i];
        if ($row['X'.$i]<$min)
            $min= $row['X'.$i];
    }
    $arTrainingSets[]=$row;
  
}


printr($arTrainingSets);

//нормализируем (все значения от нуля до единицы)
foreach($arTrainingSets as $key => $arSet)
{
    unset($arTrainingSets[$key]);
   //   printr($arSet);
    $max=0;
    $min=10;
    foreach($arSet as $index=> $data)
    {
        if ($index=='TYPE')
            continue;
        if ($data>$max)
            $max=$data;
        if ($data<$min)
            $min=$data;
    }
    foreach($arSet as $index => $data)
    {
        if ($index!='TYPE')
        
        
          $arTrainingSets[$key][0][]=round((($data-$min)/($max-$min)),5);
     // типа два варианта 1 - "прямое" высыпание, -1 - "обратное"
        else 
        {
            for ($co=1; $co<=4; $co++)
            {
                $valO=0;
                if ($co==$data)
                    $valO=1;
                $arTrainingSets[$key][1][]=$valO;
            }
        }
    }
}
$neural->arTrainingSets=$arTrainingSets;
//printr($neural->arTrainingSets);
//die();
$neural->setWeights();
//$neural->setTraingingSet();
//$arTrainingSets=$neural->arTrainingSets;
//$neural->teaching(0.05, 0.9);
$name='neural';
foreach($arLaeryCount as $lc)
{
    $name.=$lc;
}
$fileName=$_SERVER['DOCUMENT_ROOT']."/neural/weights/".$name."_dendweight.txt";
if (is_file($fileName))
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
//printr($neural->arWeights['cur']);

