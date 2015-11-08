<?
/*$data= file_get_contents('7481.xml');
$xml = new DOMDocument;
$xml->loadXML($data);
printr($xml);

$book = $xml->documentElement;
$chapter = $book->getElementsByTagName('ROW')->item(0); // Тут выбираем например 1 элемент
$chapter->parentNode->removeChild($chapter); //Получаем родительскую ноду и удаляем нужную нам
echo $xml->saveXML();

die();*/

$fileName='spec_title';
$xml=simplexml_load_file($fileName.'.xml');
printr($xml);

/*
$x = new SimpleXMLElement('<root name="toplevel"></root>');
$f1 = new SimpleXMLElement('<child pos="1">alpha</child>');
$f2 = new SimpleXMLElement('<child pos="2">beta</child>');
$f3 = new SimpleXMLElement('<child pos="3">gamma</child>'); 

$sxe = new SimpleXMLElement($xmlstr);
$sxe->addAttribute('type', 'documentary');

$movie = $sxe->addChild('movie');
$movie->addChild('title', 'PHP2: Истории парсера');
$movie->addChild('plot', 'Все о людях, создававших его.');

$characters = $movie->addChild('characters');
$character  = $characters->addChild('character');
$character->addChild('name', 'Mr. Parser');
$character->addChild('actor', 'John Doe');

$rating = $movie->addChild('rating', '5');
$rating->addAttribute('type', 'stars');
 
echo $sxe->asXML();
*/




$speсMtr=$xml->addChild("spec_mtr");
for ($i=1; $i<10; $i++)
{
    $specNum=$speсMtr->addChild("spec_mtr_num");
    $specMtrNumSumm=$specNum->addChild("mtr_name","adfadf");
    $specMtrNumSumm=$specNum->addChild("mtr_measure","asdf");
    $specMtrNumSumm=$specNum->addChild("mtr_all","asdf");
    $specMtrNumSumm=$specNum->addChild("mtr_price","23423");
    $specMtrNumSumm=$specNum->addChild("mtr_sum_wo_nds","asdf23");
    $specMtrNumSumm=$specNum->addChild("mtr_nds","asdf234");
    $specMtrNumSumm=$specNum->addChild("mtr_sum_nds","fret435");
    $specMtrNumSumm=$specNum->addChild("mtr_sum_w_nds","sd453534");
    $specMtrNumSumm=$specNum->addChild("mtr_date","erwt");
    $specMtrNumSumm=$specNum->addChild("mtr_mtrcod","павыпв");
    $specMtrNumSumm=$specNum->addChild("mtr_codespec","$i");
}

//$speсMtr->addChild($specNum);


printr($xml->asXML());
file_put_contents($fileName.'new.xml', $xml->asXML());
//printr($xml->ROW[4]);
die();
$rowIndex=0;
//$xml->removeChild($xml->ROW);
$arForDel=array();
foreach($xml->ROW as $row)
{
    //printr($rowIndex);
    $linesIndex=-1;
    foreach($row->LINES as  $lines) 
    {
        //printr($lines);
        $linesIndex++;
        
        $linesRowIndex=-1;
        foreach($lines->LINES_ROW as  $linesRow)
        {
            $linesRowIndex++;
            
            $linesAddIndex=-1;
            foreach($linesRow->LINES_ADD as  $linesAddRow)
            {
                $linesAddIndex++;
                //unset($xml->ROW[$rowIndex]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[3]);
                //unset($xml->ROW[$rowIndex]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[2]);
                //unset($xml->ROW[$rowIndex]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[1]);
               // unset($xml->ROW[$rowIndex++]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[3]);
                //$xml->ROW[$rowIndex]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[0]->NOMENCLATURE='Test';
                //unset($xml->ROW[$rowIndex]->LINES->LINES_ROW->LINES_ADD->LINES_ADD_ROW[0]);
                //printr($linesAddIndex);
                //printr($xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[0]->NOMENCLATURE);
                
                //printr($xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[0]->NOMENCLATURE);
                $first=true;
                $total=0;
                $lineAddRowIndex=0;
                $firstNoda=0;
               // printr(count($linesAddRow));
                $offset=0;
                $NDS=0;
                    $TOTAL_WITH_NDS=0;
                    $PRICE=0;
                foreach($linesAddRow as   $simpleNode)
                {
                   
                  /*  $simpleNode->NOMENCLATURE=iconv('utf-8','cp1251',$simpleNode->NOMENCLATURE);
                    $simpleNode->NDS_NAME=iconv('utf-8','cp1251',$simpleNode->NDS_NAME);
                    $simpleNode->CURRENCY=iconv('utf-8','cp1251',$simpleNode->CURRENCY);
                    */
                    $arTotal=explode('.',$simpleNode->TOTAL);
                    $drob=0;
                    if (@$arTotal[1])
                        $drob='0.'.$arTotal[1];
                   
                    $curTotal= (double)$simpleNode->TOTAL;
                    //$total=$arTotal[0]+$total+$drob;
                    $total=$total+$curTotal;
                   // printr($total);
                    $curNDS= (double)$simpleNode->NDS;
                    $curTOTAL_WITH_NDS= (double)$simpleNode->TOTAL_WITH_NDS;
                    $curPRICE= (double)$simpleNode->PRICE;
                    $NDS=$NDS+$curNDS;
                    $TOTAL_WITH_NDS=$TOTAL_WITH_NDS+$curTOTAL_WITH_NDS;
                    $PRICE=$PRICE+$curPRICE;
                    
                    $nomID=$simpleNode->NOMENCLATURE_ID;
                    if (!$first)
                    {
                       //printr($nomID);
                    //   printr($nomIDPr);
                       if ((int)$nomID==(int)$nomIDPr)
                       {
                       // printr($total);
                       //     printr(($xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]));
                            ///unset($xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$lineAddRowIndex]);
                          // unset($simpleNode);
                           $arForDel[]=array($rowIndex,$linesIndex,$linesRowIndex,$linesAddIndex,$lineAddRowIndex-$offset++);
                           
                           
                           $xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]->TOTAL= $total;
                           $xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]->NDS= $NDS;
                           $xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]->TOTAL_WITH_NDS= $TOTAL_WITH_NDS;
                           $xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]->PRICE= $PRICE;
                         //  printr(($xml->ROW[$rowIndex]->LINES[$linesIndex]->LINES_ROW[$linesRowIndex]->LINES_ADD[$linesAddIndex]->LINES_ADD_ROW[$firstNoda]));
                           
                       }
                       else
                       {
                            $firstNoda=$lineAddRowIndex;
                            $total=$curTotal;
                            $NDS=$curNDS;
                    $TOTAL_WITH_NDS=$curTOTAL_WITH_NDS;
                    $PRICE=$curPRICE;
                            
                       }
                       
                    }
                    
                    
                    
                    
                    if ($first)
                    {
                        $nomIDPr=$simpleNode->NOMENCLATURE_ID;
                        $first=false;
                    }
                    $nomIDPr=$nomID;    
                    // unset($linesAddRow[0]);
                    
                       
                   // printr($simpleNode);
                   // printr($lineAddRowIndex++);
                   $lineAddRowIndex++;
                }
            }
        }
    }
    $rowIndex++;
    //break;
}
 
 foreach($arForDel as $del)
{ //printr($del);
//printr($xml->ROW[$del[0]]->LINES[$del[1]]->LINES_ROW[$del[2]]->LINES_ADD[$del[3]]->LINES_ADD_ROW[$del[4]]);
    unset($xml->ROW[$del[0]]->LINES[$del[1]]->LINES_ROW[$del[2]]->LINES_ADD[$del[3]]->LINES_ADD_ROW[$del[4]]);
 }
 
 file_put_contents($fileName.'new.xml', $xml->asXML());
//printr($xml);

//echo $xml->asXML(); 
 
 
function printr($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}
 
 
 
 
 
?>