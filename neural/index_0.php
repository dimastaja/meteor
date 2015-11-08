<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";


// установки нейронной сети

// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)

$arLayerCount[]=2;

// количество нейронов во ВСЕХ скрытых слоях сети. Пусть будет 4, для начала (и один слой)

$arLayerCount[]=2;
//$arLayerCount[]=3;


// Один выходной нейрон
$arLayerCount[]=2;


// общее количество нейронов
$totalCount=0;
foreach($arLayerCount as $key=>$count)
{
    $totalCount+=$count;
    // заодно сделаем массив с номерами первых и последних нейронов в каждом слое. Это полезно будет впоследствии
    
    $arFirstNumbers[$key]=$totalCount-$count+1;
    $arLastNumbers[$key]=$totalCount;
}
printr($arFirstNumbers);    
printr($arLastNumbers);    
// общее количество слоев (минус один, так как первый - нулевой)

$layerCount=count($arLayerCount)-1;

/*создадим массив весов нейронной сети. Суть $arWieght[$i][$j] - связывает i-ый элемент с j-тым. Нумерация элементов - от первого
 входного и далее вниз по сети
пример нумерации
1
2 6
3 7 10
4 8
5 9
*/
//номер слоя (начинаем с первого не входного (входной - нулевой)) 
$layer=1;
// проходимся по всем нейронам, кроме нейронов, выходного слоя, у которого нейроны связаны только с предыдущими слоями
for ($i=1; $i<=($totalCount-$arLayerCount[(count($arLayerCount)-1)]); $i++)
{
    // номер связи - т.е. номер в слое нейрона, с которым связывается текущий в след слое (типа  1 - 6 1 - 7 , линк как раз  1 и 2)
    $link=1;
    // номер нейрона, с которым связывается текущий - как раз те самые 6 и 7 (здесь вычисляется номер первого нейрона в связываемом слое)
    $j=1;
    for($p=0; $p<$layer; $p++)
    {
        $j+=$arLayerCount[$p];
    }
    // копируем это значение для выяснения, не пошел ли уже след слой
    $q=$j;
    
    // присваиваем рандомные значения весам, увеличиваем в каждый проход линк, пока все нейроны связываемого слоя не пройдем
    while($link<=$arLayerCount[$layer])
    {
        $arWeights[$i][$j++]=rand(0,3);
        $link++;
    }
    // если след нейрон будет в новом слое - то увеличиваем значение layer
    if ($i==($q-1))
        $layer++;
}

// все работает, как ни странно



// обучающие выборки храним в базе. Делаем sql запрос и получаем массив с эпохой.

$query="SELECT D1_1 X1,D1_2 X2,D1_3 X3,D1_4 X4,D1_5 X5,TYPE FROM `training_set` limit 1";
$res=$db->query($query);
// мин и макс значения в выборке, нужны для нормализации
$max=0;
$min=10;
while ($row =$res->fetch_assoc())
{
    for($i=1; $i<=5; $i++)
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
//printr($arTrainingSets);
 //нормализируем (все значения от нуля до единицы)
foreach($arTrainingSets as $key => $arSet)
{
    unset($arTrainingSets[$key]);
    foreach($arSet as $index => $data)
    {
        if ($index!='TYPE')
          $arTrainingSets[0][$key][0][]=round((($data-$min)/($max-$min)),5);
        // типа два варианта 1 - "прямое" высыпание, -1 - "обратное"
        elseif ($data==2)
        {
            $arTrainingSets[0][$key][1]=array(0,1);
        }
        else
        {
            $arTrainingSets[0][$key][1]=array(1,0);
        }
    }
}

//printr($arTrainingSets);
unset($arTrainingSets);
$arTrainingSets[0][0]=array(0.3,0.5);
$arTrainingSets[0][1]=array(1,0);

$arTrainingSets[1][0]=array(0.8,0.2);
$arTrainingSets[1][1]=array(0,1);


unset($arWeights);
$arWeights[0][1][3]=1;
$arWeights[0][1][4]=2;
$arWeights[0][2][3]=3;
$arWeights[0][2][4]=1;
$arWeights[0][3][5]=2;
$arWeights[0][3][6]=3;
$arWeights[0][4][5]=1;
$arWeights[0][4][6]=2;
//printr($arWeights);


// шаг 
$error=1111;
$pogreshnost=0.1;
$n=0;
$etta=0.5;
while($error/2>$pogreshnost)
{
    

foreach($arTrainingSets as $trainingSet)
{
    $arOut=array();
    $i=1;
    $arWeight=$arWeights[$n];
    //  у входного нулевого слоя выходы равны входному вектору 
    foreach($trainingSet[0] as $inp)
    {
        $arOut[$i++]=$inp;
    }
    //$arOut[3]=0.7;
    $layer=0;
    
    // начинаем со второго слоя
    for($i=($arLayerCount[0]+1); $i<=$totalCount; $i++)
    {
        $j=1;
        // вычисляем номер первого нейрона предыдущего слоя
        for ($p=0; $p<$layer; $p++)
        {
            $j+=$arLayerCount[$p];
        }
        $link=1;
        $sum=0;
        // проходимся по всем нейронам предыдущего слоя и вычисляем сумму
        while($link<=$arLayerCount[$layer])
        {
            
            
            $sum+=$arOut[$j]*$arWeight[$j][$i];
            
           
            $link++;
            $j++;
        }
        // прогоняем сумму через фукнцию активации
        $arOut[$i]=Af($sum);
        // если этот нейрон последний в текущем слое - увеличиваем значение слоя.
        if (($i-$arLayerCount[$layer+1])==($j-1))
            $layer++;
    } 
   
    
  
    $arDelta=array();
    for($i=1; $i<=$totalCount; $i++)
        $arDelta[$i]=0;
    $error=0;
    $done=false;
    //вычисляем дельты для внешнего слоя и заодно общую ошибку (может, хватит учить?)
    $j=0;
     printr($arOut);  
    for($i=($totalCount-$arLayerCount[$layerCount]+1); $i<=$totalCount; $i++)
    {
        $curError=($trainingSet[1][$j++]-$arOut[$i]);
        printr($i);
        printr($trainingSet[1]);
        printr($curError);
        $arDelta[$i]=$arOut[$i]*(1-$arOut[$i])*$curError;
        $error+=$curError*$curError;
        // вычисляем сразу скорректированные веса для нейронов от предпоследнего к последнему
    }
    if (($error/2)<=$pogreshnost)
    {
        $done=true;
       
    }
    $n++;
    
    printr($n);
    printr($error/2);
   
    if ($done)
    {
        break;
    }
    // пройдемся по все слоям
    for($layer=$layerCount; $layer>=1; $layer--)
    {
        // проходимся по текущему слою 
        for($i=$arFirstNumbers[$layer]; $i<=$arLastNumbers[$layer]; $i++)
        {
            // пройдемся по предыдущему слою
            for($j=$arFirstNumbers[$layer-1]; $j<=$arLastNumbers[$layer-1]; $j++)
            {
                // вычисляем новый вес
                $arDeltaWeights[$n][$j][$i]=$etta*$arDelta[$i]*$arOut[$j];
                $arWeights[$n][$j][$i]=$arWeight[$j][$i]+$arDeltaWeights[$n][$j][$i];
                // считаем дельту для нейрона каждого нейрона из слоя, который из "пройдемся по предыдущему слою"
                $arDelta[$j]+=$arOut[$j]*(1-$arOut[$j])*$arWeight[$j][$i]*$arDelta[$i];
            }
        }
    }
 }   
   if ($n>100); $error=0; 
}

printr($arWeights);


// зададим функцию активации - простая сигмоидальная с альфа = 1
function Af($s)
{
    return (1/(1+exp($s)));
}
// и ее производная
function dAf($s)
{
    return $s*(1-$s);
}

// просто пройдемся вперед 
function forwardRun()
{
    
}


//printr($max);
//printr($min);
//printr($arTrainingSets);
//printr($arWeight);

// 