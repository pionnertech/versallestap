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
$iarray = array();

$i = 0;

while($extra = mysqli_fetch_row($depts)){
    $handup = mysqli_query($datos, "SELECT USR_NAME, USR_ID FROM USERS WHERE USR_DEPT = '" . $extra[0] . "'" );
        while( $sub = mysqli_fetch_row($handup)){
               $parray[$i] = $sub[0];
               $darray[$i] = $extra[0];
               $iarray[$i] = $sub[1];
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
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html">Eque-e</a>
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
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <b class="caret"></b></a>
                            </li>
                           
                            <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
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
                                <li><a href="message.html"><i class="menu-icon icon-inbox"></i>Página principal<b class="label green pull-right">
                                    11</b> </a></li>
                                <li><a href="task.html"><i class="menu-icon icon-tasks"></i><b class="label orange pull-right">
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
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2015 Eque-e </b>Todos los derechos reservados.
            </div>
        </div>
              </body>
 </html>
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

?>