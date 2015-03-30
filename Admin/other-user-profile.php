<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'admin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

$Query_team = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'back-user' AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "');");
$Query_subtask = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_DESCRIP, B.EST_DESCRIPT, A.STSK_FINISH_DATE, B.EST_COLOR, A.STSK_PROGRESS, A.STSK_LOCK FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " )" );
$Query_alerts = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " GROUP BY STSK_STATE");
$str_trf_usr = "SELECT DISTINCT A.TRF_USER, CONCAT(B.USR_NAME , ' ' ,  B.USR_SURNAME) FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE (TRF_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "') ORDER BY TRF_USER; ";
$Query_trf_usr = mysqli_query($datos, $str_trf_usr);
$Query_team_int = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_DEPT = '" . $_SESSION['TxtDept'] . "') UNION SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'admin'  AND USR_DEPT != '" . $_SESSION['TxtDept'] . "');");
// internal requirements
$query_internal= "SELECT A.STSK_ID, A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_MAIN_USR = " . $_SESSION['TxtCode'] . ")";
$internal =  mysqli_query($datos, $query_internal);
$quntum = mysqli_query($datos, "SELECT COUNT(STSK_ID) AS CONTADOR FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode']);

if(mysqli_num_rows($quntum) == 0){

    $contador = 0;
} else {
    $cont = mysqli_fetch_assoc($quntum);
    $contador = $cont['CONTADOR'];
}

$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " ORDER BY STSK_ID DESC LIMIT 1";
$notify = mysqli_fetch_assoc(mysqli_query($datos, $str_query));
if(!$notify){

    $manu = "";
} else {

    $manu = $notify['STSK_DESCRIP'];
}

$query_incoming = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . ")");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eque-e</title>
    <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="../css/theme.css" rel="stylesheet">  
    <link type="text/css" href="../css/uploader_style.css" rel="stylesheet" />
    <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
    <link rel="stylesheet" href="../css/jquery.plupload.queue.css" type="text/css" media="screen" />
    <style type="text/css">
.done{background:#daedb1;background:-moz-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#daedb1),color-stop(28%,#abd78d),color-stop(100%,#54ca50))!important;background:-webkit-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-o-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-ms-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:linear-gradient(to bottom,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#daedb1',endColorstr='#54ca50',GradientType=0)!important}.warning{background:#fefcea;background:-moz-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fefcea),color-stop(0%,#fefcea),color-stop(26%,#f1da36))!important;background:-webkit-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-o-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-ms-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:linear-gradient(to bottom,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fefcea',endColorstr='#f1da36',GradientType=0)!important}.delay{background:#ff5335;background:-moz-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ff5335),color-stop(100%,#d00e04));background:-webkit-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-o-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-ms-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:linear-gradient(to bottom,#ff5335 1%,#d00e04 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5335',endColorstr='#d00e04',GradientType=0)}.OwnComp{width:100%}.OwnComp-bars{background-color:#FFF;width:100%;margin:.5em;border:4px solid transparent;padding:1em 1.5em;width:80%}#Urgent-Display,#Audi-Display,#Com-Display{height:0;visibility:hidden;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.sub-del{width:55%;display:inline-block;vertical-align:top}#delegates{width:50%;position:relative;float:left}.require-subtasks{padding:0 1em;margin:.5em}#st-description{width:95%}.attach{display:none;vertical-align:top}.file-contents{width:100%}.file-contents,.file-contents p{display:inline-block;vertical-align:top}.display-progress{display:none}.At-int-ii{display:table-row}.Ec-int-ii,.Hc-int-ii,.Pe-int-ii,.Pv-int-ii{display:none}.At-int{display:table-row}.Ec-int,.Hc-int,.Pe-int,.Pv-int{display:none}.At{display:table-row}.Ec,.Hc,.Pe,.Pv{display:none}.ifile{margin:.5em;display:inline-block;vertical-align:top;cursor:pointer}.iname{display:block;text-align:left}#wrap-D{display:inline-block;max-height:20em}.toggle-attach{float:right;background-color:gray;border-radius:15px}.toggle-attach i{color:#fff;padding:.2em}#D-drop{height:20em;width:20em;float:right;background-color:#fff;border-radius:20px;border:1px orange solid;overflow-y:auto;overflow-x:hidden}#D-drop:after{content:"Arrastre aqui sus archivos";color:gray;position:relative;top:8em;left:2em;font-style:italic;font-size:1.3em}.attach,#wrap-D{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.after:after{content:"Arrastre aqui sus archivos"}.no-after:after{content:""}.collaborates{width:80%}.collaborates,.collaborates p{display:inline-block;vertical-align:top;font-size:.8em;font-style:italic}#audititle{font-style:italic;color:gray;width:100%}#wrapaudi{display:block;width:100%}.incoming-files{display:none}#froback{position:relative;float:right;color:#a9a9a9;font-style:italic}.spac{margin-right:.8em;color:#1e5799;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e5799',endColorstr='#207cca',GradientType=0)}.golang i,.spac{font-size:1.5em}.golang,.collaborates,.wrap-events{display:inline-block;vertical-align:top}.info-content{width:100%}.iss-descript{font-style:italic;font-size:.7em;display:inline-block;vertical-align:top}.events{color:#24B56C;font-size:1.5em}.wrap-events{width:auto;margin:0 .5em}.chrono{display:none}#back-to-main i{cursor:pointer}#back-to-main i:hover{color:#90ee90}.user-schedule{width:100%;height:auto}.wrap-charts{display:none}strong{font-size:.8em}.progressDisplay li{padding:5px}.utrf{display:none}.bolder{font-weight:bolder}.group{width:8%;border-radius:50%;padding:6px;border:1px solid #d3d3d3;border-radius:50%;display:inline-block;vertical-align:top;-webkit-transition:all 100ms ease-in-out;-moz-transition:all 100ms ease-in-out;transition:all 100ms ease-in-out}.group:hover{border:1px solid orange;width:10%}#descript-int{width:100%;}

.finished{
  color: #17D221;
  font-size: 1.5em;
  bottom: -2.2em;
  right: .5em;
  position: relative;
  opacity:0;
    -webkit-transition:all 600ms ease-in-out;
    -moz-transition:all 600ms ease-in-out;
    transition:all 600ms ease-in-out
}

    </style>    
</head>
<body>
<input id="muser" type="hidden" value="<? printf($_SESSION["TxtCode"]) ?>">
<input type="hidden" id="facility" value="<? printf($_SESSION['TxtFacility']) ?>">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html">Eque-e </a>
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
                            <ul class="dropdown-menu">  </ul>
                        </li>
                        <li><a href="#">Support </a></li>
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../images/ejecutivo4.jpg" class="nav-avatar" />
                            <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="../backend/close.php">Logout</a></li>
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
                            <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario<b class="label green pull-right">
                                <? echo $contador; ?></b> </a></li>
                        </ul>
                        <!--/.widget-nav-->
 
                        <!--/.widget-nav-->

                        <h3>Mis Compromisos</h3>
                 <div id="Audiencias" class="OwnComp">
                <div class="OwnComp-bars" style="border-right-color: #009D00; border-left-color: #009D00; cursor: pointer;" id="Audi">Compromisos</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Audi-Display">
                                        <li>
                                            <p>
                                             <strong>Compromisos Recibidos</strong> <span class="pull-right small muted">17%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 17%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Compromisos Activos</strong><span class="pull-right small muted">88%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 88%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Compromisos por vencer</strong> <span class="pull-right small muted">12%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 12%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Compromisos Atrasadas</strong> <span class="pull-right small muted">2%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 2%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                    </div> 
                    <!--/.sidebar-->
                </div>
                <!--/.span3-->
                <div class="span9">
                    <div class="content">
                        <div class="module">
                            <div class="module-body">
                                <div class="profile-head media">
                                    <a href="#" class="media-avatar pull-left" style=" width:4em; height: 4em">
                                        <img src="../images/ejecutivo4.jpg" style="width: 100%; height: 100%">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION["TxtUser"]) ?> <? printf($_SESSION["TxtPass"]) ?><small>Online</small>
                                        </h4>
                                        <p class="profile-brief">
                                         <? printf($_SESSION['TxtPosition']) ?> En SERVIU.
                                        </p>
                                        <div class="profile-details muted">
                                  <?  while($fi = mysqli_fetch_row($Query_alerts)){ 
                                       
                                       switch((int)$fi[1]){
                                          case 2:
                                            $type = "fa-angle-double-right";
                                            $taint = "#178FD0";
                                            $tuba =  "En Curso";
                                          break;
                                          case 4:
                                            $type = "fa-clock-o";
                                            $taint = "#EDB405";
                                            $tuba = "Por Vencer";
                                          break;
                                          case 3:
                                            $type = "fa-exclamation-triangle";
                                            $taint = "#E70101";
                                            $Tuba = "Atrasados";
                                          break;
                                          case 5:
                                             $type = "fa-check-circle";
                                             $taint = "#1CC131";
                                             $tuba = "Finalizados";
                                          break;


                                       }

                                    ?>
                                      
<a class="btn" title="<? printf($tuba) ?>"><p style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>; font-size: 1.5em; font-weight: 800;" ><? printf($fi[0]) ?></p>
<i class="fa <? printf($type) ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>"></i>
</a> 

<? } ?>
                                        </div>
                                    </div>
                                </div>

                                <ul class="profile-tab nav nav-tabs" id="kitkat">
                                    <li class="active" id="aux-back"><a href="#friends" data-toggle="tab">Equipo de trabajo</a></li>
                                    <li><a href="#require" data-toggle="tab">Compromisos Externos</a></li>
                                    <li><a href="#int-require" data-toggle="tab">Compromisos Internos</a></li>
                                </ul>
                                <div class="profile-tab-content tab-content">
                                    <div class="tab-pane fade active in" id="friends">
                                        <div class="module-option clearfix">
                                            <form>
                                            <div class="input-append pull-left">
                                                
                                            </div>
                                            </form>
                                        </div>
                                        <div class="module-body">

                                       <?

                                         $i = 0;
                                        $person_count = mysqli_num_rows($Query_team) - 1;
                                        while( $fila_per = mysqli_fetch_row($Query_team)){ 
                                         $i = $i + 1;

                                        ?>

                                           <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left stusr" >
                                                            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $fila_per[0] ?>.jpg" >
                                                            
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                <? printf($fila_per[1]) ?>  <? printf($fila_per[2]) ?>
                                                            </h3>
                                                            <p>
                                                                <small class="muted">Serviu</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <?
$matrix = "SELECT COUNT(A.STSK_ID),  B.EST_COLOR , B.EST_DESCRIPT , " .
          "ROUND((COUNT(A.STSK_ID)/(SELECT count(STSK_ID) FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $fila_per[0]. ")) * 100) AS percentage  " .
          "FROM SUBTASKS A RIGHT JOIN EST B ON(B.EST_CODE = A.STSK_STATE AND  STSK_CHARGE_USR = " . $fila_per[0]. ")  " .
          "GROUP BY B.EST_DESCRIPT";
$handler = mysqli_query($datos, $matrix);

                                                        ?>
                                                        <div class="media">
                                                            <div class="wrap-charts wc">
                                                                <ul class="widget widget-usage unstyled progressDisplay" >
                                                       <? while( $uI = mysqli_fetch_row($handler)) {?>
                                                                    <li>
                                                                        <p>
                                                                         <strong>Compromisos <? printf($uI[2]) ?></strong> <span class="pull-right small muted"><? printf($uI[0]) ?> / <? printf($uI[3]) ?>%</span>
                                                                        </p>
                                                                        <div class="progress tight" style="height: 5px;">

                                                                        <? 
                                                                           switch ($uI[2]) {
                                                                               case 'Pendiente':
                                                                                   $ix = " bar-warning";
                                                                                   break;
                                                                                case 'En curso':                                                                             
                                                                                   $ix = " bar-info";
                                                                                   break;
                                                                                case  'Atrasada':                                                                                                                                                               case 'Pendiente':
                                                                                   $ix = " bar-danger";
                                                                                   break;
                                                                                case  'Finalizada':                                                                                                                                                                case 'Pendiente':
                                                                                   $ix = " bar-success";
                                                                                   break;
                                                                                case  'Por Vencer':
                                                                                    $ix = " bar-warning";
                                                                                   break;
                                                                          
                                                                           }

                                                                        ?>
                                                                            <div class="bar<? printf($ix) ?>" style="width: <? printf($uI[3]) ?>%;">
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                <? } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               <? 
                                               if($i < $person_count){
                                                      $fila_per2 = mysqli_fetch_row($Query_team);
                                               ?>
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left stusr">
                                                            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $fila_per2[0] ?>.jpg">
                                                            
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                       
                                                                <? printf($fila_per2[1]) ?> <? printf($fila_per2[2] ) ?>
                                                            </h3>
                                                            <p>
                                                                <small class="muted">Serviu</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?
$matrix2= "SELECT COUNT(A.STSK_ID),  B.EST_COLOR , B.EST_DESCRIPT , " .
          "ROUND((COUNT(A.STSK_ID)/(SELECT count(STSK_ID) FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $fila_per2[0]. ")) * 100) AS percentage  " .
          "FROM SUBTASKS A RIGHT JOIN EST B ON(B.EST_CODE = A.STSK_STATE AND  STSK_CHARGE_USR = " . $fila_per2[0]. ")  " .
          "GROUP BY B.EST_DESCRIPT";
$handler2 = mysqli_query($datos, $matrix2);

                                                        ?>
                                                        <div class="media">
                                                            <div class="wrap-charts wc">
                                                                <ul class="widget widget-usage unstyled progressDisplay">
                                                             <? while( $uI2 = mysqli_fetch_row($handler2)) {?>
                                                                    <li>
                                                                        <p>
                                                                         <strong>Compromisos <? printf($uI2[2]) ?></strong> <span class="pull-right small muted"><? printf($uI2[0]) ?> / <? printf($uI2[3]) ?>%</span>
                                                                        </p>
                                                                        <div class="progress tight" style="height: 5px;">
                                                                        <? 
                                                                           switch ($uI2[2]) {
                                                                               case 'Pendiente':
                                                                                   $ix = " bar-warning";
                                                                                   break;
                                                                                case 'En curso':                                                                             
                                                                                   $ix = " bar-info";
                                                                                   break;
                                                                                case  'Atrasada':                                                                                                                                                               case 'Pendiente':
                                                                                   $ix = " bar-danger";
                                                                                   break;
                                                                                case  'Finalizada':                                                                                                                                                                case 'Pendiente':
                                                                                   $ix = " bar-success";
                                                                                   break;
                                                                                case  'Por Vencer':
                                                                                    $ix = " bar-warning";
                                                                                   break;
                                                                                            }
                                                                        ?>
                                                                            <div class="bar<? printf($ix) ?>" style="width: <? printf($uI2[3]) ?>%;">
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                <? } ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <? } ?>
                                           </div>
                                            <? }
                                             mysqli_data_seek($Query_team, 0);
                                             ?>
                                            <!--/.row-fluid-->
                                            <br />
                                            <div class="pagination pagination-centered">
                                                <ul>
                                                    <li><a href="#"><i class="icon-double-angle-left"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#"><i class="icon-double-angle-right"></i></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                   <div class="tab-pane fade" id="require">
                    <div class="module message">
                            <div class="module-head">
                                <h3>Control de cumplimientos</h3>
                            </div>
                            <div class="module-option clearfix">
                                <div class="pull-left">
                                    Filtro : &nbsp;
                                    <div class="btn-group">
                                        <button class="btn" id="showtitle" >Atrasados</button>
                                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="switcher" id="Ec"><a href="#">En Curso</a></li>
                                            <li class="switcher" id="Hc"><a href="#">Finalizados</a></li>
                                            <li class="switcher" id="Pv"><a href="#">Por vencer</a></li>
                                            <li class="switcher" id="At"><a href="#">Atrasados</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="pull-right">
                                   
                                </div>
                            </div>
                        <div>
                            <div class="module-body table">                             
                                <table class="table table-message" id="ext-tasks-table">
                                    <tbody>
                                        <tr class="heading">
                                            <td class="cell-icon"><i class="fa fa-lock" style="color: white;"></i></td>
                                            <td class="cell-title">Requerimiento</td>
                                            <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                            <td class="cell-title">Accion</td>
                                            <td class="cell-time align-right">Fecha máxima de entrega</td>

                                        </tr>
                                        <? 

                                        $class = "";
                                        $situation = "";
                                        $color = "";
                                        $lock = "";

                                        while ($stsk = mysqli_fetch_row($Query_subtask)){ 
                                         
                                         if($stsk[7] == 0 || $stsk[7] == '0'){

                                            $situation = "warning";
                                            $color = "color:#EE8817;";
                                            $lock = "";

                                         } else {

                                            $situation = "lock";
                                            $color = "color: #44D933;";
                                            $lock = "disabled";
                                         }


                                          switch ($stsk[3]){
                                              case 'Pendiente':
                                              $class = "Pe";
                                              break;
                                              case 'En Curso':
                                               $class = "Ec";

                                              break;

                                              case 'Finalizada':
                                               $class = "Hc";
                                              break;

                                              case 'Atrasada':
                                               $class = "At";
                                              break;

                                              case 'Por Vencer':
                                              $class = "Pv";
                                              break;
                                          }


        //archivos adjuntos

                                            ?> 
                                        <tr class="task <? printf($class) ?>">
                                            <td class="cell-icon"><i class="fa fa-<? printf($situation) ?>" style="<? printf($color) ?> ; cursor:pointer;"></i></td>
                                            <td class="cell-title"><? printf($stsk[2])  ?></td>
                                            <td class="cell-status"><b class="due" style="background-color: <? printf($stsk[5]) ?>;"><? printf($stsk[3]) ?></b></td>
                                            <td class="cell-title"><button class="btn btn-small forward" <? printf($lock) ?> >Delegar</button></td>
                                            <td class="cell-time align-right"><span><? printf(date("d/m/Y", strtotime(substr($stsk[4], 0, 10)))) ?></span></td>
                                            <input type="hidden" class="st" value="<? printf($stsk[0]) ?>">
                                            <input type="hidden" class="iss_id" value="<? printf($stsk[1]) ?>">
                                        </tr>
                                        <tr class="display-progress">
                                            <td colspan="5">
                                    <div class="info-content">
                                            <? 
$shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP ,  CONCAT(B.CTZ_NAMES , ' ', B.CTZ_SURNAME1, ' ',  B.CTZ_SURNAME2) AS NAME, B.CTZ_ADDRESS, B.CTZ_TEL   FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) WHERE ISS_ID = " . $stsk[1] ));
                                            ?>
                              <p class="iss-descript"><strong>Ciudadano</strong> : <? echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($shine['NAME'])))); ?></p> 
                              <p class="iss-descript"><strong>Teléfono</strong> : <? printf($shine['CTZ_TEL']) ?></p> 
                              <p class="iss-descript"><strong>Dirección</strong> : <? printf($shine['CTZ_ADDRESS']) ?></p> 
                              <p class="iss-descript"><strong>Descripcion compromiso</strong> : <? printf($shine['ISS_DESCRIP']) ?></p>          
                                        </div>
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($stsk[6]) ?>%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: <? printf($stsk[6]) ?>%;"></div>
                                            </div>
                                            <div class="collaborates">
                                            <i class="fa fa-group spac"></i>
                                                <?
$spec_tem = mysqli_query($datos, "SELECT CONCAT(A.USR_NAME , ' ',  A.USR_SURNAME), A.USR_ID FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR) WHERE (STSK_ISS_ID = " . $stsk[1] . " AND STSK_CHARGE_USR != STSK_MAIN_USR);");
 while($fila_spec = mysqli_fetch_row($spec_tem)){ ?>

        <a href="#" class="hovertip" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila_spec[0]))))) ?>">
            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $fila_spec[1]; ?>_opt.jpg" class="group" >
            <i class="fa fa-check-circle finished"></i>
            <input type="hidden" value="u<? printf($fila_spec[1])?>">
        </a>
    <?  }  ?>
    
                                            </div>
                                            <p class="golang"><i class="fa fa-paperclip" style="color:darkyellow;"></i></p>
                                            <p class="wrap-events"><i class="fa fa-calendar-o events"></i></p>
                                            <div class="file-contents">
                                           
                                            <?   
                                           
                         while($steam = mysqli_fetch_row($Query_team)){

                               if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $steam[0] . "_in/")){

                                  continue; 

                                    } else {

                                if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $steam[0] . "_in/" )){
                                    
                      

                                          $file_extension = "";

                                        while (false !== ($archivos = readdir($handler))){
                              
                                         if(preg_match_all("/_" . $stsk[0] . "_/", $archivos) == 1){
                                             
                                              $extension = substr($archivos, -3);
                                              $cor = "";

                                                 switch (true) {
                                                      case ($extension =='pdf'):
                                                      $file_extension = "pdf-";
                                                      $cor = "#FA2E2E";
                                                      break;
                                                      case ($extension =='xls' || $extension =='lsx'):
                                                      $file_extension = "excel-";
                                                      $cor = "#44D933";
                                                      break;
                                                      case ($extension =='doc' || $extension =='ocx' ):
                                                      $file_extension = 'word-';
                                                      $cor = "#5F6FE0";
                                                      break;
                                                      case ($extension == 'zip'):
                                                      $file_extension = "archive-";
                                                      $cor = "#DDCE62";
                                                      break;
                                                      case ($extension == "png" || $extension =='jpg' || $extension =='bmp'):
                                                      $file_extension = "picture-";
                                                      $cor = "#338B93";
                                                      break;
                                                      default :
                                                      $file_extension = "";
                                                      $cor = "#8E9193";
                                                      break;
                                                 }


                                          ?>

                         <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($steam[0]) ?>_in/<? printf($archivos) ?>" download><p class="ifile" title="<? printf($archivos) ?>"><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                 <span class="iname"></span>
                                                </p>
                                                </a>
                                                  <? }
                                                  } 
                                        closedir($handler);
                                       }
                                    }
                                }
                                  mysqli_data_seek($Query_team, 0);
                                                  ?>

                                            </div>
                                            <div class="toFront"></div>
                                            </td>
                                        </tr>
                                        <? } 

                                    mysqli_data_seek($Query_subtask, 0);

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="module-foot">
                            </div>
                        </div>
                       </div> 
                  </div>
                  <div class="tab-pane fade" id="events">
                        <div class="module-body table"> 
                           <div id="back-to-main"><i class="fa fa-chevron-circle-left" style="color: #16A2E4; float: left; font-size:1.5em"></i></div>                            
                            <table class="table table-message" id="del-partners">
                                <tbody>
                                     <tr class="heading">
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                     </tr>
                           <? 

$Query_trf_usr  =  mysqli_query($datos, "SELECT DISTINCT A.TRF_USER, CONCAT(B.USR_NAME , ' ', B.USR_SURNAME) FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE TRF_FAC_CODE = " . $_SESSION['TxtFacility'] );

                           while ($trf = mysqli_fetch_row($Query_trf_usr)){

                            ?>
                                <tr class="u<? printf($trf[0]) ?> utrf">
                                    <td colspan="3" >
                                        <div class="user-schedule">
                                            <div class="media" style="display : inline-block">
                                                <a href="#" class="media-avatar pull-left" style=" width:4em; height: 4em" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trf[1]))))) ?>">
                                                    <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? printf($trf[0]) ?>.jpg" style="width: 100%; height: 100%">
                                                </a>
                                            </div>
                                            <p style="font-size: 2em; font-style: italic; color: gray; display: inline-block; vertical-align: bottom;"><? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trf[1]))))) ?></p>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="task u<? printf($trf[0]) ?>"  >
                                    <td><span class="bolder">Asunto</span></td>
                                    <td><span class="bolder">Descripción</span></td>
                                    <td class="align-right"><span class="bolder">Fecha Progreso</span></td>
                                </tr>

                            <? 
$str_traffic = "SELECT A.TRF_STSK_SRC_ID,  " .
"A.TRF_SUBJECT, " . 
"A.TRF_DESCRIPT, " . 
"A.TRF_ING_DATE " . 
"FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) " . 
"WHERE USR_ID = " . $trf[0] . " ORDER BY TRF_ING_DATE;";

$Query_traffic =  mysqli_query($datos, $str_traffic);

                            while($rows = mysqli_fetch_row($Query_traffic)){  ?>         
                                     <tr class="task st<? printf($rows[0]) ?> chrono" >
                                         <td class="cell-title"><? echo $rows[1]; ?></td>
                                         <td class="cell-title"><? echo $rows[2]; ?></td>
                                         <td class="cell-time align-right"><? echo date('d/m/Y', strtotime($rows[3])); ?></td>
                                     </tr>
                             <?      }        ?>  
                         <?      }        ?>        
                                </tbody>
                            </table>
                        </div>

                  </div>

<!--  selecionar los nombres -->
                        <div class="tab-pane fade" id="tasks-own">
                           <div class="media-stream">
                           <p class="toggle-attach"><i class="fa fa-paperclip fa-2x"></i></p>
                                <div class="sub-del">
                                    <strong id="froback">Para Back Office</strong>
                                    <h3>Subdelegar tareas</h3>
                                    <strong id="wrapaudi"><small id="audititle"></small></strong>
                                                <select id="delegates">
                                                <optgroup label="<? printf($_SESSION['TxtDept']) ?>">
                                                <option value="0"></option>
                                              <?  while($team = mysqli_fetch_row($Query_team)){ ?>
                                                        <option value="<? printf($team[0]) ?>"><? printf($team[1]) ?> <?printf($team[2]) ?></option>
                                                        <? } 
                                                   mysqli_data_seek($Query_team, 0);
                                                        ?>
                                                    </optgroup>
                                                </select>
                                    <input type="text" id="subject" class="require-subtasks eras" val="" placeholder="asunto">
                                    <input type="hidden" value="" id="current-task"> 
                                    <input type="text" placeholder="Fecha Termino" class="datetimepicker eras" styles="vertical-align:top; display: inline-block;"/><br><br>
                                    <textarea id="st-description" placeholder="Descripcion del requerimiento" class="eras" style="margin: 1.5em .5em"></textarea>
                                    <div><button class="btn btn-info" id="del-subtask">Delegar Requerimiento</button></div>
                                </div>
                       
                                <div id="wrap-D">
                                    <div id="D-drop" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    </div>

                                </div>
                                <div class="attach">
                                    <form id="upload" method="post" action="../backend/upload.php" enctype="multipart/form-data">
                                         <div id="drop">
                                             Arrastra Aqui
                                               <a>Buscar</a>
                                               <input type="file" name="upl" multiple />
                                               <input type="hidden" value="" name="code" id="stsk-code">
                                               <input type="hidden" value="<? printf($_SESSION['TxtFacility']) ?>" name="fac">
                                               <input type="hidden" value="" name="user" id="stsk-user">
                                               <input type="hidden" value="" name="issId" id="issId">
                                        </div>
                                         <ul>

                                         </ul>
                                    </form>
                              </div>
                              <div class="incoming-files">
                                            <?    
                                while($stsk_esp = mysqli_fetch_row($Query_subtask)){
                                   
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/" )){

                                          $file_extension2 = "";
                                        
                                           while (false !== ($archivos2 = readdir($handler2))){
                                          
                                            if(preg_match_all("/_" . $stsk_esp[1] . "_/", $archivos2) == 1){
                                     
                                                $extension = substr($archivos2, -3);
                                          
                                                $cor = "";
                                                 switch (true) {
                                                      case ($extension =='pdf'):
                                                      $file_extension = "pdf-";
                                                      $cor = "#FA2E2E";
                                                      break;
                                                      case ($extension =='xls' || $extension =='lsx'):
                                                      $file_extension = "excel-";
                                                      $cor = "#44D933";
                                                      break;
                                                      case ($extension =='doc' || $extension =='ocx' ):
                                                      $file_extension = 'word-';
                                                      $cor = "#5F6FE0";
                                                      break;
                                                      case ($extension == 'zip'):
                                                      $file_extension = "archive-";
                                                      $cor = "#DDCE62";
                                                      break;
                                                      case ($extension == "png" || $extension =='jpg' || $extension == 'bmp'):
                                                      $file_extension = "picture-";
                                                      $cor = "#338B93";
                                                      break;
                                                      case ($extension == "txt"):
                                                      break;
                                                 }

                                              if(strlen($archivos2) > 4){
                                          ?>
                                        <p class="ifile iss<? printf($stsk_esp[1]) ?>" draggable="true" ondragstart="drag(event)" id="<? printf($archivos2) ?>" ><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x"  style="color: <? printf($cor) ?> "></i>
                                                 <span class="iname"><? printf($archivos2) ?></span>
                                                </p>
                                                  <? 
                                                  }
                                                }
                                              } // while false
                                        closedir($handler2);
                                        }
                                    } //while
                                  
                                 ?>
                                       </div>
                                    </div>
                                 </div>
                                       <div class="tab-pane fade" id="int-require">
                                            <div class="module message">
                                                   <div class="module-head">
                                                       <h3 style="display:inline-block">Compromisos Internos</h3>
                                                        <i class="fa fa-sign-in fa-2x" style="color: blue; cursor: pointer; float: right" id="sw-int-in-out"></i>
                                                   </div>
                                            <div class="module-option clearfix">
                                            <button class="btn btn-info del-int" style="float: right">Crear Requerimiento</button>
                                                    <div class="pull-left">
                                                        Filtro : &nbsp;
                                                        <div class="btn-group">
                                                            <button class="btn title-int">Atrasados</button>
                                                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li class="swt-int" id="Ec-int"><a href="#">En Curso</a></li>
                                                                <li class="swt-int" id="Pv-int"><a href="#">Por Vercer</a></li>
                                                                <li class="swt-int" id="At-int"><a href="#">Atrasados</a></li>
                                                                <li class="swt-int" id="Hc-int"><a href="#">Finalizados</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                            <div class="pull-right"></div>
                                            </div>
                                            <div class="module-body table">
                                                   <table class="table table-message" id="int-table">
                                                      <tbody id="int-body">
                                                          <tr class="heading">
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Descripción requerimiento</td>
                                                              <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                                              <td class="cell-title">Asignar</td>
                                                              <td class="cell-time align-right">Fecha maxima respuesta</td>
                                                            </tr>
                         <? while ($fila5 = mysqli_fetch_row($internal)) {

                                         if($fila5[9] == 0 || $fila5[9] == '0'){

                                            $situation = "exclamation";
                                            $color = "color:#EE8817;";
                                            $lock = "";

                                         } else {

                                            $situation = "check";
                                            $color = "color: #44D933;";
                                            $lock = "disabled";
                                         }


                                          switch ($fila5[6]){
                                              case 'Pendiente':
                                              $class = "Pe-int";
                                              break;
                                              case 'En Curso':
                                               $class = "Ec-int";

                                              break;

                                              case 'Finalizada':
                                               $class = "Hc-int";
                                              break;

                                              case 'Atrasada':
                                               $class = "At-int";
                                              break;

                                              case 'Por Vencer':
                                              $class = "Pv-int";
                                              break;
                                          }
                                                    ?>

                                                            <tr class="task <? echo $class; ?>">
                                                                <input type="hidden" value="<? echo $fila5[0]; ?>" class="hi-int-id">
                                                                <td class="cell-icon int-lock" style="cursor: pointer;  <? echo $color; ?>" ><i class="fa fa-<? echo $situation; ?> "></i></td>
                                                                <td class="cell-title"><div><? echo $fila5[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due int-desglo" style="background-color:<? echo $fila5[8]; ?>"><? echo $fila5[6]; ?></b></td>
                                                                <td class="cell-title int-forward" style="cursor:pointer;"><i class="fa fa-chevron-circle-right"></i></td>
                                                                <td class="cell-time align-right"><? echo date("d/m/Y", strtotime(substr($fila5[10], 0, 10))) ?></td>
                                                            </tr>
                                                            <tr style="display: none;">
                                                                <td colspan="5">
                                                                   <p>
                                                                        <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila5[7]) ?>%</span>
                                                                    </p>
                                                                    <div class="progress tight">
                                                                        <div class="bar bar-warning" style="width: <? printf($fila5[7]) ?>%;"></div>
                                                                    </div>
                                                                    <div class="coll-int" style="width: 100%">
                                                                        <a href="#" class="hovertip" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila5[2]))))) ?>">
                                                                            <img src="../<? echo $_SESSION['TxtFacility']  ?>/img/<? echo $fila5[1]; ?>_opt.jpg" class="group" >
                                                                            <input type="hidden" value="u<? printf($fila5[1])?>">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <? } ?>
                                                           </tbody>
                                                    </table>   
                                            </div>
                                        <div class="module-option clearfix" style="display:none">
                                                    <div class="pull-left">
                                                        Filtro : &nbsp;
                                                        <div class="btn-group">
                                                            <button class="btn title-int-ii">Atrasados</button>
                                                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li class="swt-int-ii" id="Ec-int-ii"><a href="#">En Curso</a></li>
                                                                <li class="swt-int-ii" id="Pv-int-ii"><a href="#">Por Vercer</a></li>
                                                                <li class="swt-int-ii" id="At-int-ii"><a href="#">Atrasados</a></li>
                                                                <li class="swt-int-ii" id="Hc-int-ii"><a href="#">Finalizados</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                            <div class="pull-right"></div>
                                            </div>
                                            <div class="module-body table" style="display:none">
                                                <table class="table table-message" id="income-ing">
                                                    <tbody id="income-int-body">
                                                          <tr class="heading" >
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Descripción requerimiento</td>
                                                              <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                                              <td class="cell-title">Asignar</td>
                                                              <td class="cell-time align-right">Fecha maxima respuesta</td>
                                                            </tr>
                                                        <? while ($ii = mysqli_fetch_row($query_incoming)) { 
                                                                  if($ii[9] == 0 || $ii[9] == '0'){

                                                                          $situation = "exclamation";
                                                                          $color = "color:#EE8817;";
                                                                          $lock = "";

                                                                       } else {
                              
                                                                          $situation = "check";
                                                                          $color = "color: #44D933;";
                                                                          $lock = "disabled";
                                                                       }


                                                                        switch ($ii[6]){
                                                                            case 'Pendiente':
                                                                            $class = "Pe-int-ii";
                                                                            break;
                                                                            case 'En Curso':
                                                                             $class = "Ec-int-ii";

                                                                            break;

                                                                            case 'Finalizada':
                                                                             $class = "Hc-int-ii";
                                                                            break;
                              
                                                                            case 'Atrasada':
                                                                             $class = "At-int-ii";
                                                                            break;

                                                                            case 'Por Vencer':
                                                                            $class = "Pv-int-ii";
                                                                            break;
                                                                       }

                                                            ?>
                                                            <tr class="task <? echo $class; ?>">
                                                                <input type="hidden" value="<? echo $ii[0]; ?>" class="hi-int-id">
                                                                <td class="cell-icon int-lock" style="cursor: pointer;  <? echo $color; ?>" ><i class="fa fa-<? echo $situation; ?> "></i></td>
                                                                <td class="cell-title"><div><? echo $ii[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due ii-desglo" style="background-color:<? echo $ii[8]; ?>"><? echo $ii[6]; ?></b></td>
                                                                <td class="cell-title ii-forward" style="cursor:pointer;"><i class="fa fa-chevron-circle-right"></i></td>
                                                                <td class="cell-time align-right"><? echo date("d/m/Y", strtotime(substr($ii[10], 0, 10))) ?></td>
                                                            </tr>
                                                         <? } //fin  while incoming ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                         </div> 
                                     </div> 
                                     <div class="tab-pane fade" id="del-int-req">
                                          <div id="wrap-controls">
                                          <div id="int-back" style="cursor: pointer;"><i class="fa fa-chevron-circle-left fa-2x"></i></div>
                                          <input type="text" id="subj-int" value="" placeholder="Ingrese un asunto" style="width: 98%;">
                                          <textarea id="descript-int" value="" placeholder="Describa el requerimiento" style="width:98%"></textarea>
                                          <select id="int-del" style="width: 55%; display: inline-block; vertical-align: top;">
                                              <? while($fila4 = mysqli_fetch_row($Query_team_int)) { ?>
                                                  <option value="<? echo $fila4[0] ?>"><? echo $fila4[1]  ?> <? echo $fila4[2]  ?></option><? } ?>
                                              </select>
                                          <input type="text" class="date-int-finish" style="display: inline-block;vertical-align: top; float: right">
                                          <div id="up-int"></div>
                                          <div align="center"><button id="send-int" class="btn btn-info">Enviar Requerimiento</button></div>
                                          </div>
                                     </div>
                         <div class="tab-pane fade" id="set-pro-int">
                            <div class="media-stream">
                                <div class="sub-del">
                                <div id="back-ii"><i class="fa fa-chevron-circle-left fa-3x"></i></div>
                                    <h3>Subir Cumplimientos</h3>
                                    <strong id="wrapaudi"><small id="audititle"></small></strong>
                                    <input type="text" id="subject" class="require-subtasks" value="" placeholder="asunto">
                                    <textarea id="st-description" placeholder="Descripcion cumplimiento" style="margin: 1.5em .5em"></textarea>
                                    <div class="progress-go">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                            </p>
                                             <input type="text" class="span2" />
                                    </div>
                                    <button class="btn btn-info" id="upgrade">Subir Progreso</button>
                                </div>
                                <div class="attach">
                                    <form id="upload2" method="post" action="../backend/upload_admin_to_par_up.php" enctype="multipart/form-data">
                                         <div id="drop">
                                             Arrastre aqui sus archivos
                                               <a>Buscar</a>
                                               <input type="file" name="upl" multiple />
                                               <input type="hidden" value="" name="code" id="stsk-code">
                                               <input type="hidden" value="<? printf($_SESSION['TxtFacility']) ?>" name="fac">
                                               <input type="hidden" value="" name="user" id="stsk-user">
                                               <input type="hidden" value="" name="">  
                                          </div>
                                         <ul>
                <!-- The file uploads will be shown here -->
                                         </ul>
                                    </form>
                              </div>
                          </div>

                                     </div>
                            <!--/.module-body-->
                        </div>
                        <!--/.module-->
                    </div>
                    <!--/.content-->
                </div>
                <!--/.span9-->
            </div>
        </div>
        <!--/.container-->
        <audio id="successAudio"><source src="../backend/success.mp3" type="audio/mpeg"></audio>
        <audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="../backend/notify.mp3" type="audio/mpeg"><source src="notify.wav" type="audio/wav"></audio>
    </div>
    <!--/.wrapper-->
    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2015 Eque-e </b>Todos los derechos reservados.
        </div>
    </div>
    <script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../scripts/jquery.knob.js"></script>
    <script src="../scripts/jquery.ui.widget.js"></script>
    <script src="../scripts/jquery.iframe-transport.js"></script>
    <script src="../scripts/jquery.fileupload.js"></script>
    <script src="../scripts/script.js"></script>
    <script src="../scripts/script-int.js"></script>
    <script type="text/javascript" src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="../scripts/plupload.full.min.js"></script>  
    <script type="text/javascript" src="../scripts/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="../scripts/es.js"></script>
    <script src="http://modernizr.com/downloads/modernizr-latest.js"></script>
    <script src="../scripts/bootstrap-slider.js"></script>
</body>

<script type="text/javascript">
    
    var st           = 0;
    var fac          = <? printf($_SESSION['TxtFacility'] ) ?>;
    var previuosData = <? printf("\"" . $manu . "\"")  ?>;
    var um           = 0;
    var mainuser     = <? printf($_SESSION['TxtCode'])  ?>;
    var intPointer   = 0;
    var mode         = "";
    var user_send    = "";
    var stsk_send    = "";
    var keyFile      = "";
    var dateTime
    var objeto;
    var dateTime;


    $(document).on('ready', function(){

  progressbar =  $('.span2').slider({ step: 10 , max: 100, min: 0});

 dateTime = $('.datetimepicker').datetimepicker({
    step:5,
    lang:'es',
    format:'Y/m/d',
    timepicker: false,
    onShow: function (ct){

        this.setOptions({
            minDate : '1970/01/02',  
            maxDate : dateTime,
            format:'d/m/Y'
        })
    }
});

 $(".date-int-finish").datetimepicker({
    step:5,
    lang:'es',
    format:'Y/m/d',
    minDate: '-1970/01/02',
    timepicker: false,
    onShow : function (oct){
    if(mode == "delegate"){
            this.setOptions({
                format:'Y/m/d',
                maxDate : dateTime
            });
        } else {
            this.setOptions({
                format:'Y/m/d',
                maxDate : '2036/12/29'
            });
        }
    }, 
    onSelectDate : function (ct){
       this.setOptions({ format : 'd/m/Y' });
       this('hide');
    }
});

 init();



$(".int-forward").click(function(){

dateTime = AmericanDate($(this).next().html());

       mode = "delegate";
 var indice = $(this).index();
 var ids    = $(this).parent().children('input').val();

stsk_send = ids;
console.log("stsk_send is :" + ids);

$("#del-int-req").data("val",indice );
$("#send-int").data("val", ids);
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');


});

$(".ii-forward").click(function(){

dateTime = AmericanDate($(this).next().html());

       mode = "delegate";
 var indice = $(this).index();
 var ids    = $(this).parent().children('input').val();

stsk_send = ids;
console.log("stsk_send is :" + ids);

$("#del-int-req").data("val",indice );
$("#send-int").data("val", ids);
$("#int-require").removeClass('active in');$("#set-pro-int").addClass('active in');


});

$("#back-ii").click(function(){
    $("#set-pro-int").removeClass('active in');$("#int-require").addClass('active in');
})


$(".toggle-attach").on('click', function(){

    if (st == 0){

 $("#wrap-D").css({ display: "none"});
 $(".attach").css({ display : "inline-block" });
 $("#froback").html('Para Front office');
 $(".incoming-files").css({display : "none"});
 st = 1;
    } else {
         $(".attach").css({ display: "none"});
         $("#wrap-D").css({ display: "inline-block" });
         $("#froback").html('Para Back Office');
         if ($("#delegates").val() != 0){
             $(".incoming-files").css({display : "block"});
         }
        
   st = 0;
    }

})


        $("#Urgent").on('click', function(){
         
         if (!$(this).data("val")  ||  $(this).data("val") === 0){
                $("#Urgent-Display").css({ height: "250px"});
                   $("#Urgent-Display").css({ visibility: "visible"});
               $(this).data("val", 1);
         }  else {

      $("#Urgent-Display").css({ height: "0px"});
               $("#Urgent-Display").css({ visibility: "hidden"});
                 $(this).data("val", 0);
         }


        })

            $("#Audi").on('click', function(){

             if(!$(this).data("val") || $(this).data("val") === 0 ){
                $("#Audi-Display").css({ visibility : "visible"});
                $("#Audi-Display").css({ height: "250px"});
                    
                    $(this).data("val", 1);
             } else {
                $("#Audi-Display").css({ visibility : "hidden"});
                $("#Audi-Display").css({ height: "0px"});
                    $(this).data("val", 0);
             }
            })


        $("#Com").on('click', function(){

             if(!$(this).data("val") || $(this).data("val") === 0 ){
                    $("#Com-Display").css({ height: "250px"});
                       $("#Com-Display").css({ visibility: "visible"});
                          $(this).data("val" , 1); 
             }  else {
                $("#Com-Display").css({ visibility: "hidden"});
                 $("#Com-Display").css({ height: "0px"});
                       
                          $(this).data("val" , 0);

             }

                });


$(".forward").on("click", function(){
                                    
var stsk_id = $(this).parent().parent().children('input.st').val();
var iss_ident = $(this).parent().parent().children('input.iss_id').val();
var subject = $(this).parent().parent().children('td').eq(1).text();
var index_current = parseInt($(this).index());

dateTime = AmericanDate($(this).parent().next().children().html());


$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});


$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

});



$(".del-int").on('click', function(){

     mode = "first";
     
$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');

});

$("#int-back").on('click', function(){
$("#del-int-req").removeClass('active in');$("#int-require").addClass('active in');
$("#up-int").empty();

});


$("#send-int").on('click', function(){
if (mode == "first"){
    intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#del-int-req").data("val"), 0 );
} else {
    intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#del-int-req").data("val"), $("#send-int").data("val") );
}  
});



$("#delegates").on('change', function(){

    if ($(this).val() == 0 ){
            $(".incoming-files").css({display: "none"});
    } else  {
        if(st == 0){
             $(".incoming-files").css({display: "block"});
        } 
    }
    $("#stsk-user").val($("#delegates").val());

});

});

$("#del-subtask").on('click', function(){
    //check type.

var _fS = new Date();
var fechaS = _fS.getFullYear() + "-" + ('0' + (_fS.getMonth()+1)).slice(-2) + "-" + ('0' + _fS.getDate()).slice(-2) + " 10:00:00";

console.info();
    $.ajax({
        type: "POST",
        url: "../backend/stsk-del.php?iss_id=" + $("#issId").val() + 
        "&muser=" + $("#muser").val() +
        "&user=" + $("#stsk-user").val() +
        "&stsk=" + $("#stsk-code").val() + 
        "&subject=" + $("#subject").val() +
        "&descript=" + $("#st-description").val() +
        "&startD=" + fechaS + 
        "&fechaF=" + ($(".datetimepicker").val()).replace(/\//g, "-") + 
        "&fac=" + $("#facility").val(), 
        success : function(data){

           bootbox.alert("Requerimiento delegado existosamente");
            $("#kitkat li").eq(3).removeClass('active');$("#kitkat li").eq(2).addClass('active');
            $("#tasks-own").removeClass('active in');$("#require").addClass('active in');
            $("#D-drop").empty();
            $(".eras").val('');
       
                var target =  $("#current-task").val();
                var ancient = $(".collaborates").eq(target).html();
               
                var key_main    = document.querySelectorAll(".collaborates")[target];

                var a_del       = document.createElement('a');
                a_del.className = "hovertip";
                a_del.title     = ancient + data;

                var img_del =  document.createElement('img');
                img_del.src = "../" + fac + "/img/" + $("#stsk-user").val() + "_opt.jpg";

                var inp_del   = document.createElement('input');
                inp_del.type  = "hidden";
                inp_del.value = "u" + $("#stsk-user").val();

                a_del.appendChild(img_del);
                a_del.appendChild(inp_del);
                key_main.appendChild(a_del);

               // nueva delegacia 
                    $("#upload ul").empty();
        }
    })
});



$(".switcher").on('click', function(){

    var all_on = document.querySelectorAll('.switcher');

    var ex = $(this).attr("id");
    var title_in = $(this).html();
    $(".display-progress").css({ display: "none"});
    $("#showtitle").html(title_in);

     for(i=0; i < all_on.length ; i++){
           if(all_on[i].id !== ex){
              $('.' + all_on[i].id).css({ display : "none"});
           } else {
              $('.' + all_on[i].id).css({ display: "table-row"});
           }
        
     }
});


$(".swt-int").on('click', function(){

    var all_on = document.querySelectorAll('.swt-int');

    var ex = $(this).attr("id");
    var title_in = $(this).html();
    $(".display-progress").css({ display: "none"});
    $(".title-int").html(title_in);
     for(i=0; i < all_on.length ; i++){
           if(all_on[i].id !== ex){
              $('.' + all_on[i].id).css({ display : "none"});
           } else {
              $('.' + all_on[i].id).css({ display: "table-row"});
           }
        
     }
});


$(".swt-int-ii").on('click', function(){

    var all_on = document.querySelectorAll('.swt-int-ii');

    var ex = $(this).attr("id");
    var title_in = $(this).html();
    $(".display-progress").css({ display: "none"});
    $(".title-int-ii").html(title_in);
     for(i=0; i < all_on.length ; i++){
           if(all_on[i].id !== ex){
              $('.' + all_on[i].id).css({ display : "none"});
           } else {
              $('.' + all_on[i].id).css({ display: "table-row"});
           }
        
     }
});


$(".due").on('click', function(){

if(!$(this).data("val") || !$(this).data("val") === 0 ){

   $(this).parent().parent().next().css({ display: "table-row"});
     $(this).data("val", 1);
} else  {

  $(this).parent().parent().next().css({ display: "none"});
   $(this).data("val", 0);
}

});

$(".cell-icon").on('click', function(){

if(!$(this).hasClass('int-lock')){
obj = $(this).children('i');
  var stsk =  $(this).parent().children('input').eq(0).val();
  var iss_id = $(this).parent().children('input').eq(1).val();
  bootbox.confirm("Esta seguro de cerrar este requerimiento?", function (confirmation){
    if (confirmation){
           unlock(stsk, iss_id, obj);
    }
  })
}
});



$(".int-lock").on('click', function(){

    var obj = $(this).children('i');
    var stsk_int = $(this).parent().children('input').val();
    bootbox.confirm("Esta seguro de cerrar este requerimiento?", function (confirmation){
    if (confirmation){
           unlock(stsk_int, "" , obj);
    }
  });
})



$(".golang").on('click', function(){
    if($(this).data("val") === undefined){
     
        $(this).data("val", 1);
            var object = $(this).parent().children('.toFront');
            var iss_id = $(this).parent().parent().prev().children('input').eq(1).val();
        
                uploaderInt(object, iss_id);
    } else {

       $(this).parent().children('.toFront').fadeToggle('slow');
    }

});

$("#sw-int-in-out").on('click', function(){

  if($(this).data("val") == 0 || $(this).data("val") == undefined){

    $(this).removeClass("fa-sign-out");
    $(this).addClass("fa-sign-in");
    $(this).css({ color: "orange"});
    $(this).data("val", 1);
 
     $("#int-table").fadeOut(400, function(){
         $("#income-ing").fadeIn(400, function(){
            $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(0).fadeOut(100, function(){
                 $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(1).fadeIn(100, function(){
                    $("#sw-int-in-out").parent().parent().children("div.module-body").eq(0).fadeOut(100, function(){
                         $("#sw-int-in-out").parent().parent().children("div.module-body").eq(1).fadeIn(100);
                    });
                 });
            });
         });
         
     });

     } else {
   
    $(this).removeClass("fa-sign-in");
    $(this).addClass("fa-sign-out");
    $(this).css({ color: "blue"});
    $(this).data("val", 0);
      $("#income-ing").fadeOut(400, function(){
         $("#int-table").fadeIn(400, function(){
            $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(1).fadeOut(100, function(){
                 $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(0).fadeIn(100, function(){
                    $("#sw-int-in-out").parent().parent().children("div.module-body").eq(1).fadeOut(100, function(){
                         $("#sw-int-in-out").parent().parent().children("div.module-body").eq(0).fadeIn(100);
                    })
                 });
            });
         });
     });

     }

})


function unlock(stsk_id, iss_id, object){

$.ajax({
       type: "POST",
       url: "../backend/unlock.php?stsk_id=" + stsk_id + "&iss_id=" + iss_id,
       success : function(data){

           object.fadeOut(400, function(){

           object.removeClass("fa-warning");

           if(object.parent().hasClass("int-lock")){

              object.addClass("fa-check");

           } else {

              object.addClass("fa-lock");
           }
           
           object.css({color:"#44D933"});
           object.parent().parent().children('td.cell-title').children('button').attr('disabled', true);
           object.parent().parent().children('td.cell-title').children('button').unbind('click');
           object.fadeIn(400);
           });
  
       }
});


}

function drop (event) {
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    event.target.appendChild(document.getElementById(data));
    document.getElementById(data).style.width = "100%";
    $("#" + data + " span").css("text-align", "left");
    var chargeuser = $("#delegates :selected").val();
    moveAtDragDropfiles(data, mainuser, chargeuser);
    $("#D-drop:after").css("content", " ");
}

function allowDrop (event) {
    event.preventDefault();
}

function drag (event) {
    event.dataTransfer.setData("text", event.target.id);
}

function moveAtDragDropfiles(name, main_usr_id, charge_usr_id){

    $.ajax({ type: "POST",
        url : "../backend/switchfiles.php?fac=" + fac + "&file_name=" + name + "&main_usr_id=" + main_usr_id + "&charge_usr_id=" + charge_usr_id,
        success : function (data){
          console.info("and..." + data);
        }

    })
}

var uploaderInt = function(object, iss_id , usr_id, stsk_id , kind){

if(kind == "internal"){
   var url = '../backend/upload_int.php?fac_id=' + fac + "&stsk=" + stsk_id + "&user=" + usr_id + "&keyfile=" + keyFile;
} else {
    var url = '../backend/upload_for_front.php?fac_id=' + fac + "&iss_id=" + iss_id;
}

uploader =  $(object).pluploadQueue({
        runtimes : 'html5',
        url : '../backend/upload_for_front.php?'  ,
        chunk_size : '1mb',
        unique_names : true,
  filters : {
            max_file_size : '10mb',
            mime_types: [
                {title : "General files", extensions : "jpg,gif,png,pdf,xls,xlsx,docx,doc,txt"},
                {title : "Zip files", extensions : "zip" }
            ]
        },
  preinit : {
            Init: function(up, info) {
                console.log('[Init]', 'Info:', info, 'Features:', up.features);
            },
 
            UploadFile: function(up, file) {

                console.log('[UploadFile]', file);
                up.setOption("url", url);
               // up.setOption('multipart_params', {param1 : 'value1', param2 : 'value2'});
            }
        },
  init : {
            PostInit: function() {
                // Called after initialization is finished and internal event handlers bound
                console.log('[PostInit]');
            },
 
            Browse: function(up) {
                // Called when file picker is clicked
                console.log('[Browse]');
            },
 
            Refresh: function(up) {
                // Called when the position or dimensions of the picker change
                console.log('[Refresh]');
            },
  
            StateChanged: function(up) {
                // Called when the state of the queue is changed
                console.log('[StateChanged]', up.state == plupload.STARTED ? "STARTED" : "STOPPED");
            },
  
            QueueChanged: function(up) {
                // Called when queue is changed by adding or removing files
                console.log('[QueueChanged]');
            },
 
            OptionChanged: function(up, name, value, oldValue) {
                // Called when one of the configuration options is changed
                console.log('[OptionChanged]', 'Option Name: ', name, 'Value: ', value, 'Old Value: ', oldValue);
            },
 
            BeforeUpload: function(up, file) {
                // Called right before the upload for a given file starts, can be used to cancel it if required
                console.log('[BeforeUpload]', 'File: ', file);
                $("#SendRequest-free").attr("disabled", true);
            },
  
            UploadProgress: function(up, file) {
                // Called while file is being uploaded
                console.log('[UploadProgress]', 'File:', file, "Total:", up.total);
            },
 
            FileFiltered: function(up, file) {
                // Called when file successfully files all the filters
                console.log('[FileFiltered]', 'File:', file);
            },
  
            FilesAdded: function(up, files) {
                // Called when files are added to queue
                console.log('[FilesAdded]');
  
                plupload.each(files, function(file) {
                    console.log('  File:', file);
                });
            },
  
            FilesRemoved: function(up, files) {
                // Called when files are removed from queue
                console.log('[FilesRemoved]');
  
                plupload.each(files, function(file) {
                    console.log('  File:', file);
                });
            },
  
            FileUploaded: function(up, file, info) {
                // Called when file has finished uploading
                console.log('[FileUploaded] File:', file, "Info:", info);

            },
  
            ChunkUploaded: function(up, file, info) {
                // Called when file chunk has finished uploading
                console.log('[ChunkUploaded] File:', file, "Info:", info);
            },
 
            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                   console.log("reponse", files);
            },
 
            Destroy: function(up) {
                // Called when uploader is destroyed
                console.log('[Destroy] ');
            },
  
            Error: function(up, args) {
                // Called when error occurs
                console.log('[Error] ', args);
            }
        } // init

    });

};


function intDel(user, sub, des, date, ind, mst){

console.info(ind);
var pre_fecha  = new Date();
var fecha = pre_fecha.getFullYear() + "-" + ('0' + (pre_fecha.getMonth()+1)).slice(-2) + "-" +
 ('0' + pre_fecha.getDate()).slice(-2) + " " + ('0' + pre_fecha.getHours()).slice(-2) + ":" + ('0' + pre_fecha.getMinutes()).slice(-2)  + ":" + ('0' + pre_fecha.getSeconds()).slice(-2) ;

  $.ajax({
          type: "POST",
          url: "../backend/delegate_internal.php?muser=" + $("#muser").val() + 
          "&user=" + user + 
          "&fechaF=" + date + 
          "&subject=" + sub + 
          "&descript=" + des + 
          "&startD=" + fecha  + 
          "&fac="+ fac +
          "&main_stsk=" + mst + 
          "&keyfile=" + keyFile, 
          success : function (data){
           result = data.split("|");
          console.log(data);
                   bootbox.alert("Su requerimiento ha sido generado existosamente", function(){
                         $("#del-int-req").removeClass('active in');$("#int-require").addClass('active in');
                         if (mode != "first"){
                              assoc_collar_int(user, ind);
                         } else {
                            firstTask(result[0], des, result[1] , date, user);
                         }
                     });
                   $("#del-int-req input, #del-int-req textarea").val('');
          }
  })

}

//historial de eventos 

$(".events").on('click', function(){

  //get the Classes by ID 
  // cambio de fotos
 var ucla =  $(this).parent().prev().prev().children('a').children('input');

   for (i=0; i < ucla.length; i++){

    console.info(ucla.eq(i).val());
       $("." + ucla.eq(i).val()).css({ display: "table-row"});
   }


  var primary = $(this).parent().parent().parent().prev().children('input').eq(0).val();

    $("#require").removeClass("active in");
        $("#events").addClass("active in");

        if($(".st" + primary).length == 0){
          
             $("#events .task").css({display : 'none'});
        }
           else {

                $(".htd" + primary).css({display: "table-row"});
                $(".st" + primary).css({display: "table-row"});
                $("#back-to-main").data("val", primary);

           }          
});

$("#back-to-main").click(function(){
    $(".st" + $(this).data("val") ).css({display: "none"});
      $(".utrf").css({ display: "none"});
      $("#events .task").css({display : 'none'});
        $("#events").removeClass("active in");
          $("#require").addClass("active in");
});


$(".stusr").click(function(){

var indicator = $(this).index('.stusr');

console.info(indicator);

if (um == 0){

    $(this).parent().children('.media-body').fadeOut(400, function(){
        $(".stusr").eq(indicator).parent().children("div.media").children('div.wc').fadeIn(400);
    });

    um  = 1;
} else {
    
   $(this).parent().children("div.media").children("div.wc").fadeOut(400, function(){
        $(".stusr").eq(indicator).parent().children(".media-body").fadeIn(400);
    });
    um = 0;
}
});

    var Notification = window.Notification || window.mozNotification || window.webkitNotification;

    Notification.requestPermission(function (permission) {
        // console.log(permission);
    });

    function showAlert(message, type, usr_name) {
if(type == "req"){

var title = "Te ha llegado un nuevo requerimiento:";
var iconShow = "https://cdn4.iconfinder.com/data/icons/meBaze-Freebies/512/alert.png";

} else {

var title = usr_name + " ha marcado un progreso :";
var iconShow = "http://icons.iconarchive.com/icons/visualpharm/must-have/256/Next-icon.png";
}
        var instance = new Notification(
            title , {
                body: message,
                icon : iconShow
            }
        );

        instance.onclick = function () {
            // Something to do
        };
        instance.onerror = function () {
            // Something to do
        };
        instance.onshow = function () {
            if( type == "req"){
               $('#chatAudio')[0].play(); 
           } else {
               $('#successAudio')[0].play();
           }
          
        };
        instance.onclose = function () {
            // Something to do
        };

        return false;
    }


    setInterval(function(){
        $.ajax({
            type: "POST",
            url: "../backend/time.php?usr="+mainuser,
            success: function(data){
                packets = data.split("|");
                 if(parseInt(packets[0]) !== 0 ){
                       showAlert(packets[2], "pro", packets[0]);
                       collection = $("input.st");
                       indice = $("input.st[value=" + packets[5] + "]").index(".st");
                       updateProgress(packets[2], packets[3], packets[6], packets[4], packets[1], packets[0], indice, packets[5] );
                       if(parseInt(packets[8]) >= 99.5){
                           $(".collaborates").eq(indice).children(".hovetip").children("input[value=u" + packets[4] +"]").prev().css({ opacity : "1"});

                       }

                 }
            }
        });
    }, 3000);
    
if(typeof(EventSource) !== "undefined") {
    var source     = new EventSource("../backend/sse-event.php?usr=" + mainuser);
    source.onmessage = function(event) {
       var eventMessage = event.data.split('\n');
       if (eventMessage[0] !== previuosData){
        console.info( eventMessage[0] + "/" + previuosData);
        showAlert(eventMessage[0], 'req');
        inputTask(eventMessage[0], eventMessage[1], eventMessage[3], eventMessage[4], eventMessage[2]);
        previuosData = eventMessage[0];
    } 
}
} else {

    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";

}


function assoc_collar_int(usr, ind){

var parent = document.querySelectorAll('.coll-int')[ind];

  var string =  '<a href="#" class="hovertip" title="">' +
        '<img src="../' + fac + '/img/'  + usr + '_opt.jpg" class="group" >' +
        '<input type="hidden" value="u'  + usr + '>">' +
        '</a>';
  var stringAl   = parent.innerHTML + string;   
parent.innerHTML = stringAl;   

}

function inputTask(stsk_descript, stsk, iss, ctz, desc){

    var parent =  document.querySelector("#ext-tasks-table tbody");

    var tr1 = document.createElement('tr');
    tr1.className = "task Ec" ;

    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td5 = document.createElement('td');

    var inp1 = document.createElement('input');
    var inp2 = document.createElement('input');

    var b   = document.createElement('b');
    var btn = document.createElement('button');
    
    td1.className = "cell-icon";
    td2.className = "cell-title";
    td3.className = "cell-status";
    td4.className = "cell-title";
    td5.className = "cell-time align-right";

    btn.className   = "btn btn-small forward";
    btn.innerHTML   = "Delegar";
    td4.appendChild(btn);  
   
    td2.innerHTML = stsk_descript;

    inp1.type = "hidden";
    inp2.type = "hidden";

    inp1.value = stsk;
    inp2.value = iss;

    inp1.className = "st";
    inp2.className = "iss_id";
    
    b.className = "due";
    b.style.backgroundColor = "#178FD0";
    b.innerHTML = "EN CURSO";
    td3.appendChild(b);


    var is = document.createElement('i');
    is.className    = "fa fa-warning";
    is.style.color  = "#EE8817";
    is.style.cursor =  "pointer"
    td1.appendChild(is);


    is.onclick = function (){

          var stsk   = $(this).parent().parent().children('input').eq(0).val();
          var iss_id = $(this).parent().parent().children('input').eq(1).val();
 

       bootbox.confirm("Esta seguro de cerrar este requerimiento?", function (confirmation){
        if(confirmation){
            unlock(stsk, iss_id, $(this)); 
        }
       })
    }

    b.onclick = function(){
        if(!$(this).data("val") || !$(this).data("val") === 0 ){
             $(this).parent().parent().next().css({ display: "table-row"});
             $(this).data("val", 1);
       } else  {
         $(this).parent().parent().next().css({ display: "none"});
        $(this).data("val", 0);
        }
    }


    btn.onclick = function(){

            var stsk_id = $(this).parent().parent().children('input.st').val();
            var iss_ident = $(this).parent().parent().children('input.iss_id').val();
            var subject = $(this).parent().parent().children('td').eq(1).text();
            var index_current = parseInt($(this).index());

            $("#audititle").html("\"" + stsk_descript + "\"");
            $("#current-task").val(index_current);

            $(".ifile").css({display : "none"});
            $(".iss" + iss_ident).css({ display : "inline-block"});

             $("#issId").val(iss_ident);
             $("#stsk-code").val(stsk_id);

                $('#delegates option:first-child').attr("selected", "selected");

                var current = $("#delegates").val();

                    $("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
                    $("#require").removeClass('active in');$("#tasks-own").addClass('active in');

};

// callback function

   $.ajax({
          type: "POST",
          url: "../backend/dynamics_JSON_files.php?usr_id=" + mainuser + "&iss_id=" + iss + "&fac=" + fac,
          success: function(data){
    
      var files  =  data.split("|");

      var elem   = [];
      var elem_i = [];
      var elem_s = [];
      var setClass ="";
      var cor= "";
      var fileParent = document.querySelector('.incoming-files');

      for (n=0; n < files.length-1 ; n++){
      
         elem[n]           = document.createElement('p');
         elem[n].className = "ifile iss" + iss;
         elem[n].id        = files[n];
         elem[n].setAttribute("draggable", true);
 
         elem[n].ondragstart = function(event){
                 drag(event);
          }

        var extension = files[n].substring(files[n].length -3 , files[n].length);
              switch(extension){
                case "pdf": 
            setClass = "pdf-o";
            cor = "#FA2E2E";    
        break;
                case "lsx":
            setClass = "excel-o";
            cor = "#44D933";
        break;
                case "ocx":
            setClass = "word-o"; 
            cor = "#5F6FE0";
        break;
                case "doc":
            setClass = "word-o"; 
            cor = "#5F6FE0";
        break;
                case "xls":
            setClass = "excel-o";
        break;
                case "zip":
            setClass = "zip-o";
            cor = "#DDCE62";
        break;
                case "png" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "jpg" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "gif" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "bmp" : 
            setClass = "picture-o";
            cor = "#338B93";
        break;     

    }
        
         elem_i[n]             = document.createElement('i');
         elem_i[n].className   = "fa fa-file-" + setClass + " fa-2x";
         elem_i[n].style.color = cor;
         
         elem_s[n]           = document.createElement('span');
         elem_s[n].className = "iname";
         elem_s[n].innerHTML = files[n];

         elem[n].appendChild(elem_i[n]);
         elem[n].appendChild(elem_s[n]);

         fileParent.appendChild(elem[n]);
      }
}
});
    
    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(inp1);
    tr1.appendChild(inp2);

    parent.appendChild(tr1);
// second tr

    var tr2  = document.createElement('tr');
    var td6  = document.createElement('td');

    var div1 = document.createElement('div');
    var div2 = document.createElement('div');
    var div3 = document.createElement('div');
    var div4 = document.createElement('div');

    var i1   = document.createElement('i');
    var i2   = document.createElement('i');
    var i3   = document.createElement('i');
    var i4   = document.createElement('i');
   
    var p1   = document.createElement('p');
    var p2   = document.createElement('p');
    var p3   = document.createElement('p');
    var p4   = document.createElement('p');
    var p5   = document.createElement('p');
   
    var str1 = document.createElement('strong');
    var str2 = document.createElement('strong');

    tr2.className = "display-progress";
    td6.colSpan = "5";
    div1.className = "info-content";

    p1.className = "iss-descript";
    p2.className = "iss-descript";

    str1.innerHTML = "Ciudadano : " + ctz;
    str2.innerHTML = "Descripción: " + desc;

    var str3  = document.createElement('strong');
    var span1 = document.createElement('span');

    str3.innerHTML  = "Grado de progreso";
    span1.innerHTML = "0%";
    span1.className = "pull-right small muted";
    div2.className  = "progress tight";
    div3.className  = "bar bar-warning";
    div4.className  = "collaborates";
    

    i1.className = "fa fa-group spac";
    i2.className = "fa fa-paperclip";
    i3.className = "fa fa-calendar-o events";
    i4.className = "fa fa-group spac";


    p4.className = "golang";
    p5.className = "wrap-events";

    p1.appendChild(str1);
    p2.appendChild(str2);
    p3.appendChild(str3);
    p3.appendChild(span1);
    p4.appendChild(i2);
    p5.appendChild(i3);
    div4.appendChild(i4);

    div1.appendChild(p1);
    div1.appendChild(p2);
    div2.appendChild(div3);

    td6.appendChild(div1);
    tr2.appendChild(td6);
    td6.appendChild(p3);
    td6.appendChild(div2);

    td6.appendChild(div4);
    td6.appendChild(i2);
    td6.appendChild(i3);
    td6.appendChild(p4);
    td6.appendChild(p5);
    parent.appendChild(tr2);
}


function getFiles(iss_id, usr_id, callback){
var files;
   $.ajax({
          type: "POST",
          url: "../backend/dynamics_JSON_files.php?usr_id=" + usr_id + "&iss_id=" + iss_id+ "&fac=" + fac,
          success: function(data){
          files = data;
          callback(files);
          }
   })
};


function updateProgress(subject, descript, percent, date, userId, usr_name, ind, stsk){

console.log(subject + ","  + descript + ","  + percent + "," +  date + "," +  userId + "," +  usr_name + ","  + ind + "," + stsk);

document.querySelectorAll("#ext-tasks-table .bar")[ind].style.width = percent + "%";
document.querySelectorAll("#ext-tasks-table p > span.muted")[ind].innerHTML = percent + "%";

var parent = document.querySelector("#del-partners");

var tr_av  = document.createElement('tr');
var td1_av = document.createElement('td');
var td2_av = document.createElement('td');
var td3_av = document.createElement('td');

td1_av.innerHTML = subject;
td2_av.innerHTML = descript;
td3_av.innerHTML = date;

tr_av.appendChild(td1_av);
tr_av.appendChild(td2_av);
tr_av.appendChild(td3_av);


$.ajax({ type:"POST",
         url: "../backend/files_back_to_admin.php?fac=" + fac +  "&user=" + userId + "&stsk=" + stsk,
         success : function (data){

            files = data.split("|");

        for (n=0; n < files.length-1 ; n++){

        var extension = files[n].substring(files[n].length -3 , files[n].length);
              switch(extension){
                case "pdf": 
            setClass = "pdf-o";
            cor = "#FA2E2E";    
        break;
                case "lsx":
            setClass = "excel-o";
            cor = "#44D933";
        break;
                case "ocx":
            setClass = "word-o"; 
            cor = "#5F6FE0";
        break;
                case "doc":
            setClass = "word-o"; 
            cor = "#5F6FE0";
        break;
                case "xls":
            setClass = "excel-o";
        break;
                case "zip":
            setClass = "zip-o";
            cor = "#DDCE62";
        break;
                case "png" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "jpg" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "gif" : 
            setClass = "picture-o";
            cor = "#338B93";
        break; 
                case "bmp" : 
            setClass = "picture-o";
            cor = "#338B93";
        break;
                case "ptx" : 
            setClass = "powerpoint-o";
            cor = "#A80B9C";
        break;

    }

      var sshot =  document.querySelectorAll(".file-contents")[ind].innerHTML;
      strHtml   =  sshot + '<a href="../' + fac + '/' + userId + '_in/' + files[n] + '" download>' +
      '<p class="ifile" title="' + files[n] + '"><i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor+ ';"></i>'
      '<span class="iname"></span></p></a>';
      document.querySelectorAll(".file-contents")[ind].innerHTML = strHtml;

      }

    }
})




// search the user;

var search1 = document.querySelectorAll(".u" + userId)[0];

    if(!search1){
    // create user .. pfffff... no comments.


        tr_usr   = document.createElement('tr');
        td_usr   = document.createElement('td');
        div_usr1 = document.createElement('div');
        div_usr2 = document.createElement('div');
        a_usr    = document.createElement('a');
        img_usr  = document.createElement('img');
        p_usr    = document.createElement('p');

        tr_usr2   = document.createElement('tr');
        td_usr1   = document.createElement('td');
        td_usr2   = document.createElement('td');
        td_usr3   = document.createElement('td');

        span_usr1 = document.createElement('span');
        span_usr2 = document.createElement('span');
        span_usr3 = document.createElement('span');

  // style and attr assigments

       tr_usr.className = "u" + userId + " utrf";
       td_usr.colSpan = 3;
       div_usr1.className = "user-schedule";
       div_usr2.className = "media";
       div_usr2.style.display = "inline-block";
       a_usr.className = "media-avatar pull-left";
       a_usr.style.width = "4em";
       a_usr.style.height = "4em";
       a_usr.title = usr_name;
       img_usr.src = "../" + fac + "/img/" + usrId + ".jpg";
       img_usr.style.width = "100%";
       img_usr.style.height = "100%";

       p_usr.style.fontSize = "2em";
       p_usr.style.fontStyle = "italic";
       p_usr.style.color = "gray";
       p_usr.style.display = "inline-block";
       p_usr.style.verticalAlign = "bottom";
       p_usr.innerHTML = usr_name;

       tr_usr2.className = "task u" + usrId + "chrono";
       span_usr1.className = "bolder";
       span_usr2.className = "bolder";
       span_usr3.className = "bolder";

       td_usr3.className = "cell-time align-right";

       span_usr1.innerHTML = "Asunto";
       span_usr2.innerHTML = "Descripcion";
       span_usr3.innerHTML = "Fecha Progreso";



  // sorting of appending elements to display;

      a_usr.appendChild(img_usr);
      div_usr2.appendChild(a_usr);
      div_usr1.appendChild(div_usr2);
      div_usr1.appendChild(p_usr);
      td_usr.appendChild(div_usr1);
      tr_usr.appendChild(td_usr);
    
      td_usr1.appendChild(span_usr1);
      td_usr2.appendChild(span_usr2);
      td_usr3.appendChild(span_usr3);

      tr_usr2.appendChild(td_usr1);
      tr_usr2.appendChild(td_usr2);
      tr_usr2.appendChild(td_usr3);
    


      parent.appendChild(tr_usr);
      parent.appendChild(tr_usr2);
      parent.appendChild(tr_av);


      parent.insertBefore(tr_usr, tr_usr2);
      insertAfter(tr_av, tr_usr2);
      
    } else {
        pseudoparent =  document.querySelector("#del-partners");
        pseudoparent.appendChild(tr_av);
    }

}


function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function touchHandler(event) {
    var touch = event.changedTouches[0];

    var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent({
        touchstart: "mousedown",
        touchmove: "mousemove",
        touchend: "mouseup"
    }[event.type], true, true, window, 1,
        touch.screenX, touch.screenY,
        touch.clientX, touch.clientY, false,
        false, false, false, 0, null);

    touch.target.dispatchEvent(simulatedEvent);
    event.preventDefault();
}

function init() {
   window.addEventListener("touchstart", touchHandler, true);
   window.addEventListener("touchmove", touchHandler, true);
   window.addEventListener("touchend", touchHandler, true);
   window.addEventListener("touchcancel", touchHandler, true);
}

function firstTask(stsk_ident, descript, user_name, date, user_id){

    var parent_int =  document.getElementById("int-body");

    var tr1 = document.createElement('tr');
    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td5 = document.createElement('td');
    var i1  = document.createElement('i');
    var i2  = document.createElement('i');
    var b1  = document.createElement('b');
    var inp1 = document.createElement('input');


    td1.className = "cell-icon int-lock";
    tr1.className = "task Ec-int";
    td3.className = "cell-status";
    td2.innerHTML = descript;
    td5.innerHTML = date;
    inp1.value    = stsk_ident;
    inp1.type     = "hidden";
    td4.className = "int-forward";
    b1.innerHTML = "En Curso";
    b1.className  = "due int-desglo"; 
    b1.style.backgroundColor = "#178FD0";

    i1.className = "fa fa-exclamation";
    i1.style.color = "orange";
    i2.className = "fa fa-chevron-circle-right";
    
    td1.appendChild(i1);
    td4.appendChild(i2);
    td3.appendChild(b1);


    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(inp1);

   //events

i2.onclick = function(){

 mode = "delegate";
 var indice = $(this).index();
 var ids    = $(this).parent().children('input').val();

$("#del-int-req").data("val",indice );
$("#send-int").data("val", ids);
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');
}

b1.onclick = function (){

if(!$(this).data("val") || !$(this).data("val") === 0 ){

   $(this).parent().parent().next().css({ display: "table-row"});
     $(this).data("val", 1);
} else  {

  $(this).parent().parent().next().css({ display: "none"});
   $(this).data("val", 0);
}
}

td1.onclick = function (){

    var obj = $(this).children('i');
    var stsk_int = $(this).parent().children('input').val();
    bootbox.confirm("Esta seguro de cerrar este requerimiento?", function (confirmation){
    if (confirmation){
           unlock(stsk_int, "" , obj);
    }
  });
}


var tr2 = document.createElement('tr');
var td_i1 = document.createElement('td');
var p = document.createElement('p');
var strong = document.createElement('strong');
var span = document.createElement('span');
var div1 = document.createElement('div');
var div2 = document.createElement('div');
var div3 = document.createElement('div');
var a    = document.createElement('a');
var img  = document.createElement('img');
var inp2 = document.createElement('input');

tr2.style.display = "none";
span.className = "pull-right small muted";
div1.className = "progress tight";
div2.className = "bar bar-warning";
div3.className = "coll-int";
div3.style.width = "100%";
strong.innerHTML = "Grado de progreso";

a.href = "#";
a.className = "hovertip";
a.title = user_name;

img.src ="../" + fac + "/img/" + user_id + "_opt.jpg";
img.className ="group";

inp2.type= "hidden";
inp2.value = "u" + user_id;

td_i1.colSpan = "5";

p.appendChild(strong);
p.appendChild(span);

div1.appendChild(div2);
a.appendChild(img);
a.appendChild(inp2);

div3.appendChild(a);

td_i1.appendChild(p);
td_i1.appendChild(div1);
td_i1.appendChild(div3);
tr2.appendChild(td_i1);

parent_int.appendChild(tr1);
parent_int.appendChild(tr2);

insertAfter(tr2, tr1);
  
  

}


function AmericanDate(date){
  var subs = date.substring(6) + "/"  + date.substring(3, 5) + "/" + date.substring(0, 2);
return subs;
}

document.getElementById("int-del").addEventListener("change" , function(){
       user_send = this.value;
       console.info(user_send);

       if ( mode == "first"){

           keyFile = RandomString(8);
       } else {
          keyFile = "";
       }

       uploaderInt($("#up-int"), "", user_send, stsk_send , "internal", keyFile); 

})

function RandomString(length) {
    
    var str = '';
    for ( ; str.length < length; str += Math.random().toString(36).substr(2) );
    return str.substr(0, length);
}

</script>

<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>









