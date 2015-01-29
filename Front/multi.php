<?php 


$fac = $_GET['facility'];

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

//departamntos
$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = 10000 GROUP BY USR_DEPT;");

//personal
 $data_per = mysqli_query($datos, "SELECT USR_ID,  USR_NAME FROM USERS");

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
                             		<option value="0">ADMINISTRACION_Y_FINANZAS</option>
                             		<option value="1">CONTROL_Y_GESTION_VIVIENDA</option>
                             	</select>
                             	<select id="personal">
                          <? while ($fila1 = mysqli_fetch_row($data_per)){ ?>
                                   <option value="<? printf($fila1[0]) ?>"><? printf(str_replace(" ", "_", $fila1[1]))?></option>
                          <? } ?>
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
var name = document.querySelector("#personal").options[document.querySelectorAll("#selection")[0].selectedIndex].text;
var ind2 = document.querySelector("#personal").options[document.querySelectorAll("#selection")[0].selectedIndex].value;
var ind1 = $("#selection").val();
var mode  = 1;

if (name == 'General' ){
	mode == 1;
} else { 
   mode = 0;
}
// ind1 ve el departamento, ind2 ve la naturaleza, ind3 ve  el personal
updateChart(depto_eval, name, ind1, ind2, mode);


})

// create data.

function updateChart(depto, name, index_d, index_p, mode){


$.ajax({ type: "POST", 
	    url: "../backend/JSON.php?facility=" + fac, 
	    success: function(datab){

//set Jlinq and parse database
var database = JSON.parse(datab);
var newData_eval = jlinq.from(database.data).select();

// clean up the plot chart
$("#placeholder2").empty();

newData = eval('newData_eval[' + index_d + '].' + depto + "[" + mode + "]." + name + "");
var matriz =[];
console.info('newData_eval[' + index_d + '].' + depto + "[" + mode + "]." + name );
  for (i=0; i < newData.length ; i++){

   matriz[i] =  eval('newData_eval[' + index_d + '].' + depto + "[" + mode + "]." + name + "[" + i +"]");
    
  }

 matrix = matriz;
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
