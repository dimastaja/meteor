Вчера прошел онлайн регистрацию на рейс. По приезде в аэропорт сдал багаж и начал ожидать посадку, которую задержали на 20 минут. При посадке мне было сообщено, что мое место изменено - с 24F (это место у окно в конце салона) на 9D (место в проходе в начале салона без возможности откинуть спинку назад). Причину не озвучили, сказали, что все вопросы  к регистрации. Предложения вызвать регистратора не было. Посадка заканчивалась,мне пришлось проследовать на борт. Уже на месте выяснилось, что пересадили еще одного пассажира - с места 24E на 9E - т.е. рядом со мной. На наших местах сидело двое молодых людей. Они избранные, на других наплевать? Почему ваши сотрудники по просьбе кого-либо может распоряжаться другими клиентами? Факт смены места должен был быть отмечен в журнале главной бортпроводницей. Если необходимо приложу также скан посадочного, хоть там и просто ручкой отмечено новое место.Просьба сообщить причину такого наплевательского отношения и неуважения к клиенту, а также смысл прохождения регистрации онлайн, если ее могут запросто переиначить. 
<?
//require $_SERVER["DOCUMENT_ROOT"]."/function.php";
// установки нейронной сети
// Итого. Для начала количество входов (у нас их пока что пять, а там как пойдет)
class NeuralNetwork
{
      // количество нейронов по слоям
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
      public $done=1;
      public function __construct($arLayerCount, $log)
      {
            $this->log=$log;
            //printr($arLayerCount);
            $this->db = new mysqli("localhost", "root", "123", "meteor");
            $this->arLayerCount=$arLayerCount;
            // нейронов всего
            $this->totalCount=0;
            foreach($this->arLayerCount as $key=>$count)
            {
                $this->totalCount+=$count;
                // заодно сделаем массивы с номерами первых и последних нейронов в каждом слое. Это полезно будет впоследствии
                $this->arFirstNumbers[$key]=$this->totalCount-$count+1;
                $this->arLastNumbers[$key]=$this->totalCount;
            }
            // общее количество слоев (минус один, так как первый - нулевой)
            $this->layerCount=count($this->arLayerCount)-1;
      }
      public function setWeights()
      {
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
            for ($i=1; $i<=($this->arLastNumbers[($this->layerCount-1)]); $i++)
            {
                // номер связи - т.е. номер в слое нейрона, с которым связывается текущий в след слое (типа  1 - 6 1 - 7 , линк как раз  1 и 2)
                $link=1;
                // номер нейрона, с которым связывается текущий - как раз те самые 6 и 7 (здесь вычисляется номер первого нейрона в связываемом слое)
                $j=1;
                for($p=0; $p<$layer; $p++)
                {
                    $j+=$this->arLayerCount[$p];
                }
                // копируем это значение для выяснения, не пошел ли уже след слой
                $q=$j;
                // присваиваем рандомные значения весам, увеличиваем в каждый проход линк, пока все нейроны связываемого слоя не пройдем
                while($link<=$this->arLayerCount[$layer])
                {
                   // $rand=file('http://www.random.org/integers/?num=1&min=1&max=100&col=1&base=10&format=plain&rnd=new');
                    $this->arWeights['cur'][$i][$j++]=0.5;//random(-0.5,0.5);
                    $link++;
                }
                // если след нейрон будет в новом слое - то увеличиваем значение layer
                if ($i==($q-1))
                    $layer++;
            }
          // printr($this->arWeights);
            // все работает, как ни странно
      }
     
      function forwardRun($arInput, $arWeight)
      {
          //  if ($this->log)
          printr($arInput); 
         // printr($arWeight);
            $i=1;
            $arOut=array();
            //  у входного нулевого слоя выходы равны входному вектору
            foreach($arInput as $inp)
            {
               $arOut[$i++]=$inp;
            }
           // printr($arOut);
          //  die();
          //$arOut[3]=0.7;
        //  if ($this->log) printr($arOut);
          $layer=0;
          // начинаем со второго слоя
          for($i=($this->arFirstNumbers[1]); $i<=$this->totalCount; $i++)
          {
            $j=$this->arFirstNumbers[$layer];
                  //printr('layer '.$layer);
                  //printr('j '.$j);
              $link=1;
              $sum=0;
              // проходимся по всем нейронам предыдущего слоя и вычисляем сумму
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
              // прогоняем сумму через фукнцию активации
             // if ($this->log) printr('sum '.$sum);
              $arOut[$i]=Af($sum);
             // if ($this->log) printr('arout[i] '.$arOut[$i]);
                  //printr($this->arLastNumbers);
              // если этот нейрон последний в текущем слое - увеличиваем значение слоя.
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
            $pogreshnost=0.02;
            $n=0;
            $etta=0.01;
            $error1=array();
            $error11=array();
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
                      // проход вперед
                        $arOut=$this->forwardRun($trainingSet[0],$arWeight);
                        printr($arOut);
                      // обнуляем дельту
                      $arDelta=array();
                      for($i=1; $i<=$this->totalCount; $i++)
                         $arDelta[$i]=0;
                        $errPr=$error;
                      $error=0;
                      $done=0;
                      //вычисляем дельты для внешнего слоя и заодно общую ошибку (может, хватит учить?)
                      $j=0;
                      if ($this->log)//&&$n>99900)
                      {
                        //printr('номер шага '.($n+1));  
                        //printr('Вход');
                       // printr($trainingSet[0]);
                        //printr('Выход');
                        $arOutMain=array($arOut[count($arOut)-1],$arOut[count($arOut)]);
                       // printr($arOut);
                       // printr($arOutMain);
                      //  prin
                        //printr('end out');
                      //  printr($trainingSet[1]);
                      }
                        //printr('Что должно выйти');
                        //printr($trainingSet[1]); 
                        for($i=$this->arFirstNumbers[$this->layerCount]; $i<=$this->totalCount; $i++)
                        {
                          
                            if ($this->log)
                            {
                            // printr($trainingSet[1]);
                            //printr($i);
                            }
                            
                            $curError=($trainingSet[1][$j++]-$arOut[$i]);
                            //printr('ошибка по '.$j.' выходу');
                            //printr($curError);
                            $arDelta[$i]=$arOut[$i]*(1-$arOut[$i])*$curError;
                            $error+=$curError*$curError;
                            $arOutMain=array($arOut[count($arOut)-1],$arOut[count($arOut)-2]);
                       // printr($arOutMain);
                          
                          
                          // вычисляем сразу скорректированные веса для нейронов от предпоследнего к последнему
                        }
                        //$error_=round(abs(abs($trainingSet[1][0]-$arOut[count($arOut)-1])+abs($trainingSet[1][1]-$arOut[count($arOut)])),5);
                        $error_=round(abs($trainingSet[1][0]-$arOut[count($arOut)]),5);
                        //echo(($trainingSet[1][0]+1)."\t".$error_.'<br/>');
                       // printr($n);
                       // printr($trainingSet[0]);
                       
                            $error1[]=array($trainingSet[0],$arOut[count($arOut)],$error_);
                            $error11[]=array(count($arErrorTotal),$error_);
                            
                        $arErrorTotal[]=array(count($arErrorTotal),($error_*$error_)/2);
                        if (($error/2)<=$pogreshnost)
                        {
                          
                        }else $done++;
                        $n++;
                    if ($this->log)
                    {
                        
                      //printr('среднеквадратическая ошибка предыдущая');
                      //printr(($errPr/2).'<BR/>');
                      //printr('среднеквадратическая ошибка');
                      //printr(($error/2).'<BR/>');
                    }
                      /*if ($done)
                      {
                          break;
                      }*/
                      // пройдемся по все слоям
                      // printr('Дельты по выходам');
                        //printr($arDelta);
                      for($layer=$this->layerCount; $layer>=1; $layer--)
                      {
                          // проходимся по текущему слою
                          for($i=$this->arFirstNumbers[$layer]; $i<=$this->arLastNumbers[$layer]; $i++)
                          {
                              // пройдемся по предыдущему слою
                              for($j=$this->arFirstNumbers[$layer-1]; $j<=$this->arLastNumbers[$layer-1]; $j++)
                              {
                                  // вычисляем новый вес
                                  $this->arDeltaWeights['cur'][$j][$i]=$etta*$arDelta[$i]*$arOut[$j];
                                  if ($n>1)
                                         $this->arDeltaWeights['cur'][$j][$i]+=0.9*$this->arDeltaWeights['pr'][$j][$i];
                                  $this->arWeights['cur'][$j][$i]=$arWeight[$j][$i]-$this->arDeltaWeights['cur'][$j][$i];
                                  // считаем дельту для нейрона каждого нейрона из слоя, который из "пройдемся по предыдущему слою"
                                  $arDelta[$j]+=$arOut[$j]*(1-$arOut[$j])*$arWeight[$j][$i]*$arDelta[$i];
                              }
                          }
                      }
                      $this->arDeltaWeights['pr']=$this->arDeltaWeights['cur'];
                        //printr('Дельты весов');
                        //printr($arDeltaWeights);
                        //printr($arWeights);
                        //printr('Все дельты');
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
                    if ($done||($n>10))
                    {
                        $this->done=$done;
                       // printr("arError");
                       //printr($error1);
                      
                       file_put_contents('mistake/error1.txt',json_encode($error11));
                       
                       file_put_contents('mistake/errorTotal.txt',json_encode($arErrorTotal));
                       // printr($arError);
                       // printr('номер шага '.($n+1));  
                        //printr('Вход');
                       // printr($trainingSet[0]);
                        //printr('Выход');
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
                            $file=fopen($_SERVER['DOCUMENT_ROOT']."/xor/weights/".$name."_startweight.txt",'w');
                            fwrite($file,serialize($startWeight));
                            fclose($file);
                            $file=fopen($_SERVER['DOCUMENT_ROOT']."/xor/weights/".$name."_endweight.txt",'w');
                            fwrite($file,serialize($endWieght));
                            fclose($file); 
                        }
                        
                        break;
                    }
                       
                     if ($n>10) break;
                     
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
// и ее производная
function dAf($s)
{
    return $s*(1-$s);
}

/*function Af($s)
{
    return (exp(2*$s)-1)/(exp(2*$s)+1);//(1/(1+exp($s)));
}
// и ее производная
function dAf($s)
{
    $chx=(exp($s)-exp(-$s))/2;
    return $chx*$chx//$s*(1-$s);
}*/
function printr($arr)
{
      echo '<pre>';
      print_r($arr);
      echo '</pre>';
}
// предполагается число с одной цифрой после запятой (если целое - ноль добавлять обязательно))

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
