<?php 

function getResult($paper){
        
        if($paper['EXT'] === "AB"){
            
            return "AB";
        }
        // check both absent
        if( $paper['INT']==="AB" ){
            if($paper['EXT']/$paper['TOTALMARKS']>=0.40){
                return "P";
            }
        }
        
        if( $paper['EXT']/$paper['ETOTAL'] >= 0.40 && $paper['INT'] / $paper['ITOTAL'] >= 0.40){
            return "P";
        }
        else{
            return "F";
        }
        
        if($paper['EXT']!="AB" && $paper['INT']!="AB"){
            if($paper['EXT']/$paper['TOTALMARKS']>=0.40){
                return "P";
            }
        }
        
        if ($paper['EXT']/$paper['ETOTAL']>=0.40 && $paper['INT']/$paper['ITOTAL']>=0.40 ){
            return "P";    
        }
        else {
            return "F";
        }
}
$paper=array();

$paper['EXT']=40;
$paper['INT']="AB";
$paper['ETOTAL']=80;
$paper['ITOTAL']=20;
$paper['TOTALMARKS']=$paper['ETOTAL']+$paper['ITOTAL'];

echo getResult($paper);

?>