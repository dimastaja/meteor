<script src="/js/jquery.js"></script><script src="/js/jquery.flot.js"></script>
<? 


require $_SERVER["DOCUMENT_ROOT"]."/function.php";
  
    $query="SELECT D1 FROM `outer_belt`";
    
    	$res=$db->query($query);
        $i=0;
        while ($row =$res->fetch_assoc())
    	{
    	    
            $arData[$i][1]=$row['D1'];
            $arData[$i++][0]=$i;
            
        }
       // printr($arData);
        
        $dataSend["PLOT2"][] = array('label' => 'D1' , 'data' => $arData, 'color' => $arColor['D1']);
    	
       
       ?>
        <script> $(function() { var startData=<?=json_encode($dataSend["PLOT2"]);?>; MainPlot=$.plot($("#placeholder<?=$nubmer?>"), startData,{points: {show: true},lines: {show: true}}); }) </script>
        <div class='placeholder' id="placeholder<?=$nubmer?>" style=" width:11500px;height:500px;"></div> <br /><br />
      
    
