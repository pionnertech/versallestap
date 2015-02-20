<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'sadmin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
                                                                
$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtFacility']);
$Query_task = mysqli_query($datos, "SELECT A.ISS_SUBJECT, D.CTZ_NAMES,  C.USR_NAME, B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.ISS_FINISH_DATE, 1, 10) , C.USR_SURNAME, D.CTZ_SURNAME1, D.CTZ_SURNAME2 FROM ISSUES A INNER JOIN EST B ON(B.EST_CODE = A.ISS_STATE) INNER JOIN USERS C ON(C.USR_ID = A.ISS_CHARGE_USR)  INNER JOIN CITIZENS D ON(D.CTZ_RUT = A.ISS_CTZ) WHERE ISS_FAC_CODE = " . $_SESSION['TxtFacility'] . ";");
$qcd = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");

//personal
$data_per = mysqli_query($datos, "SELECT DISTINCT B.USR_NAME , B.USR_DEPT FROM USERS B RIGHT JOIN SUBTASKS A ON(B.STSK_CHARGE_USR = A.USR_ID) WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " ORDER BY USR_DEPT");

$depts = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B  ON(A.STSK_CHARGE_USR = B.USR_ID) WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT");

$parray = array();
$darray = array();
$i = 0;

while($extra = mysqli_fetch_row($depts)){
    $handup = mysqli_query($datos, "SELECT USR_NAME FROM USERS WHERE USR_DEPT = '" . $extra[0] . "'" );
        while( $sub = mysqli_fetch_row($handup)){
               $parray[$i] = $sub[0];
               $darray[$i] = $extra[0];
               $i = $i + 1;
        }
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eque-e</title>
        <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="../css/theme.css" rel="stylesheet">
        <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
            rel='stylesheet'>
            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />

            <style type="text/css">

                    .linerchart{
            width:33.33%;
            display: inline-block;
            vertical-align: top;
        }
        #data-contents{
            display: inline-block;
            vertical-align: top;
            width: auto;

        }

        </style>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html">Edmin </a>
                    <div class="nav-collapse collapse navbar-inverse-collapse">
                        <ul class="nav nav-icons">
                            <li class="active"><a href="#"><i class="icon-envelope"></i></a></li>
                            <li><a href="#"><i class="icon-eye-open"></i></a></li>
                            <li><a href="#"><i class="icon-bar-chart"></i></a></li>
                        </ul>
                        <form class="navbar-search pull-left input-append" action="#">
                        <input type="text" class="span3">
                        <button class="btn" type="button">
                            <i class="icon-search"></i>
                        </button>
                        </form>
                        <ul class="nav pull-right">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Item No. 1</a></li>
                                    <li><a href="#">Don't Click</a></li>
                                    <li class="divider"></li>
                                    <li class="nav-header">Example Header</li>
                                    <li><a href="#">A Separated link</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Support </a></li>
                            <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Your Profile</a></li>
                                    <li><a href="#">Edit Profile</a></li>
                                    <li><a href="#">Account Settings</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <!-- /.nav-collapse -->
                </div>
            </div>
            <!-- /navbar-inner -->
        </div>
        <!-- /navbar -->
        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <div class="span3">
                        <div class="sidebar">
                            <ul class="widget widget-menu unstyled">
                                <li class="active"><a href="index.html"><i class="menu-icon icon-dashboard"></i>Dashboard
                                </a></li>
                                <li><a href="activity.html"><i class="menu-icon icon-bullhorn"></i>News Feed </a>
                                </li>
                                <li><a href="message.html"><i class="menu-icon icon-inbox"></i>Inbox <b class="label green pull-right">
                                    11</b> </a></li>
                                <li><a href="task.html"><i class="menu-icon icon-tasks"></i>Tasks <b class="label orange pull-right">
                                    19</b> </a></li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                        <div class="content">
                           
                        <div class="module">
                                <div class="module-head">
                                    <h3>
                                       Gráfico General</h3>
                                </div>
                                <div class="module-body">
                                    <div class="chart inline-legend grid" style="width: 100%;">
                                        <div id="placeholder2" style="height: 250px"></div>
                                    </div>
<?
$i = 0;
$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");
while($f1 = mysqli_fetch_row($query_count_departament)){
?>
                                   <div class="chart inline-legend grid linerchart" align="center" >
                                        <div id="chart<? printf($i)?>" class="graph" style="height: 200px"></div>
                                        <strong><? printf($f1[0]) ?></strong>
                                    </div>
 <? 
    $i = $i + 1;

 } ?>
                                </div>
                            </div>

                        </div>
                        <!--/.content-->
                    </div>
                    <!--/.span9-->

                    <div class="span9" style="float: right;">
                        <div class="content">
                            <div class="module">
                                <div class="module-head">
                                    <h3>Gráfico Dinamico</h3>
                                </div>
                                <div class="module-body">
                                    <div  style="width: 350px; height: 350px; display: inline-block;">
                                         <div id="dynamics" style="height: 350px; width:350px;"></div>
                                    </div>
                                    <div id="data-contents">
                                    <p><i class="fa fa-building"></i>Departamento</p>
                                        <select id="selection">
                                        <?  $i = 1;
                                               while($fil22 = mysqli_fetch_row($qcd)) {
                                        ?>
                                            <option value="<? printf($i) ?>"><? printf(str_replace(" ", "_", $fil22[0]))?></option>

                                        <? $i = $i + 1; } ?>

                                        </select>
                                        <select id="personal">
                                        <?  
                                            $z = 0;
                       
                                            for($y=0; $y < count($parray); $y++){ 

                                                    if(($y-1) < 0){

                                                        $z = 0;

                                                    } else {

                                                        if($darray[$y] != $darray[$y-1] || count($darray) == $y){  
                                 
                                 ?>
                                    <option class="<? printf(str_replace(" ", "_", $darray[$y-1])) ?>" value="<? printf($z+1) ?>">General</option>
                                 <?
                                                             $z = 0;  

                                                        } else {

                                                            $z = $z+1;
                                                        }                                          
                                                    }
                                        ?>
                                        <option class="<? printf(str_replace(" ", "_", $darray[$y])) ?>" value="<? printf($z) ?>"><? printf(str_replace(" ", "_", $parray[$y]))?></option>
                                       <?
                                            }

                                        ?>
                                        
                                        </select>
                                                                          <div class="wrap-progress" >
                                         <ul class="widget widget-usage unstyled progressDisplay" id="Audi-Display">
                                            <li>
                                                <p>
                                                    <strong>Audiencias Pendientes</strong><span class="pull-right small muted"></span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-warning" style="width:;"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>
                                                    <strong>Audiencias En Curso</strong><span class="pull-right small muted"></span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar" style="width:;"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>
                                                    <strong>Audiencias Por vencer</strong><span class="pull-right small muted"></span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-warning" style="width:;"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>
                                                    <strong>Audiencias Atrasadas</strong><span class="pull-right small muted"></span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-danger" style="width:;"></div>
                                                </div>
                                            </li>
                                            <li>
                                                <p>
                                                    <strong>Audiencias Finalizadas</strong><span class="pull-right small muted"></span>
                                                </p>
                                                <div class="progress tight">
                                                    <div class="bar bar-success" style="width:;"></div>
                                                </div>
                                            </li>
                                         </ul>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
            <!--/.container-->
        </div>
        <!--/.wrapper-->
        <div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2015 Eque-e </b>All rights reserved.
            </div>
        </div>
        <script src="http://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script type="text/javascript">

              function heighter(obj){
                    obj.children('td').css({ height : '130px'});
                         }
        </script>

        <script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.pie.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="../scripts/jlinq.js" type="text/javascript"></script>
        <script src="../scripts/jlinq.jquery.js" type="text/javascript"></script>
        <script type="text/javascript">

var fac = <? printf($_SESSION['TxtFacility']) ?>;
var datas;
var perplot;
var matrix;

$(document).on('ready', function(){

updateChart();

array_set = [
<?

$pass = mysqli_query($datos, "SELECT B.EST_DESCRIPT, COUNT( STSK_ID ) , B.EST_COLOR FROM SUBTASKS A INNER JOIN EST B ON ( A.STSK_STATE = B.EST_CODE )  WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY EST_DESCRIPT");

while ( $fila2 = mysqli_fetch_row($pass)) {

?>
{ label: "<? printf(  $fila2[0] ) ?>",  data: <? printf( $fila2[1] ) ?> , color:"<? printf( $fila2[2] ) ?>"},
<? } ?>
{ label: "n/n",  data: 0, color: "#FFF"}
];

    $.plot($("#placeholder2"), array_set, {
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



//graficos secundarios por depart
<?
$array_dept = [];
$i = 0;

$query_count_departament = mysqli_query($datos, "SELECT DISTINCT B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");

while($f1 = mysqli_fetch_row($query_count_departament)){
$array_dept[$i] = $f1[0];
$i = $i + 1;
}

$cant_dept = count($array_dept);

?>

<?
$query_dept_global = mysqli_query($datos, "SELECT COUNT(STSK_ID), B.USR_DEPT FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID)  WHERE STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");
$x = 0;
while($filax = mysqli_fetch_row($query_dept_global)){
?>
var array_set_<? printf(str_ireplace(" ", "_" , $filax[1])) ?> = [];
array_set_<?  printf(str_ireplace(" ", "_" , $filax[1])) ?> = [
<?
$handler = "";
$handler = mysqli_query($datos, "SELECT COUNT( STSK_ID ) , B.USR_DEPT, C.EST_DESCRIPT, C.EST_COLOR FROM SUBTASKS A INNER JOIN USERS B ON ( A.STSK_CHARGE_USR = B.USR_ID ) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (A.STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND B.USR_DEPT = '" . $array_dept[$x] . "') GROUP BY B.USR_DEPT, A.STSK_STATE ORDER BY B.USR_DEPT" );

while($subt = mysqli_fetch_row($handler)){
?>
{ label: "<? printf(  $subt[2] ) ?>",  data: <? printf( $subt[0] ) ?> , color:"<? printf( $subt[3] ) ?>"},
<? } ?>
{ label: "n/n",  data: 0, color: "#FFF"}
];

    $.plot($("#chart<? printf($x) ?>") , array_set_<? printf(str_replace(" ", "_" , $filax[1])) ?>, {
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

<? 
  $x = $x + 1;
}

 ?>

});


$("#placeholder2").bind("plothover", pieHover);
$("#placeholder2").bind("plotclick", pieClick);

             function pieHover(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            $("#hover").html('<span>' + obj.series.label + ' - ' + percent + '%</span>');
        }

        function pieClick(event, pos, obj) {
            if (!obj)
                return;
            percent = parseFloat(obj.series.percent).toFixed(2);
            alert('' + obj.series.label + ': ' + percent + '%');
        }

$("#selection, #personal").on("change" , function (){

var depto_eval = document.querySelector("#selection").options[document.querySelector("#selection").selectedIndex].text;
var name = document.querySelector("#personal").options[document.querySelectorAll("#personal")[0].selectedIndex].text;
$("#personal option").css({ display: "none" });
$("#personal option." + depto_eval).css({ display: "block" });
$("#personal option#general").css({ display: "block" });

var ind2 = document.querySelector("#personal").options[document.querySelectorAll("#personal")[0].selectedIndex].value;
var ind1 = $("#selection").val() - 1;
var mode = 0;
// ind1 ve el departamento, ind2 ve la naturaleza, ind3 ve  el personal
setDataByJSON(depto_eval, name, ind1, ind2, mode);

});

// create data.

function updateChart(){

$.ajax({ type: "POST", 
        url: "../backend/JSON.php?facility=" + fac, 
        success: function(datab){
               datas = datab;
                }
       });

}



function setDataByJSON(depto, name, index_d, index_p, mode){

var database = JSON.parse(datas);
var newData_eval = jlinq.from(database.data).select();

//make contador
var conta = eval('newData_eval[' + index_d + '].' + depto );
var per_conta = eval('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name );

console.log('newData_eval[' + index_d + '].' + depto + "[" + index_p + "]." + name);

console.info("valor de per_conta : " + per_conta.length);

// clean up the plot chart
$("#dynamics").html('');

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
$.plot($("#dynamics"), matriz, {
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

</script>
    </body>

<?

} else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}
?>