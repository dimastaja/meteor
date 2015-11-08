<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// ��������� ��������� ����
// �����. ��� ������ ���������� ������ (� ��� �� ���� ��� ����, � ��� ��� ������)
class NeuralNetwork
{
      // ���������� �������� �� �����
      public $arLayerCount=array();
      public $arTrainingSets=array();
      public $arFirstNumbers=array();
      public $arLastNumbers=array();
      public $arWeights=array();
      public $arDeltaWeights=array();
      public $totalCount=0;
      public $layerCount=0;
      public $db=false;
      public $log;
      public $error;
      public function __construct($arLayerCount, $log)
      {
            $this->log=$log;
            //printr($arLayerCount);
            $this->db = new mysqli("localhost", "root", "123", "meteor");
            $this->arLayerCount=$arLayerCount;
            // �������� �����
            $this->totalCount=0;
            foreach($this->arLayerCount as $key=>$count)
            {
                $this->totalCount+=$count;
                // ������ ������� ������� � �������� ������ � ��������� �������� � ������ ����. ��� ������� ����� ������������
                $this->arFirstNumbers[$key]=$this->totalCount-$count+1;
                $this->arLastNumbers[$key]=$this->totalCount;
            }
            // ����� ���������� ����� (����� ����, ��� ��� ������ - �������)
            $this->layerCount=count($this->arLayerCount)-1;
      }
      public function setWeights()
      {
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
            for ($i=1; $i<=($this->arLastNumbers[($this->layerCount-1)]); $i++)
            {
                // ����� ����� - �.�. ����� � ���� �������, � ������� ����������� ������� � ���� ���� (����  1 - 6 1 - 7 , ���� ��� ���  1 � 2)
                $link=1;
                // ����� �������, � ������� ����������� ������� - ��� ��� �� ����� 6 � 7 (����� ����������� ����� ������� ������� � ����������� ����)
                $j=1;
                for($p=0; $p<$layer; $p++)
                {
                    $j+=$this->arLayerCount[$p];
                }
                // �������� ��� �������� ��� ���������, �� ����� �� ��� ���� ����
                $q=$j;
                // ����������� ��������� �������� �����, ����������� � ������ ������ ����, ���� ��� ������� ������������ ���� �� �������
                while($link<=$this->arLayerCount[$layer])
                {
                   // $rand=file('http://www.random.org/integers/?num=1&min=1&max=100&col=1&base=10&format=plain&rnd=new');
                    $this->arWeights['cur'][$i][$j++]=random(-4,4);
                    $link++;
                }
                // ���� ���� ������ ����� � ����� ���� - �� ����������� �������� layer
                if ($i==($q-1))
                    $layer++;
            }
          // printr($this->arWeights);
            // ��� ��������, ��� �� �������
      }
      function setTraingingSet ()
      {
            // ��������� ������� ������ � ����. ������ sql ������ � �������� ������ � ������.
            $query="SELECT D1_1 X1,D1_2 X2,D1_3 X3,D1_4 X4,D1_5 X5,D1_6 X6,D1_7 X7,D1_8 X8,TYPE FROM `training_set_2` limit 100";
            $res=$this->db->query($query);
            // ��� � ���� �������� � �������, ����� ��� ������������
            $max=0;
            $min=10;
            while ($row =$res->fetch_assoc())
            {
                for($i=1; $i<=8; $i++)
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
                      $arTrainingSets[$key][0][]=round((($data-$min)/($max-$min)),5);
                 // ���� ��� �������� 1 - "������" ���������, -1 - "��������"
                    else 
                    {
                        for ($co=1; $co<14; $co++)
                        {
                            $valO=0;
                            if ($co==$data)
                                $valO=1;
                            $arTrainingSets[$key][1][]=$valO;
                        }
                    }
                }
            }
            $this->arTrainingSets=$arTrainingSets;
            //printr($arTrainingSets);
           // die();
//            printr($arTrainingSets);
           /* unset($this->arTrainingSets);
            $this->arTrainingSets[4][0]=array(0.1,0.2, 0.3);
            $this->arTrainingSets[4][1]=array(1,0);
             $this->arTrainingSets[0][0]=array(0.3,0.8, 0.9);
            $this->arTrainingSets[0][1]=array(1,0);
            $this->arTrainingSets[3][0]=array(0.2,0.4, 0.9);
            $this->arTrainingSets[3][1]=array(1,0);
            $this->arTrainingSets[1][0]=array(0.9,0.7, 0.5);
            $this->arTrainingSets[1][1]=array(0,1);
            $this->arTrainingSets[2][0]=array(0.6,0.4, 0.2);
            $this->arTrainingSets[2][1]=array(0,1);
            $this->arTrainingSets[5][0]=array(0.8,0.6, 0.5);
            $this->arTrainingSets[5][1]=array(0,1);
            $this->arTrainingSets[6][0]=array(0.95,0.65, 0.2);
            $this->arTrainingSets[6][1]=array(0,1);
           */
      }
      /*
      $arTrainingSets[0][0]=array(0.3,0.2,0.1);
      $arTrainingSets[0][1]=array(0,1);
      $arTrainingSets[2][0]=array(0.9,0.4,0.1);
      $arTrainingSets[2][1]=array(0,1);
      $arTrainingSets[4][0]=array(0.8,0.7,0.5);
      $arTrainingSets[4][1]=array(0,1);*/
      /*
      unset($arWeights);
      $arWeights[0][1][3]=1;
      $arWeights[0][1][4]=2;
      $arWeights[0][2][3]=3;
      $arWeights[0][2][4]=1;
      $arWeights[0][3][5]=2;
      $arWeights[0][3][6]=3;
      $arWeights[0][4][5]=1;
      $arWeights[0][4][6]=2;
      printr($arWeights);
      */
      // ���
      //
      function forwardRun($arInput, $arWeight)
      {
          //  if ($this->log) printr($arWeight);
            $i=1;
            $arOut=array();
            //  � �������� �������� ���� ������ ����� �������� �������
            foreach($arInput as $inp)
            {
               $arOut[$i++]=$inp;
            }
          //$arOut[3]=0.7;
        //  if ($this->log) printr($arOut);
          $layer=0;
          // �������� �� ������� ����
          for($i=($this->arFirstNumbers[1]); $i<=$this->totalCount; $i++)
          {
            $j=$this->arFirstNumbers[$layer];
                  //printr('layer '.$layer);
                  //printr('j '.$j);
              $link=1;
              $sum=0;
              // ���������� �� ���� �������� ����������� ���� � ��������� �����
              while($link<=$this->arLayerCount[$layer])
              {
            //     if ($this->log) printr('j '.$j);
            //       if ($this->log)      printr('i '.$i);
                  $sum+=$arOut[$j]*$arWeight[$j][$i];
            //      if ($this->log) printr($arWeight[$j][$i]);
            //      if ($this->log) printr($arOut[$j]);
                  $link++;
                  $j++;
              }
              // ��������� ����� ����� ������� ���������
             // if ($this->log) printr('sum '.$sum);
              $arOut[$i]=Af($sum);
             // if ($this->log) printr('arout[i] '.$arOut[$i]);
                  //printr($this->arLastNumbers);
              // ���� ���� ������ ��������� � ������� ���� - ����������� �������� ����.
             // printr('i '.$i.' layer'.$layer);
              if ($i==$this->arLastNumbers[$layer+1])
                  $layer++;
                  //printr($arOut);
          }
            //printr($arOut);
            //die();
            return $arOut;
      }
      function teaching($pogreshnost, $etta)
      {
            $error=1111;
            $arError=array();
            $errPr=$error;
            $pogreshnost=0.01;
            $n=0;
            $etta=1;
            $error1=array();
            $error2=array();
            $arErrorTotal=array();
            while((($error/2)>0))
            {
                  foreach($this->arTrainingSets  as $trainNum => $trainingSet)
                  {
                    // die();
                    
                      $arOut=array();
                      $i=1;
                      $arWeight=$this->arWeights['cur'];
                      // ������ ������
                        $arOut=$this->forwardRun($trainingSet[0],$arWeight);
                      // �������� ������
                      $arDelta=array();
                      for($i=1; $i<=$this->totalCount; $i++)
                         $arDelta[$i]=0;
                        $errPr=$error;
                      $error=0;
                      $done=1;
                      //��������� ������ ��� �������� ���� � ������ ����� ������ (�����, ������ �����?)
                      $j=0;
                      if ($this->log)//&&$n>99900)
                      {
                        //printr('����� ���� '.($n+1));  
                        //printr('����');
                       // printr($trainingSet[0]);
                        //printr('�����');
                        $arOutMain=array($arOut[count($arOut)-1],$arOut[count($arOut)]);
                       // printr($arOut);
                       // printr($arOutMain);
                      //  prin
                        //printr('end out');
                      //  printr($trainingSet[1]);
                      }
                        //printr('��� ������ �����');
                        //printr($trainingSet[1]); 
                        for($i=$this->arFirstNumbers[$this->layerCount]; $i<=$this->totalCount; $i++)
                        {
                          
                            if ($this->log)
                            {
                            // printr($trainingSet[1]);
                            //printr($i);
                            }
                            
                            $curError=($trainingSet[1][$j++]-$arOut[$i]);
                            //printr('������ �� '.$j.' ������');
                            //printr($curError);
                            $arDelta[$i]=$arOut[$i]*(1-$arOut[$i])*$curError;
                            $error+=$curError*$curError;
                            $arOutMain=array($arOut[count($arOut)-1],$arOut[count($arOut)-2]);
                       // printr($arOutMain);
                          
                          
                          // ��������� ����� ����������������� ���� ��� �������� �� �������������� � ����������
                        }
                        $error_=round(abs($trainingSet[1][1]-$arOut[count($arOut)]),5);
                       // $error_=round(abs($trainingSet[1][0]-$arOut[count($arOut)-1]),5);
                        //echo(($trainingSet[1][0]+1)."\t".$error_.'<br/>');
                       // printr($n);
                       // printr($trainingSet[0]);
                        if ($trainingSet[1][0]==1)
                        {
                            $error1[]=array(count($arErrorTotal),$error_);
                        //    printr("error1 = ".$error_);
                        }
                        else
                        {
                            $error2[]=array(count($arErrorTotal),$error_);
                        //    printr("error2 = ".$error_);
                        }
                        $arErrorTotal[]=array(count($arErrorTotal),($error_*$error_)/2);
                        printr('������ ���� '.$trainingSet[1][0]);
                        printr('����  '.$arOut[count($arOut)]);
                        if (abs($trainingSet[1][0]-$arOut[count($arOut)])<=$pogreshnost)
                        {
                          
                        } else $done++;
                        $n++;
                    if ($this->log)
                    {
                        
                      //printr('�������������������� ������ ����������');
                      //printr(($errPr/2).'<BR/>');
                      //printr('�������������������� ������');
                      //printr(($error/2).'<BR/>');
                    }
                      /*if ($done)
                      {
                          break;
                      }*/
                      // ��������� �� ��� �����
                      // printr('������ �� �������');
                        //printr($arDelta);
                      for($layer=$this->layerCount; $layer>=1; $layer--)
                      {
                          // ���������� �� �������� ����
                          for($i=$this->arFirstNumbers[$layer]; $i<=$this->arLastNumbers[$layer]; $i++)
                          {
                              // ��������� �� ����������� ����
                              for($j=$this->arFirstNumbers[$layer-1]; $j<=$this->arLastNumbers[$layer-1]; $j++)
                              {
                                  // ��������� ����� ���
                                  $this->arDeltaWeights['cur'][$j][$i]=$etta*$arDelta[$i]*$arOut[$j];
                                  if ($n>1)
                                         $this->arDeltaWeights['cur'][$j][$i]+=0.9*$this->arDeltaWeights['pr'][$j][$i];
                                  $this->arWeights['cur'][$j][$i]=$arWeight[$j][$i]-$this->arDeltaWeights['cur'][$j][$i];
                                  // ������� ������ ��� ������� ������� ������� �� ����, ������� �� "��������� �� ����������� ����"
                                  $arDelta[$j]+=$arOut[$j]*(1-$arOut[$j])*$arWeight[$j][$i]*$arDelta[$i];
                              }
                          }
                      }
                      $this->arDeltaWeights['pr']=$this->arDeltaWeights['cur'];
                        //printr('������ �����');
                        //printr($arDeltaWeights);
                        //printr($arWeights);
                        //printr('��� ������');
                        //printr($arDelta);
                         $arError[$trainNum]=$error;
                  }  
                   //printr('ERROR');
                    // printr($error);
                   
                   // printr($arError);
                    $done=true;
                    foreach($arError as $error)
                    {
                        if ($error>$pogreshnost)
                            $done=false;
                    }
                    if (($done==1)||($n>20000))
                    {
                       // printr("arError");
                       printr($error1);
                       printr($error2);
                       file_put_contents('mistake/error1.txt',json_encode($error1));
                       file_put_contents('mistake/error2.txt',json_encode($error2));
                       file_put_contents('mistake/errorTotal.txt',json_encode($arErrorTotal));
                       // printr($arError);
                       // printr('����� ���� '.($n+1));  
                        //printr('����');
                       // printr($trainingSet[0]);
                        //printr('�����');
                        $arOutMain=array($arOut[count($arOut)-1],$arOut[count($arOut)]);
                       // printr($arOut);
                        //printr('end out');
                       // printr($trainingSet[1]);
                        $startWeight=$this->arWeights['cur'];
                        $endWieght=$this->arWeights['cur'];
                        $name='neural';
                        foreach($this->arLayerCount as $lc)
                        {
                            $name.=$lc;
                        }
                        if ($done)
                        {
                            $file=fopen($_SERVER['DOCUMENT_ROOT']."/predict/weights/".$name."_startweight.txt",'w');
                            fwrite($file,serialize($startWeight));
                            fclose($file);
                            $file=fopen($_SERVER['DOCUMENT_ROOT']."/predict/weights/".$name."_endweight.txt",'w');
                            fwrite($file,serialize($endWieght));
                            fclose($file); 
                        }
                        
                        break;
                    }
                       
                     if ($n>20000) break;
                     
                    // printr('ERROR');
                    // printr($error);

            }
              $this->error=$error;
           //   printr($n);
              asort($arError);
           //   printr($arError);
      }
    
}



function Af($s)
{
    return (1/(1+exp(-$s)));
}
// � �� �����������
function dAf($s)
{
    return $s*(1-$s);
}
function printrr($arr)
{
      echo '<pre>';
      print_r($arr);
      echo '</pre>';
}
function random($min, $max)
{
    if ($min>=$max) return false;
    $rand=-99999999;
    while ($rand/1000000<$min)
    {
        if ($min<0)
            $absmin=0;
        else
            $absmin=($min*1000000);
      $rand=rand($absmin, $max*1000000);  
     // printr($rand); printr($min);
    }
        
    $znak=rand(0,1000);
    if (($znak>500)&&($min<0)&&($rand/1000000<abs($min)))
        $rand=$rand*(-1);
    return $rand/1000000;
    
    
}
