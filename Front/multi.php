<?php 


$fac = $_GET['facility'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

//departamntos
$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = 10000 GROUP BY USR_DEPT;");

//personal
 $data_per = mysqli_query($datos, "SELECT DISTINCT B.USR_NAME , B.USR_DEPT FROM USERS B RIGHT JOIN SUBTASKS A ON(B.STSK_CHARGE_USR = A.USR_ID) WHERE STSK_FAC_CODE = 10000 ORDER BY USR_DEPT");

$depts = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A  JOIN USERS B  ON(A.STSK_CHARGE_USR = B.USR_ID) WHERE STSK_FAC_CODE = 10000 GROUP BY USR_DEPT")

$parray = [];

$i = 0;

while($extra = mysqli_fetch_row($depto_eval)){
    $handup = mysqli_query($datos, "SELECT USR_NAME FROM USERS WHERE USR_DEPT = " . $extra[0] );
        while( $sub = mysqli_fetch_row($handup)){
               $parray[$i][0] = $handup[$i];
               $parray[$i][1] = $extra[0];
               $i = $i + 1;
        }
}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	*{ border:0;
		margin: 0;
		padding:0;
	}

	body, html{
		width:100%;
		height: 100%;
		font-family: arial, helvetica;
		font-style: italic;
	}


	</style>
</head>
<body>
                         <div class="chart inline-legend grid" style="width: 100%; height: 100%">
                                <div id="placeholder2" style="height: 350px; width:350px;"></div>
                             </div>
                             <div>
                             	<select id="selection">
                                <option value="0">GENERAL</option>
     <?  $i = 1;
     while($fila1 = mysqli_fetch_row($query_count_departament)) 
        { ?>
                             		<option value="<? printf($i) ?>"><? printf(str_replace(" ", "_", $fila1[0]))?></option>

                                    <? $i = $i + 1; } ?>
                             	</select>
                             	<select id="personal">
                          <?  
                          $z= 0;
                          $ancient = "";
                          for($y=0; $y < count($parray); $y++){ 

                            ?>

                            <option value="<? printf($z) ?>"><? printf(str_replace(" ", "_", $parray[$y][0]))?></option>

                            <?
                                  if($parray[$y][1] != $parray[$y-1][1]){  
                                 
                                     $z = 0;  } else {
                                        $z = $z+1;
                                     }                                          
                                 }

                           ?>
                             	</select>
                             </div>
</body>
</html>
<script type="text/javascript" src="../scripts/jquery-1.9.1.min.js"></script>
<script src="../scripts/flot/jquery.flot.js" type="text/javascript"></script>
<script src="../scripts/flot/jquery.flot.pie.js" type="text/javascript"></script>
<script src="../scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
<script src="../scripts/jlinq.js" type="text/javascript"></script>
<script src="../scripts/jlinq.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
	
var datas;
var perplot;
var fac = 10000;
var matrix;
$(document).on('ready', function(){

	var data = [{ label: "progreso",  data: 43, color: "#0000FF"},{ label: "estamina",  data: 40, color: "#FF0000"},{ label: "efectos especiales",  data: 17, color: "#00FF00"}];
perplot = $.plot($("#placeholder2") , data , {
           series: {
            pie: {
                innerRadius: 0.5,
                show: true
            }
         },
         legend: {
            show: false         
        },
        grid: {
        hoverable: true,
        clickable: true
    }
});

})



$("#selection, #personal").on("change" , function (){

var depto_eval = document.querySelector("#selection").options[document.querySelector("#selection").selectedIndex].text;
var name = document.querySelector("#personal").options[document.querySelectorAll("#personal")[0].selectedIndex].text;
var ind2 = document.querySelector("#personal").options[document.querySelectorAll("#personal")[0].selectedIndex].value;
var ind1 = $("#selection").val() - 1;
var mode = 0;

// ind1 ve el departamento, ind2 ve la naturaleza, ind3 ve  el personal
updateChart(depto_eval, name, ind1, ind2, mode);


})

// create data.

function updateChart(depto, name, index_d, index_p, mode){

console.info(depto + " / " + name + " / " + index_d + " / "+ index_p + " / " + mode);

$.ajax({ type: "POST", 
	    url: "../backend/JSON.php?facility=" + fac, 
	    success: function(datab){

//set Jlinq and parse database
var database = JSON.parse(datab);
var newData_eval = jlinq.from(database.data).select();


//make contador
var conta = eval('newData_eval[' + index_d + '].' + depto );
var per_conta = eval('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name );

console.log('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name);

console.info("valor de per_conta : " + per_conta.length);

// clean up the plot chart
$("#placeholder2").html('');

var matriz =new Array();

   Mtx_data = eval('newData_eval[' + index_d + '].' + depto + "[" + mode + "]." + name );

  for (i=0; i < per_conta.length ; i++){
     var val1 = eval('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name + "[" + i + "].label" );
     var val2 = eval('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name + "[" + i + "].data" );
     var val3 = eval('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name + "[" + i + "].color" );

     console.info(val1 + "/" + val2 + "/" + val3);
     matriz[i] = { label : val1 , data : parseInt(val2) , color:  val3 }
     console.info(matriz[i]);
  } 

//recreate
$.plot($("#placeholder2"), matriz, {
           series: {
            pie: {
                innerRadius: 0.5,
                show: true
            }
         },
         legend: {
            show: false         
        },
        grid: {
            
        hoverable: true,
        clickable: true
    }
});

	    }
	})

}


</script>



