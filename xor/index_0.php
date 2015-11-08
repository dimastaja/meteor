<?
require $_SERVER["DOCUMENT_ROOT"]."/function.php";


// ��������� ��������� ����

// �����. ��� ������ ���������� ������ (� ��� �� ���� ��� ����, � ��� ��� ������)

$arLayerCount[]=2;

// ���������� �������� �� ���� ������� ����� ����. ����� ����� 4, ��� ������ (� ���� ����)

$arLayerCount[]=2;
//$arLayerCount[]=3;


// ���� �������� ������
$arLayerCount[]=2;


// ����� ���������� ��������
$totalCount=0;
foreach($arLayerCount as $key=>$count)
{
    $totalCount+=$count;
    // ������ ������� ������ � �������� ������ � ��������� �������� � ������ ����. ��� ������� ����� ������������
    
    $arFirstNumbers[$key]=$totalCount-$count+1;
    $arLastNumbers[$key]=$totalCount;
}
printr($arFirstNumbers);    
printr($arLastNumbers);    
// ����� ���������� ����� (����� ����, ��� ��� ������ - �������)

$layerCount=count($arLayerCount)-1;

/*�������� ������ ����� ��������� ����. ���� $arWieght[$i][$j] - ��������� i-�� ������� � j-���. ��������� ��������� - �� �������
 �������� � ����� ���� �� ����
������ ���������
1
2 6
3 7 10
4 8
5 9
*/
//����� ���� (�������� � ������� �� �������� (������� - �������)) 
$layer=1;
// ���������� �� ���� ��������, ����� ��������, ��������� ����, � �������� ������� ������� ������ � ����������� ������
for ($i=1; $i<=($totalCount-$arLayerCount[(count($arLayerCount)-1)]); $i++)
{
    // ����� ����� - �.�. ����� � ���� �������, � ������� ����������� ������� � ���� ���� (����  1 - 6 1 - 7 , ���� ��� ���  1 � 2)
    $link=1;
    // ����� �������, � ������� ����������� ������� - ��� ��� �� ����� 6 � 7 (����� ����������� ����� ������� ������� � ����������� ����)
    $j=1;
    for($p=0; $p<$layer; $p++)
    {
        $j+=$arLayerCount[$p];
    }
    // �������� ��� �������� ��� ���������, �� ����� �� ��� ���� ����
    $q=$j;
    
    // ����������� ��������� �������� �����, ����������� � ������ ������ ����, ���� ��� ������� ������������ ���� �� �������
    while($link<=$arLayerCount[$layer])
    {
        $arWeights[$i][$j++]=rand(0,3);
        $link++;
    }
    // ���� ���� ������ ����� � ����� ���� - �� ����������� �������� layer
    if ($i==($q-1))
        $layer++;
}

// ��� ��������, ��� �� �������



// ��������� ������� ������ � ����. ������ sql ������ � �������� ������ � ������.

$query="SELECT D1_1 X1,D1_2 X2,D1_3 X3,D1_4 X4,D1_5 X5,TYPE FROM `training_set` limit 1";
$res=$db->query($query);
// ��� � ���� �������� � �������, ����� ��� ������������
$max=0;
$min=10;
while ($row =$res->fetch_assoc())
{
    for($i=1; $i<=5; $i++)
    {
        
        // ����� �� �������������,  ����� �� ���� �������� ������, ��� �����, ��� ���������� �� ������ ������ ����������
        
        $row['X'.$i]=round(log10($row['X'.$i]),5);
        
        // ����� ���� ������� � �������� 
         
        if ($row['X'.$i]>$max)
            $max= $row['X'.$i];
        if ($row['X'.$i]<$min)
            $min= $row['X'.$i];
    }
        
    $arTrainingSets[]=$row;
}
//printr($arTrainingSets);
 //������������� (��� �������� �� ���� �� �������)
foreach($arTrainingSets as $key => $arSet)
{
    unset($arTrainingSets[$key]);
    foreach($arSet as $index => $data)
    {
        if ($index!='TYPE')
          $arTrainingSets[0][$key][0][]=round((($data-$min)/($max-$min)),5);
        // ���� ��� �������� 1 - "������" ���������, -1 - "��������"
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


// ��� 
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
    //  � �������� �������� ���� ������ ����� �������� ������� 
    foreach($trainingSet[0] as $inp)
    {
        $arOut[$i++]=$inp;
    }
    //$arOut[3]=0.7;
    $layer=0;
    
    // �������� �� ������� ����
    for($i=($arLayerCount[0]+1); $i<=$totalCount; $i++)
    {
        $j=1;
        // ��������� ����� ������� ������� ����������� ����
        for ($p=0; $p<$layer; $p++)
        {
            $j+=$arLayerCount[$p];
        }
        $link=1;
        $sum=0;
        // ���������� �� ���� �������� ����������� ���� � ��������� �����
        while($link<=$arLayerCount[$layer])
        {
            
            
            $sum+=$arOut[$j]*$arWeight[$j][$i];
            
           
            $link++;
            $j++;
        }
        // ��������� ����� ����� ������� ���������
        $arOut[$i]=Af($sum);
        // ���� ���� ������ ��������� � ������� ���� - ����������� �������� ����.
        if (($i-$arLayerCount[$layer+1])==($j-1))
            $layer++;
    } 
   
    
  
    $arDelta=array();
    for($i=1; $i<=$totalCount; $i++)
        $arDelta[$i]=0;
    $error=0;
    $done=false;
    //��������� ������ ��� �������� ���� � ������ ����� ������ (�����, ������ �����?)
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
        // ��������� ����� ����������������� ���� ��� �������� �� �������������� � ����������
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
    // ��������� �� ��� �����
    for($layer=$layerCount; $layer>=1; $layer--)
    {
        // ���������� �� �������� ���� 
        for($i=$arFirstNumbers[$layer]; $i<=$arLastNumbers[$layer]; $i++)
        {
            // ��������� �� ����������� ����
            for($j=$arFirstNumbers[$layer-1]; $j<=$arLastNumbers[$layer-1]; $j++)
            {
                // ��������� ����� ���
                $arDeltaWeights[$n][$j][$i]=$etta*$arDelta[$i]*$arOut[$j];
                $arWeights[$n][$j][$i]=$arWeight[$j][$i]+$arDeltaWeights[$n][$j][$i];
                // ������� ������ ��� ������� ������� ������� �� ����, ������� �� "��������� �� ����������� ����"
                $arDelta[$j]+=$arOut[$j]*(1-$arOut[$j])*$arWeight[$j][$i]*$arDelta[$i];
            }
        }
    }
 }   
   if ($n>100); $error=0; 
}

printr($arWeights);


// ������� ������� ��������� - ������� ������������� � ����� = 1
function Af($s)
{
    return (1/(1+exp($s)));
}
// � �� �����������
function dAf($s)
{
    return $s*(1-$s);
}

// ������ ��������� ������ 
function forwardRun()
{
    
}


//printr($max);
//printr($min);
//printr($arTrainingSets);
//printr($arWeight);

// 