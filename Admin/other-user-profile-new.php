<?php ini_set('session.gc_maxlifetime', 27000);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(27000);
session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'admin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

$Query_team       = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'back-user' AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "') ORDER BY USR_ID;");
$Query_subtask    = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_DESCRIP, B.EST_DESCRIPT, A.STSK_FINISH_DATE, B.EST_COLOR, A.STSK_PROGRESS, A.STSK_LOCK, A.STSK_TICKET, A.STSK_RESP FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 0 AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " ) ORDER BY STSK_FINISH_DATE " );
$Query_alerts_ext = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_TYPE = 0 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . ") GROUP BY STSK_STATE");
$Query_alerts_int = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_MAIN_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1) GROUP BY STSK_STATE");
$Query_alerts_ii  = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_LOCK = 1 AND STSK_TYPE = 1) GROUP BY STSK_STATE");

$str_trf_usr      = "SELECT DISTINCT A.TRF_USER, CONCAT(B.USR_NAME , ' ' ,  B.USR_SURNAME) FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE (TRF_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "') ORDER BY TRF_USER; ";
$Query_trf_usr    = mysqli_query($datos, $str_trf_usr);
$Query_team_int   = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_DEPT = '" . $_SESSION['TxtDept'] . "') UNION SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'admin'  AND USR_DEPT != '" . $_SESSION['TxtDept'] . "');");
// internal requirements

$query_internal = "SELECT A.STSK_ID, A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE, A.STSK_ISS_ID, A.STSK_TICKET FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_MAIN_USR = " . $_SESSION['TxtCode'] . " AND STSK_MAIN_USR = STSK_CHARGE_USR )  ORDER BY STSK_FINISH_DATE";
$internal       = mysqli_query($datos, $query_internal);
$quntum         = mysqli_query($datos, "SELECT COUNT(STSK_ID) AS CONTADOR FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode']);

$vlist = "Mi Departamento,";

while ($Qth_list = mysqli_fetch_row( $Query_team)){
  $vlist .= str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($Qth_list[1] . " " . $Qth_list[2] )))) . ",";
}

mysqli_data_seek($Query_team, 0);

if(mysqli_num_rows($quntum) == 0){

    $contador = 0;
} else {
    $cont = mysqli_fetch_assoc($quntum);
    $contador = $cont['CONTADOR'];
}

$intList = "Mi Departamento, Jefaturas,";

while ($Qth_int_list = mysqli_fetch_row( $Query_team_int)){
  $intList .= str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($Qth_int_list[1] . " " . $Qth_int_list[2] )))) . ",";
}

mysqli_data_seek($Query_team_int, 0);


$str_query = "SELECT A.STSK_DESCRIP, A.STSK_ID, B.ISS_DESCRIP, B.ISS_ID , CONCAT(C.CTZ_NAMES, ' ' , C.CTZ_SURNAME1, ' ' , C.CTZ_SURNAME2 ) AS NAME " .
"FROM `SUBTASKS` A " .
"INNER JOIN `ISSUES` B ON(A.STSK_ISS_ID = B.ISS_ID) " .
"INNER JOIN `CITIZENS` C ON(B.ISS_CTZ = C.CTZ_RUT ) WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " ORDER BY STSK_ID DESC LIMIT 1";
$notify = mysqli_fetch_assoc(mysqli_query($datos, $str_query));
if(!$notify){

    $manu = "";
} else {

    $manu = $notify['STSK_DESCRIP'];
}

$query_incoming = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_MAIN_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE, A.STSK_ISS_ID FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR <> STSK_MAIN_USR)");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eque-e</title>
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="../css/theme.css" rel="stylesheet">  
    <link type="text/css" href="../css/uploader_style.css" rel="stylesheet" />
    <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
    <link type="text/css" href="../css/slider.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/jquery.plupload.queue.css" type="text/css" media="screen" />
    <link type="text/css" href="../scripts/dist/css/selectize.css" rel="stylesheet">
    <link type="text/css" href="../css/typeahead.js-bootstrap.css" rel="stylesheet">
    <link type="text/css" href="../scripts/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
    <link type="text/css" href="../css/style_radial.css" rel="stylesheet">
    <style type="text/css">
.done{background:#daedb1;background:-moz-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#daedb1),color-stop(28%,#abd78d),color-stop(100%,#54ca50))!important;background:-webkit-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-o-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-ms-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:linear-gradient(to bottom,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#daedb1',endColorstr='#54ca50',GradientType=0)!important}.warning{background:#fefcea;background:-moz-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fefcea),color-stop(0%,#fefcea),color-stop(26%,#f1da36))!important;background:-webkit-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-o-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-ms-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:linear-gradient(to bottom,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fefcea',endColorstr='#f1da36',GradientType=0)!important}.delay{background:#ff5335;background:-moz-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ff5335),color-stop(100%,#d00e04));background:-webkit-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-o-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-ms-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:linear-gradient(to bottom,#ff5335 1%,#d00e04 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5335',endColorstr='#d00e04',GradientType=0)}.OwnComp{width:100%}.OwnComp-bars{background-color:#FFF;width:100%;margin:.5em;border:4px solid transparent;padding:1em 1.5em;width:80%}#Urgent-Display,#Audi-Display,#Com-Display{height:0;visibility:hidden;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.sub-del{width:55%;display:inline-block;vertical-align:top}#delegates{width:100%;position:relative;float:left}.require-subtasks{padding:0 1em;margin:.5em}#st-description{width:95%}.attach{display:none;vertical-align:top}.file-contents, .file-sent{width:80%}.file-contents,.file-contents p{display:inline-block;vertical-align:top}.display-progress{display:none}.At-int-ii{display:table-row}.Ec-int-ii,.Hc-int-ii,.Pe-int-ii,.Pv-int-ii{display:none}.At-int{display:table-row}.Ec-int,.Hc-int,.Pe-int,.Pv-int{display:none}.At{display:table-row}.Ec,.Hc,.Pe,.Pv{display:none}.ifile, .ifile-ii{margin:.5em;display:inline-block;vertical-align:top;cursor:pointer}.iname{display:block;text-align:left}#wrap-D{display:inline-block;max-height:20em}.toggle-attach{float:right;background-color:gray;border-radius:15px}.toggle-attach i{color:#fff;padding:.2em}#D-drop{height:20em;width:20em;float:right;background-color:#fff;border-radius:20px;border:1px orange solid;overflow-y:auto;overflow-x:hidden}#D-drop:after{content:"Arrastre aqui sus archivos";color:gray;position:relative;top:8em;left:2em;font-style:italic;font-size:1.3em}.attach,#wrap-D{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.after:after{content:"Arrastre aqui sus archivos"}.no-after:after{content:""}.collaborates{width:80%}.collaborates p{display:inline-block;vertical-align:top;font-size:.8em;font-style:italic}#audititle{font-style:italic;color:gray;width:100%}#wrapaudi{display:block;width:100%}.incoming-files{display:none}#froback{position:relative;float:right;color:#a9a9a9;font-style:italic}.spac{margin-right:.3em;color:#1e5799;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e5799',endColorstr='#207cca',GradientType=0);-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.golang i,.spac{font-size:1.5em}.golang, .wrap-events{display:inline-block;vertical-align:top}.info-content{width:100%}.iss-descript{font-style:italic;font-size:.7em;display:inline-block;vertical-align:top}.events{color:#24B56C;font-size:1.5em}.wrap-events{width:auto;margin:0 .5em}.chrono{display:none}#back-to-main i{cursor:pointer}#back-to-main i:hover{color:#90ee90}.user-schedule{width:100%;height:auto}.wrap-charts{display:none}strong{font-size:.8em}.progressDisplay li{padding:5px}.utrf{display:none}.bolder{font-weight:bolder}.group{width:8%;border-radius:50%;padding:6px;border:1px solid #d3d3d3;border-radius:50%;display:inline-block;vertical-align:top;-webkit-transition:all 100ms ease-in-out;-moz-transition:all 100ms ease-in-out;transition:all 100ms ease-in-out}.group:hover{border:1px solid orange;width:10%}#descript-int{width:100%;}.af{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.af:hover{color:#F70202;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}

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

.trf-int-usr{
    -webkit-transition:all 600ms ease-in-out;
    -moz-transition:all 600ms ease-in-out;
    transition:all 600ms ease-in-out
}

.trf-int-usr:hover{
    background-color: lightgrey;
    -webkit-transition:all 600ms ease-in-out;
    -moz-transition:all 600ms ease-in-out;
    transition:all 600ms ease-in-out

}

.tt-selectable, .padlock{
  background-color:#FFF;
    -webkit-transition:all 600ms ease-in-out;
    -moz-transition:all 600ms ease-in-out;
    transition:all 600ms ease-in-out
}

.padlock{
  cursor:pointer;
  color:gray;
  border: 2px solid white;
      -webkit-transition:all 600ms ease-in-out;
    -moz-transition:all 600ms ease-in-out;
    transition:all 600ms ease-in-out
}
.padlock:hover{
  font-size: 2.5em;
  color:#3CF96B;
  border:2px solid #3CF96B;
  border-radius: 50%;
    -webkit-transition:all 400ms ease-in-out;
    -moz-transition:all 400ms ease-in-out;
    transition:all 400ms ease-in-out;
    padding: 9px 16px;
}
.file-opac{
    -webkit-transition:all 400ms ease-in-out;
    -moz-transition:all 400ms ease-in-out;
    transition:all 400ms ease-in-out;
}
.wrap-lock{
  display: inline-block;
  vertical-align: top;
  float: right;
  position: relative;
  right: 1em;
}

#outer-dropzone {
  height: 140px;
}

#inner-dropzone {
  height: 80px;
}

.dropzone {
  background-color: #ccc;
  border: dashed 4px transparent;
  border-radius: 4px;
  margin: 10px auto 30px;
  padding: 10px;
  width: 80%;
  transition: background-color 0.3s;
}

.drop-active {
  border-color: #aaa;
}

.drop-target {
  background-color: #29e;
  border-color: #fff;
  border-style: solid;
}

.drag-drop {
  display: inline-block;
  min-width: 40px;
  padding: 2em 0.5em;

  color: #fff;
  background-color: #29e;
  border: solid 2px #fff;

  -webkit-transform: translate(0px, 0px);
          transform: translate(0px, 0px);

  transition: background-color 0.3s;
}

.drag-drop.can-drop {
  color: #000;
  background-color: #4e4;
}

.display-pro-int{
    display:none;
}

@media screen and (max-width: 1024px) {
  .slider-horizontal{
       margin: 0 20%;
    }

}

@media screen and (max-width: 640px) {
  .slider-horizontal{
       margin: 0 10%;
    }

}
@media screen and (max-width: 500px) {
  .slider-horizontal{
       margin: 0 5%;
    }

}

  .slider-horizontal{
       margin: 0 25%;
    }

.ex-del-par > tbody > tr{
  display:none;
}

.g-wrap{
  width:100%;
}

.great-chart{
  display: inline-block;vertical-align: top;
    position: relative;
  top: -3em;
}
.person-sw{
  position:relative;
  display: inline-block;
  vertical-align: top;
}

pre > a {
  display: inline-block;
  vertical-align: top;
}
pre > a, pre > a > p{
  max-width: 3em;
  max-height: 3em;
}

.file-contents:before, .front-sent:before {
content: "\f053";
font-size: 2em;
color: lightblue;
font-family: 'FontAwesome';
position: relative;

}
.file-sent:before, .front-received:before{
content: "\f054";
font-size: 2em;
color: lightgreen;
font-family: 'FontAwesome';
position: relative;
top:.5em;
 }


 svg{
  width:100px;
 }

 .front-sent:before,
.front-received:before{
  top:0;
}

 .front-sent,
.front-received{
margin-left: 1.5em;
display:flex;
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
                                    <a href="#" class="media-avatar pull-left" >
                                        <img src="../<? echo $_SESSION['TxtFacility']?>/img/<? echo $_SESSION['TxtCode']?>_opt.jpg" >
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION["TxtUser"]) ?> <? printf($_SESSION["TxtPass"]) ?><small>Online</small>
                                        </h4>
                                        <p class="profile-brief">
                                         <? printf($_SESSION['TxtPosition']) ?> En <? echo $_SESSION['TxtFacName'] ?>.
                                        </p>
                                        <div class="profile-details muted">
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
                                                                <small class="muted"></small></p>
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
                                                                                case 'En Curso':                                                                             
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
                                                                                case 'En Curso':                                                                             
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
                                        <button class="btn" id="showtitle">Atrasados</button>
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
                 <?  while($fi = mysqli_fetch_row($Query_alerts_ext)){ 
                                       
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
                                            $Tuba = "Atrasado";
                                          break;
                                          case 5:
                                             $type = "fa-check-circle";
                                             $taint = "#1CC131";
                                             $tuba = "Finalizado";
                                          break;

                                       }

                                    ?>
                                      
<a class="btn Qext" title="<? echo $tuba ?>"><p style="display: inline-block; vertical-align: top;color: <? echo $taint ?>; font-size: 1.5em; font-weight: 800;" ><? echo $fi[0] ?></p>
<i class="fa <? echo $type ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? echo $taint ?>"></i>
</a> 

<? } ?>
                                   
                                </div>
                            </div>
                            <div class="starter" style="width:100%;">
                                        <input type="text" value="" placeholder="Búsqueda" id="search1" style="width: 36em; float: left;margin-left: 1em;">
                                        <input type="text" id="dfrom" class="seo" placeholder="Desde" style="width: 10em; margin: 0 .5em; vertical-align:top;">
                                        <input type="text" id="duntil" class="seo" placeholder="Hasta" style="width: 10em; margin: 0 .5em; vertical-align:top;">
                            </div>
                        <div>
                            <div class="module-body table">                             
                                <table class="table table-message" id="ext-tasks-table">
                                    <tbody>
                                        <tr class="heading">
                                            <td class="cell-icon"><i class="fa fa-lock" style="color: white;"></i></td>
                                            <td class="cell-title">Requerimiento</td>
                                            <td class="cell-status">Status</td>
                                            <td class="cell-title" style="min-width: 80px;">Accion</td>
                                            <td class="align-right">Fecha máxima de entrega</td>
                                        </tr>
                                        <? 

                                        $class = "";
                                        $situation = "";
                                        $color = "";
                                        $lock = "";

                                        while ($stsk = mysqli_fetch_row($Query_subtask)){ 
                                         
                                         if($stsk[7] == 0 || $stsk[7] == '0'){

                                            $color = "color:#EE8817;";
                                            $lock = "";

                                         } else {
                                            $color = "color: #44D933;";
                                            if(($stsk[9] == 1 || $stsk[9] == 2) && $stsk[3] != "Finalizada"){
                                                $lock = "";
                                            } else {
                                                 $lock = "disabled";
                                            }
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
                                            ?> 
                                        <tr class="task <? printf($class) ?>">
                                            <td class="cell-icon"><? echo $stsk[8] ?></td>
                                            <td class="cell-title"><? printf($stsk[2])  ?></td>
                                            <td class="cell-status">
                                            <b class="due" style="background-color: <? printf($stsk[5]) ?>;"><? printf($stsk[3]) ?></b></td>
                                            <td class="cell-title" style="min-width: 80px;"><button it="" class="btn btn-small forward" <? printf($lock) ?> style="margin-right: 1em"><i class="fa fa-chevron-circle-right"></i></button>
                                          <? if ($stsk[9] == 1  ) { ?>
                                                   <i it="<? echo $stsk[9]  ?>" class="fa fa-user spac"></i>
                                                   <i class="fa fa-search viewToggle" style="color: lightblue; font-size: 1.5em"></i>

                                          <?  } elseif ($stsk[9] == 0 ) {    ?>

                                                    <i it="<? echo $stsk[9]  ?>" class="fa fa-group spac"></i>
                                                    <i class="fa fa-search viewToggle" style="color: lightblue; font-size: 1.5em"></i>

                                          <?  } else { ?>

                                                   <div class="person-sw" it="<? echo $stsk[9] ?>">
                                                      <i class="fa fa-user spac" ></i>
                                                      <input type="checkbox" class="swt-boo" checked="true"  data-label-width="3" data-size="mini" data-on-color="info"  data-on-color="default" data-on-text="&ensp;" data-off-text="&ensp;">
                                                      <i class="fa fa-group spac" style="color: gray;" ></i>
                                                   </div>
                                                   <i class="fa fa-search viewToggle" style="color: lightblue; font-size: 1.5em"></i>

                                            <?   } ?>

                                            </td>
                                            <td class="cell-time align-right"><span><? printf(date("d/m/Y", strtotime(substr($stsk[4], 0, 10)))) ?></span></td>
                                            <input type="hidden" class="st" value="<? printf($stsk[0]) ?>">
                                            <input type="hidden" class="iss_id" value="<? printf($stsk[1]) ?>">
                                        </tr>
                                        <tr class="display-progress">
                                            <td colspan="5">
                                                 <div class="info-content" style="display:none">
                                 <div class="docs-example">

                                        <dl class="dl-horizontal">
<? $shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP , CONCAT(B.CTZ_NAMES , ' ', B.CTZ_SURNAME1, ' ', B.CTZ_SURNAME2) AS NAME, B.CTZ_ADDRESS, B.CTZ_TEL, A.ISS_TICKET, B.CTZ_GEOLOC, E.CAT_DESCRIPT, A.ISS_PROGRESS FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) INNER JOIN CAT E ON(E.CAT_ID = A.ISS_TYPE) WHERE ISS_ID = " . $stsk[1] ));

                                         if($shine['CTZ_GEOLOC'] !== 0){ ?>
                                            <img style="float:right;" src="https://maps.googleapis.com/maps/api/staticmap?zoom=14&size=150x150&sensor=false&maptype=roadmap&markers=color:red|<? echo $shine['CTZ_GEOLOC'] ?>">
                                          <? } else { ?>
                                               <i class="fa fa-camera fa-5x" style="float:right ; color grey"></i>
                                          <? } ?>
                                            <dt>Ciudadano</dt>
                                            <dd><? echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($shine['NAME'])))); ?></dd>
                                            <dt>Dirección</dt>
                                            <dd><? printf($shine['CTZ_ADDRESS']) ?></dd>
                                            <dt>Telefono</dt>
                                            <dd><? printf($shine['CTZ_TEL']) ?></dd>
                                            <dt>Descripción</dt>
                                            <dd><? printf($shine['ISS_DESCRIP']) ?></dd>
                                            <dt>Origen</dt>
                                            <dd><? printf($shine['CAT_DESCRIPT']) ?></dd>
                                        </dl>
                                        <p class="adjuste">
                                            <strong>Grado de progreso</strong><span class="pull-right small muted"> <? echo $shine['ISS_PROGRESS'] ?>%</span>
                                        </p>
                                            <div class="progress tight">
                                                <div class="bar forward" style="width: <? echo $shine['ISS_PROGRESS'] ?>%"></div>
                                            </div>
                                        <div class="front-response"></div>
                                        <pre class="pre" style="display:inline-flex; width: 100%">
                                            <i class="fa fa-paperclip fa-2x fr"  style="display: block;" title="Documentos de Respuesta"></i>
                                            <div class="front-received" style="width: 100%">
                                                                          <?    
                                   
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/" )){

                                          $file_extension2 = "";
                                        
                                           while (false !== ($archivos2 = readdir($handler2))){
                                          
                                            if(preg_match_all("/_" . $stsk[1] . "_/", $archivos2) == 1){
                                     
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
  <a href="../<? echo $_SESSION['TxtFacility'] ?>/<? echo $_SESSION['TxtCode'] ?>/<? echo $archivos2 ?>" download title="<? printf($archivos2) ?>" > <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x"  style="color: <? printf($cor) ?> "></i></a>
                                                  <? 
                                                  }
                                                }
                                              } // while false
                                        closedir($handler2);
                                        }
                                      ?>
                                      </div>
                                      <div class="front-sent">
                                      <?
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/reply/" )){

                                          $file_extension2 = "";
                                        
                                           while (false !== ($archivos2 = readdir($handler2))){
                                          
                                            if(preg_match_all("/_" . $stsk[1] . "_/", $archivos2) == 1){
                                     
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
  <a href="../<? echo $_SESSION['TxtFacility'] ?>/reply/<? echo $archivos2 ?>" download title="<? printf($archivos2) ?>" > <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x"  style="color: <? printf($cor) ?> "></i></a>
                                                  <? 
                                                  }
                                                }
                                              } // while false
                                            }
                                        closedir($handler2);
                                        
                                      ?>
                                      </div>
                                        </pre>
                                    </div>
                                        </div>
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($stsk[6]) ?>%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: <? printf($stsk[6]) ?>%;"></div>
                                            </div>
                                     <div class="collaborates">

                      <?
$spec_tem = mysqli_query($datos, "SELECT CONCAT(A.USR_NAME , ' ',  A.USR_SURNAME), A.USR_ID, B.STSK_STATE, B.STSK_PROGRESS, B.STSK_RESP, B.STSK_CHARGE_USR, B.STSK_MAIN_USR FROM USERS A INNER JOIN SUBTASKS B ON(A.USR_ID = B.STSK_CHARGE_USR) WHERE (STSK_ISS_ID = " . $stsk[1] . "  AND STSK_TYPE = 0);");
 while($fila_spec = mysqli_fetch_row($spec_tem)){ 
     if($fila_spec[4] == 1 ){ ?>
        <a class="hovertip extUsr" data-val="<? echo $fila_spec[3]; ?>" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila_spec[0]))))) ?>">
            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $_SESSION['TxtCode'] ?>_opt.jpg" class="group" >
            <i class="fa fa-check-circle finished" <? if($fila_spec[2] == 5){ ?> style="opacity: 1;"  <? } else {?> <? } ?> ></i>
            <input type="hidden" value="<? printf($fila_spec[1])?>">
        </a>
        <? 
        } else { 
          if($fila_spec[5] == $_SESSION['TxtCode']){
            continue; 
          } else {
          ?>
        
        <a class="hovertip extUsr" data-val="<? echo $fila_spec[3]; ?>" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila_spec[0]))))) ?>">
            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $fila_spec[1]; ?>_opt.jpg" class="group" >
            <i class="fa fa-check-circle finished" <? if($fila_spec[2] == 5){ ?> style="opacity: 1;"  <? } else {?> <? } ?> ></i>
            <input type="hidden" value="<? printf($fila_spec[1])?>">
        </a>

    <?  }
  }
    }  ?>
                                            </div>
                                      <div class="g-wrap"> <!--for  internal files and graphics-->
                                            <div class="file-sent" style="width: 80%;display: inline-block; vertical-align: top;">
                                             <?   
                                           
                         while($steam = mysqli_fetch_row($Query_team)){
                          
                               if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $steam[0] . "/")){

                                  continue; 

                                    } else {

                                if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $steam[0] . "/" )){
                                    
                                          $file_extension = "";

                                        while (false !== ($archivos = readdir($handler))){
                              
                                         if(preg_match_all("/_" . $stsk[1] . "_/", $archivos) == 1){
                                             
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

                         <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($steam[0]) ?>/<? printf($archivos) ?>" download><p class="ifile" title="<? printf($archivos) ?>"><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
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

                         <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($steam[0]) ?>_in/<? printf($archivos) ?>" class="file-opac" download><p class="ifile" title="<? printf($archivos) ?>"><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
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
                                           <div class="great-chart" style="width:18%; height:4em;"></div>
                                          </div>
                                            <div class="toFront">
                                            </div>
                                            <table style="width: 100%" class="ex-del-par">
                                              <thead>
                                                <th>Asunto</th>
                                                <th>Descripción</th>
                                                <th>Fecha</th>
                                              </thead>
                                              <tbody>
                                                  <?


  $trf = mysqli_query($datos, "SELECT TRF_SUBJECT, TRF_DESCRIPT, TRF_USER, TRF_ING_DATE FROM TRAFFIC WHERE TRF_STSK_SRC_ID = " . $stsk[0]);
                            while($tss = mysqli_fetch_row($trf)){

                                                  ?>
                                              <tr class="eu<? echo $tss[2] ?>" >
                                                 <td><? echo $tss[0] ?></td>
                                                 <td><? echo $tss[1] ?></td>
                                                 <td><? echo $tss[3] ?></td> 
                                              </tr>
                                          <?
                                                }
                                          ?>   
                                              </tbody>
                                            </table>
                                            </td>
                                        </tr>
                                        <? } 

                                    mysqli_data_seek($Query_subtask, 0);
                                    mysqli_data_seek($Query_team, 0);
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="module-foot">
                            </div>
                        </div>
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
                                                <input id="delegates" value="<?  echo $vlist ?>"/>
                                      
                                    <input type="text" id="subject" class="require-subtasks eras" val="" placeholder="asunto">
                                    <input type="hidden" value="" id="current-task"> 
                                    <input id="end-data" type="text" placeholder="Fecha Termino" class="datetimepicker eras" styles="vertical-align:top; display: inline-block;"/><br><br>
                                    <textarea id="st-description" placeholder="Descripcion del requerimiento" class="eras" style="margin: 1.5em .5em"></textarea>
                                    <div><button class="btn btn-info" id="del-subtask">Delegar Requerimiento</button>
                                    </div>
                                </div>
                       
                                <div id="wrap-D">
                                    <div id="D-drop" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    </div>
                                </div>
                                <div class="attach">
                                    <form id="upload" method="post" action="../backend/upload-new.php" enctype="multipart/form-data">
                                         <div id="drop">
                                             Arrastra Aqui
                                               <a>Buscar</a>
                                               <input type="file" name="upl" multiple />
                                               <input type="hidden" value="" name="code" id="stsk-code">
                                               <input type="hidden" value="<? printf($_SESSION['TxtFacility']) ?>" name="fac">
                                               <input type="hidden" value="" name="user" id="stsk-user">
                                               <input type="hidden" value="" name="issId" id="issId">
                                               <input type="hidden" value="<? echo $_SESSION['TxtCode'] ?>" name="muser">
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
                                        <p class="ifile iss<? printf($stsk_esp[1]) ?>"  draggable="true" ondragstart="drag(event)" title="<? printf($archivos2) ?>" id="<? printf($archivos2) ?>" ><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x"  style="color: <? printf($cor) ?> "></i>
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
                                                       <h3 style="display:inline-block">Compromisos Internos Enviados</h3>
                                                        <i class="fa fa-caret-right fa-2x" style="color: blue; cursor: pointer; float: right" id="sw-int-in-out"></i>
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
                                            <div class="pull-right">
                             <?  while($fi = mysqli_fetch_row($Query_alerts_int)){ 
                                       
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
                                            $tuba = "Atrasado";
                                          break;
                                          case 5:
                                             $type = "fa-check-circle";
                                             $taint = "#1CC131";
                                             $tuba = "Finalizado";
                                          break;


                                       }

                                    ?>
                                      
<a class="btn Qint" title="<? printf($tuba) ?>"><p style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>; font-size: 1.5em; font-weight: 800;" ><? printf($fi[0]) ?></p>
<i class="fa <? printf($type) ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>"></i>
</a> 

<? } ?>
                                            </div>

                                            </div>
                                        <div style="width: 100%" class="seoEnv">
                                        <input type="text" value="" placeholder="Búsqueda" id="search2" style="width: 36em; float: left; margin-left: 1em">
                                        <input type="text" id="dfrom2" class="seo" placeholder="Desde" style="width: 10em; margin: 0 .5em;vertical-align: top;">
                                        <input type="text" id="duntil2" class="seo" placeholder="Hasta" style="width: 10em; margin: 0 .5em;vertical-align: top;">
                                        </div>
                                            <div class="module-body table">
                                                   <table class="table table-message" id="int-table">
                                                      <tbody id="int-body">
                                                          <tr class="heading">
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Descripción requerimiento</td>
                                                              <td class="cell-status">Status</td>
                                                              <td class="cell-title">Asignar</td>
                                                              <td class="cell-time align-right">Fecha maxima respuesta</td>
                                                            </tr>
                         <? while ($fila5 = mysqli_fetch_row($internal)) {

                                         if(((int)$fila5[9]) == 0 ){

                                            $situation = "exclamation";
                                            $color = "color:#EE8817;";
                                            $lock = "";

                                         } else {

                                            $situation = "lock";
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
                                                                <td class="cell-icon int-lock" style="cursor: pointer; <? echo $color; ?>" ><i class="fa fa-<? echo $situation ?>"></i></td>
                                                                <td class="cell-title"><div><? echo $fila5[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due int-desglo" style="background-color:<? echo $fila5[8]; ?>" <? printf($lock) ?> ><? echo $fila5[6]; ?></b></td>
                                                                <td class="cell-title int-forward" style="cursor:pointer;"><i class="fa fa-chevron-circle-right"></i></td>
                                                                <td class="cell-time align-right"><? echo date("d/m/Y", strtotime(substr($fila5[10], 0, 10))) ?></td>
                                                            </tr>
                                                            <tr class="display-pro-int" style="display: none;">
                                                                <td colspan="5">
                                                                   <p>
                                                                        <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila5[7]) ?>%</span>
                                                                    </p>
                                                                    <div class="progress tight">
                                                                        <div class="bar bar-warning" style="width: <? printf($fila5[7]) ?>%;"></div>
                                                                    </div>  
                                                                                                                                       
                                                                    <div class="coll-int" style="width: 100%">

             <?  $part = mysqli_query($datos, "SELECT A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ', B.USR_SURNAME), A.STSK_ID FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_TYPE= 1 AND STSK_ISS_ID =" . $fila5[11] . " AND STSK_CHARGE_USR <> STSK_MAIN_USR)"); 
                               while($prt = mysqli_fetch_row($part)){
             ?>
                                                                        <a  class="hovertip" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($prt[1]))))) ?>">
                                                                            <img src="../<? echo $_SESSION['TxtFacility']  ?>/img/<? echo $prt[0]; ?>_opt.jpg" class="group" >
                                                                            <input type="hidden" value="u<? printf($prt[0])?>">
                                                                        </a>

                                                                        <? 
                                                                    }
                                                                    mysqli_data_seek($part, 0);
                                                                 ?>
                                                                    </div>


                                                <div class="int-files-for" style="display: inline-block; vertical-align:top;">
                          <?    
                      while($fint = mysqli_fetch_row($part)){

                          if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/", 0775, true); 
                              } 
                           
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/" )){
                                        
                                          $file_extension = "";

                                           while (false !== ($archivos2 = readdir($handler2))){
//echo "<script>console.info('" . $archivos2 . "' + ' / ' + '" . preg_match_all("/_\[" . $fint[2]. "\]_/", $archivos2) . "' + '/' + '" . $fila5[0] . "' )</script>";
                                         if(preg_match_all("/_\[" . $fint[2] . "\]_/", $archivos2) == 1){

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
                                          
                                                 <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? echo $_SESSION['TxtCode'] ?>_alt/<? printf($archivos2) ?>" download>
                                                     <p class="ifile-ii" title="<? printf($archivos2) ?>">
                                                         <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                         <span class="iname"></span>
                                                     </p>
                                                 </a>
                                                  <? }
                                                  } 
                                                } 
                                               }
                                                ?>
                                                </div>
                                             <div class="int-files-to">
                                                    
                                                </div>

                                                <table style="width: 100%" class="int-trf-descript">
                                                    <tbody>
                                                        <tr>
                                                            <td><span style="font-weight: bolder; font-style: italic">Asunto</span></td>
                                                            <td><span style="font-weight: bolder; font-style: italic">Descripcion</span></td>
                                                            <td><span style="font-weight: bolder; font-style: italic">Fecha</span></td>
                                                        </tr>
                                                <?
                                          echo "<script>console.info('" . $fila5[11] . "')</script>";
$tr_ii = mysqli_query($datos, "SELECT TII_USER, TII_STSK_ID, TII_STSK_SRC_ID, TII_SUBJECT, TII_DESCRIPT, TII_ING_DATE FROM TRAFFIC_II WHERE (TII_STSK_SRC_ID = " . $fila5[11] . " AND TII_FAC_CODE = " . $_SESSION['TxtFacility'] . ")" );

                                 while($fut = mysqli_fetch_row($tr_ii)){
                                                ?>
                                                        <tr class="trf-int-usr ust<? echo $fut[0] ?>" style="display: none">
                                                            <td><? echo $fut[3] ?></td>
                                                            <td><? echo $fut[4] ?></td>
                                                            <td><? echo  date("d/m/Y", strtotime(substr($fut[5], 0, 10))) ?></td>
                                                        </tr>

                                                <? } ?>
                                                    </tbody>
                                                </table>
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
                                            <div class="pull-right">
                                <?  while($fi = mysqli_fetch_row($Query_alerts_ii)){ 
                                       
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
                                      
<a class="btn Qiii" title="<? printf($tuba) ?>"><p style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>; font-size: 1.5em; font-weight: 800;" ><? printf($fi[0]) ?></p>
<i class="fa <? printf($type) ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>"></i>
</a> 

<? } ?>


                                            </div>

                                            </div>
                                      <div style="width: 100%; display:none" class="seoRec">
                                        <input type="text" value="" placeholder="Búsqueda" id="search3" style="width: 36em; float: left; margin-left: 1em">
                                        <input type="text" id="dfrom3" class="seo" placeholder="Desde" style="width: 10em; margin: 0 .5em;vertical-align: top;">
                                        <input type="text" id="duntil3" class="seo" placeholder="Hasta" style="width: 10em; margin: 0 .5em;vertical-align: top;">
                                      </div>
                                            <div class="module-body table" style="display:none">
                                                <table class="table table-message" id="income-ing">
                                                    <tbody id="income-int-body">
                                                          <tr class="heading" >
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Descripción requerimiento</td>
                                                              <td class="cell-status">Status</td>
                                                              <td class="cell-title">Asignar</td>
                                                              <td class="cell-time align-right">Fecha maxima respuesta</td>
                                                            </tr>
                                                        <? while ($ii = mysqli_fetch_row($query_incoming)) { 
                                                                  if($ii[9] == 0 || $ii[9] == '0'){

                                                                          $situation = "exclamation";
                                                                          $color = "color:#EE8817;";
                                                                          $lock = "";

                                                                       } else {
                              
                                                                          $situation = "lock";
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
                                                                <input type="hidden" value="<? echo $ii[0] ?>" class="ii-stsk">
                                                                <input type="hidden" value="<? echo $ii[1] ?>" class="main-user-ii"> 
                                                                <input type="hidden" value="<? echo $ii[11] ?>" class="ii-iss">
                                                                <td class="cell-icon int-lock" style="cursor: pointer; <? echo $color ?>;" ><i class="fa fa-<? echo $situation ?>"></i></td>
                                                                <td class="cell-title"><div><? echo $ii[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due ii-desglo" style="background-color:<? echo $ii[8]; ?>"><? echo $ii[6]; ?></b></td>
                                                                <td class="cell-title ii-forward" style="cursor:pointer;"><i class="fa fa-chevron-circle-right"></i></td>
                                                                <td class="cell-time align-right"><? echo date("d/m/Y", strtotime(substr($ii[10], 0, 10))) ?></td>
                                                            </tr>
                                                            <tr style="display:none;">
                                                                <td colspan="5">
                                                                    <p>
                                                                        <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($ii[7]) ?>%</span>
                                                                    </p>
                                                                    <div class="progress tight">
                                                                        <div class="bar bar-warning" style="width: <? printf($ii[7]) ?>%;"></div>
                                                                    </div>
                                                                    <div class="ii-files">

                          <?    if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/", 0775, true); 
   
                              } 

                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/" )){
                                        
                                          $file_extension = "";
                            
                                         while (false !== ($archivos2 = readdir($handler2))){
                                    
                                         if(preg_match_all("/_\[" . $ii[0] . "\]_/", $archivos2) == 1){
                                             
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
                                            
                                                                        <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? echo $_SESSION['TxtCode'] ?>_alt/<? printf($archivos2) ?>" download>
                                                                            <p class="ifile-ii" title="<? printf($archivos2) ?>">
                                                                                <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                                                <span class="iname"></span>
                                                                            </p>
                                                                        </a>
                                                                      <? }
                                                                      } 
                                                                    } ?>
                                                                    </div>
                                                            <table style="width: 100%;">
                                                                <tbody class="ii-body-table">
                                                                    <tr>
                                                                        <td><span style="font-weight: bolder;">Asunto</span></td>
                                                                        <td><span style="font-weight: bolder;">Descripcion</span></td>
                                                                        <td class="align-right"><span style="font-weight: bolder;">Fecha progreso</span></td>
                                                                    </tr>
                                                                      <?   
                                  $TII = mysqli_query($datos, "SELECT TII_SUBJECT, TII_DESCRIPT, TII_ING_DATE FROM TRAFFIC_II WHERE TII_STSK_ID =" . $ii[0]);
                                                        while ($ii_trf = mysqli_fetch_row($TII)) {
                                                                      ?>
                                                                     <tr>
                                                                         <td class="cell-title"><? echo $ii_trf[0] ?></td>
                                                                         <td class="cell-title"><? echo $ii_trf[1] ?></td>
                                                                         <td class="align-right"><? echo date("d/m/Y", strtotime(substr($ii_trf[2], 0, 10))) ?></td>
                                                                     </tr>
                                                                 <? } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                         <? } //fin  while incoming ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                         </div> 
                                     </div> 
                                     <div class="tab-pane fade" id="del-int-req">
                                          <div id="wrap-controls">
                                          <div id="int-back" style="cursor: pointer; width:20px;"><i class="fa fa-chevron-circle-left fa-2x"></i></div>
                                          <input value=" <? echo $intList ?>" id="int-del" style="width: 95%; display: inline-block; vertical-align: top;">
                                          <input type="text" id="subj-int" value="" placeholder="Ingrese un asunto" style="width: 67%;display: inline-block; vertical-align: top;">
                                          <input type="text" class="datetimepicker date-int-finish" style=" width: 30%;display: inline-block; vertical-align: top;" >
                                          <textarea id="descript-int" value="" placeholder="Describa el requerimiento" style="width:98%"></textarea>
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
                                    <input  id="pro-subject" type="text" class="int-ii-subtasks" value="" placeholder="asunto">
                                    <textarea id="pro-descript" placeholder="Descripcion cumplimiento" style="margin: 1.5em .5em"></textarea>
                                    <div class="progress-go">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                            </p>
                                             <input type="text" id="value-progress" class="span2" style="width: 28em"/>
                                    </div>
                                    <button class="btn btn-info" id="upgrade">Subir Progreso</button>
                                </div>

                                <div class="attach" style="display:inline-block">
                                    <form id="upload2" method="post" action="../backend/upload_admin_to_par_up.php" enctype="multipart/form-data">
                                         <div id="drop2">
                                             Arrastre aqui sus archivos
                                               <a>Buscar</a>
                                               <input type="file" name="upl" multiple />
                                               <input type="hidden" value="" name="code" id="stsk-code-ii">
                                               <input type="hidden" value="<? printf($_SESSION['TxtFacility']) ?>" name="fac">
                                               <input type="hidden" value="" name="user" id="stsk-user-ii">
                                               <input type="hidden" value="" name="">  
                                          </div>
                                         <ul>
                <!-- The file uploads will be shown here -->
                                         </ul>
                                    </form>
                              </div>
                          </div>
                  </div> <!-- fin set-pro-int-->
                      <div class="tab-pane fade" id="set-pro-own" data-stsk="" data-iss="">
                            <div class="media-stream">
                                <div class="sub-del" style="width:100%">
                                <div id="back-own"><i class="fa fa-chevron-circle-left fa-3x"></i></div>
                                    <h3>Subir Cumplimientos</h3>
                                    <strong id="wrapaudi"><small id="audititle"></small></strong>
                                    <input type="text" id="own-subtasks" value="" placeholder="asunto" style="margin: 1em .5em; width: 90%">
                                    <textarea id="own-descript" placeholder="Descripcion cumplimiento" style="margin: 1em .5em; width: 90%"></textarea>
                                    <div class="progress-go">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                            </p>
                                             <input type="text" id="value-progress" class="span2" style="width:400%"/>
                                    </div>
                                     <div id="up-own"></div>
                                    <button class="btn btn-info" id="upgrade-own" style="margin: 2em 0; position: relative; left:40%">Subir Progreso</button>
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
        <audio id="pro-audio"><source src="../backend/progress.mp3" type="audio/mpeg"></audio>
        <audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="../backend/notify.mp3" type="audio/mpeg"><source src="notify.wav" type="audio/wav"></audio>
    </div>
    <!--/.wrapper-->
    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2015 Eque-e </b>Todos los derechos reservados.
        </div>
    </div>
    <script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">
jQuery.extend(
    jQuery.expr[':'].containsCI = function (a, i, m) {
        //-- faster than jQuery(a).text()
        var sText   = (a.textContent || a.innerText || "");     
        var zRegExp = new RegExp (m[3], 'i');
        return zRegExp.test (sText);
    }
);

jQuery.fn.justtext = function() {
   
    return $(this)  .clone()
            .children()
            .remove()
            .end()
            .text();
 
};
    </script>
    <script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
    <script src="../scripts/jquery.knob.js"></script>
    <script src="../scripts/jquery.ui.widget.js"></script>
    <script src="../scripts/jquery.iframe-transport.js"></script>
    <script src="../scripts/jquery.fileupload.js"></script>
    <script src="../scripts/script.js"></script>
    <script src="../scripts/script-int.js"></script>
    <script src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script src="../scripts/plupload.full.min.js"></script>  
    <script src="../scripts/jquery.plupload.queue.js"></script>
    <script src="../scripts/es.js"></script>
    <script src="http://modernizr.com/downloads/modernizr-latest.js"></script>
    <script src="../scripts/bootstrap-slider.js"></script>
    <script type="text/javascript" src="../scripts/download.js"></script>
    <script src="../scripts/dist/js/standalone/selectize.js"></script>
    <script src="../scripts/typeahead.jquery.min.js"></script>
    <script type="text/javascript" src="../scripts/d3.min.js"></script>
    <script type="text/javascript" src="../scripts/radialProgress.js"></script>
    <script src="../scripts/dist/js/bootstrap-switch.min.js"></script>
</body>
<script type="text/javascript">
<? include_once "../backend/json_searchengine.php" ?>


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
    var dateTime, objeto, dateTime;

    //ii variables 
    var remoteUser= 0;
    var st_ii     = 0;
    var ii_ind    = 0;
    var ii_iss    = 0;
    var previan   = "";
    var aa_ii     = "";
    var kenin;
    //indexes
    var cc1 = "At";
    var cc2 = "At-int";
    var cc3 = "At-int-ii";
    var kenin, selectInt;
    var keys;
    var switcher=0;




    var substringMatcher = function(strs) {

    return function findMatches(q, cb) {

    var matches, substringRegex;
 
    // an array that will be populated with substring matches
    matches = [];
 
    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');
 
    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });
 
    cb(matches);
  };
};




$(document).on('ready', function(){

kenin = $('#delegates').selectize({
plugins: ['remove_button'],
delimiter: ',',
preload:true,
closeAfterSelect: true,
hideSelected: true, 
persist: false,
create: false,
openOnFocus: true,
onChange : function(){

$("#stsk-user").val($("#delegates").val());

},
onItemAdd: function(value){
  console.info(value);
  $("#stsk-user").val($("#delegates").val()); 
        if($("#issId").val() !== 0){
             $(".incoming-files").css({display: "block"});
             $(".incoming-files > p" ).css({display: "none"});
             $(".incoming-files p.iss" + $("#issId").val() ).css({display: "inline-block"});
        } 
}, 
onItemRemove : function(){
   if($("#delegates").val() == ""){
        $(".incoming-files").css({display: "none"});
   } 
}
   });


selectInt = $('#int-del').selectize({
plugins: ['remove_button'],
delimiter: ',',
preload:true,
closeAfterSelect: true,
hideSelected: true, 
persist: false,
create: false,
openOnFocus: true,
onChange : function(){
       user_send = $('#int-del').val();
       console.info(user_send);
       keyFile = RandomString(8);
       uploaderInt($("#up-int"), "", user_send, stsk_send , "internal", keyFile); 
},
onItemAdd: function(){

      }
});

kenin[0].selectize.clear();
selectInt[0].selectize.clear();

$("input[type=checkbox].swt-boo").bootstrapSwitch();

progressbar =  $('.span2').slider({ step: 10 , max: 100, min: 0});

$("i.fa-lock").parent().unbind('click');
$("i.fa-lock").parent().parent().children('td:nth-child(5)').off();

 dateTime = $('.datetimepicker').datetimepicker({
    step:5,
    lang:'es',
    format:'Y/m/d',
    timepicker: false,
    onShow: function (ct){
        this.setOptions({
            minDate : '-1970/01/02',  
            maxDate : dateTime,
            format:'d/m/Y'
        })
    }
});


var datetime  = $(".seo").datetimepicker({
    step:5,
    lang:'es',
    format:'d/m/y',
    timepicker: false
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
                maxDate : dateTime,
                format:'d/m/Y'
            });
        } else {
            this.setOptions({
                maxDate : '2036/12/29',
                format:"d/m/Y"
            });
        }
    }
});

 $('.starter #search1').typeahead({
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'subjects',
  source: substringMatcher(subjects)
})

$("#search1, #search2, #search3").on('paste keypress keydown input', function(){

var indval = $(this).attr("id");

  switch(true){
  case (indval == "search1"):
   var objTbl = "ext-tasks-table";
   var def = cc1;
  break;
  case (indval == "search2" ):
   var objTbl = "int-table";
   var def = cc2;
  break;
  case (indval == "search3" ):
   var objTbl = "income-ing";
   var def = cc3;
  break;
}

// get the current index of filter   $(".pull-left .btn-group > .btn:first-child").eq().text();

// get class of current visible elements
console.info(def);
    getFuzzyIndex($(this).val(), objTbl, def);


});


$(".seo").on("input paste keypress keydown change", function(){

 var indval = $(this).index(".seo");

if(isOdd(indval)){
   var newVal = indval -1;
} else {
  var newVal = indval;
}

switch(true){
  case (indval == 1 || indval == 0):
   var objTbl = "ext-tasks-table";
   var def = cc1;
  break;
  case (indval == 2 || indval == 3):
   var objTbl = "int-table";
   var  def = cc2;
  break;
  case (indval == 4 || indval == 5):
   var objTbl = "income-ing";
   var  def = cc3;
  break;
}
/*$(".pull-left .btn").eq(x).text()*/

    if($(".seo").eq(newVal).val() !== "" && $(".seo").eq(indval).next(".seo").val() !== ""){

        doSearch($(".seo").eq(newVal).val(),$(".seo").eq(indval).val(), objTbl);

    } else {

        $( "#" + objTbl + " tr.task").css({ display : "none"});
        $( "#" + objTbl + " tr." + def).css({ display : "table-row"});
    }
});


})

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

 remoteUser = $(this).parent().children("input").eq(1).val();
 st_ii      = $(this).parent().children("input").eq(0).val();
 ii_iss     = $(this).parent().children("input").eq(2).val();
 ii_ind     = $(this).index(".ii-forward");

$("#stsk-code-ii").val(st_ii);
$("#stsk-user-ii").val(remoteUser);
$("#stsk-user-ii").attr("name", "muser");

percent = parseInt($(this).parent().next().children('td').children('p').children('span').html());
console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);

$("#int-require").removeClass('active in');
$("#set-pro-int").addClass('active in');

});



$("#back-ii").click(function(){
    $("#set-pro-int").removeClass('active in');$("#int-require").addClass('active in');
});

$("#back-own").click(function(){
  $("#set-pro-own").removeClass('active in');$("#require").addClass('active in');
})


$(".toggle-attach").on('click', function(){

    if (st == 0){

        $("#wrap-D").css({ display: "none"});
        $(".attach").css({ display : "inline-block" });
        $("#froback").html('Documentos Anexos');


 st = 1;

    } else {
         $(".attach").css({ display: "none"});
         $("#wrap-D").css({ display: "inline-block" });
         $("#froback").html('Documentos desde front');
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
        // establecemos  quien es quien 
var stsk_id = $(this).parent().parent().children('input.st').val();
var iss_ident = $(this).parent().parent().children('input.iss_id').val();
var subject = $(this).parent().parent().children('td').eq(1).text();
var index_current = parseInt($(this).index(".forward"));
console.info("current-index:" + index_current);
$("#own-descript").val('');
$("#own-subtasks").val('');

$("#current-task").val(index_current);

dateTime = AmericanDate($(this).parent().next().children().html());

if($(this).next().attr("class") == "person-sw" ){

  if($(this).next().children('div').find(".swt-boo").bootstrapSwitch('state') == false){

$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile[class*='iss']").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});
$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

//$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

$(".incoming-files").css({ display : "none"});

} else {

$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#require").removeClass('active in');$("#set-pro-own").addClass('active in');
uploaderInt($("#up-own"), iss_ident, $("#muser").val(), stsk_id , 0);

}

} else if($(this).next().hasClass('fa-user') ){


$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#require").removeClass('active in');$("#set-pro-own").addClass('active in');
uploaderInt($("#up-own"), iss_ident, $("#muser").val(), stsk_id , 0);

} else if( $(this).next().hasClass('fa-group') ){


$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile[class*='iss']").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});

$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

//$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

$(".incoming-files").css({ display : "none"});


}
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
    if(checkIntDel() == true){
       intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#del-int-req").data("val"), 0);
        selectInt[0].selectize.clear();
    } else {
      bootbox.alert("Falta el siguiente campo :" + checkIntDel());
    }
    
} else {
 if(checkIntDel() == true){
    intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#del-int-req").data("val"), $("#send-int").data("val") );
    selectInt[0].selectize.clear();
   } else {
    bootbox.alert("Falta el siguiente campo :" + checkIntDel());
   }
}  
});

$("#upgrade").on('click', function (){
 
var da  = new Date();
var fp = da.getFullYear() + "-" + ('0' + (da.getMonth()+1)).slice(-2) + "-" + ('0' + da.getDate()).slice(-2) + " " + ('0' + da.getHours()).slice(-2) + ":" + ('0' + da.getMinutes()).slice(-2)  + ":" + ('0' + da.getSeconds()).slice(-2) ;
 
 $.ajax({ type: "POST", 
          url : "../backend/progress-ii.php?val=" + $("#value-progress").val() + 
          "&stsk_id=" + st_ii+ 
          "&user=" + mainuser +
          "&iss_id=" + ii_iss + 
          "&muser=" + remoteUser + 
          "&subject=" + $("#pro-subject").val() + 
          "&des=" +  $("#pro-descript").val() + 
          "&date=" + fp + 
          "&fac=" + fac , 
          success : function(data){
            console.info(data);
            bootbox.alert("progreso ingresado", function(){

                $("#set-pro-int").removeClass('active in');$("#int-require").addClass('active in');
                $("#income-int-body tr.task").eq(ii_ind).next().children("td").children("div.progress").children("div").css({ width: $("#value-progress").val() + "%"});
                $("#income-int-body tr.task").eq(ii_ind).next().children("td").children("p").children("span").html($("#value-progress").val() + "%");
                incoInt($("#pro-subject").val(), $("#pro-descript").val(), fp, ii_ind );

                if( $("#value-progress").val() == 100){
                    $("#income-int-body tr.task").eq(ii_ind).children('td').eq(2).children("b").html('Finalizada');
                    $("#income-int-body tr.task").eq(ii_ind).children('td').eq(2).children("b").css("background-color", "#1CC131");
                    $("#income-int-body tr.task").eq(ii_ind).removeClass("class").addClass("task Hc-int-ii");
                }
            $("#pro-subject").val('');
            $("#pro-descript").val('');
            $("#upload2 ul").empty();
           });

          }
      })  
});



$("#del-subtask").on('click', function(){
    //check type.
if(checkDelExt() == true) {

var _fS = new Date();
var fechaS = _fS.getFullYear() + "-" + ('0' + (_fS.getMonth()+1)).slice(-2) + "-" + ('0' + _fS.getDate()).slice(-2) + " 10:00:00";

    $.ajax({
        type: "POST",
        url: "../backend/stsk-del-new.php?iss_id=" + $("#issId").val() + 
        "&muser=" + $("#muser").val() +
        "&user=" + $("#delegates").val() +
        "&stsk=" + $("#stsk-code").val() + 
        "&subject=" + $("#subject").val() +
        "&descript=" + $("#st-description").val() +
        "&startD=" + fechaS + 
        "&fechaF=" + ($(".datetimepicker").val()).replace(/\//g, "-") + 
        "&fac=" + $("#facility").val(), 
        success : function(data){
           console.info(data);
           var filestring = "";
           var users = data.split("|");
        
           bootbox.alert("Requerimiento delegado existosamente");

                var target        =  $("#current-task").val();
                var key_main    = document.querySelectorAll(".collaborates")[target/2]; // aqui se cambió por una razón inexplicable

                for (i= 0 ; i < users.length-1; i++){
                var a_del       = document.createElement('a');
                a_del.className = "hovertip extUsr";
                a_del.title     = data;

                a_del.onclick  = function (){

   var ind = $(this).parent().next().parent().parent().prev().index('tr.task');
   
   var percent = $(this).attr("data-val");
   var usrId = $(this).children('input').val();
   var filCont = $(this).parent().next();

   for (i=0; i < filCont.children('div.file-contents').children('a').length; i++){
        if (filCont.children('div.file-contents').children('a').eq(i).attr('href').search(usrId + "_in") == -1){
            filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "0.3"});
        } else {
           filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "1"});
        }
   }

$(this).parent().parent().children('.ex-del-par').children('tbody').children('tr').css({ display : "none"});
$(this).parent().parent().children('.ex-del-par').children('tbody').children('tr.eu' + usrId).css({ display : "table-row"});
        var selter = d3.select(document.querySelectorAll('.great-chart')[ind]).transition().each('start',function (d){ $("#pro-audio")[0].play() }).each('end', function (d){ setTimeout(function(){$("#pro-audio")[0].pause() ; $("#pro-audio")[0].currentTime = 0 }, 800)})
        var rp1 = radialProgress(document.querySelectorAll('.great-chart')[ind])
                .label('')
                .diameter(125)
                .value(percent)
                .render();
     //$(".radial-svg").

                }
                
                var img_del =  document.createElement('img');
                img_del.className = "group";
                img_del.src = "../" + fac + "/img/" + users[i] + "_opt.jpg";

                var inp_del   = document.createElement('input');
                inp_del.type  = "hidden";
                inp_del.value = users[i]; // si es interno hay que ponerle una u antecediendo todo
              
                var icom       = document.createElement('i');
                icom.className = "fa fa-check-circle finished";
                icom.style.opacity = "0";

                a_del.appendChild(img_del);
                a_del.appendChild(icom);
                a_del.appendChild(inp_del);
                key_main.appendChild(a_del);
                 

               }
                var far = $("#D-drop").data("files").split("|");

               for(i=0; i < far.length; i++ ){
                        var extension = far[i].substring(far[i].length -3 , far[i].length);
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
            cor = "#44D933";
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
          filestring +=  '<a href="#" download>' +
           '<p class="ifile" title="' + far[i] + '">' +
             '<i class="fa fa-file-' + setClass + ' fa-2x" style="color: ' + cor + '"></i>' +
             '<span class="iname"></span>' +
           '</p>'+
        '</a>';

               }
               $("#upload ul").empty();
               $(".task").eq(target/2).find(".person-sw").replaceWith('<i class="fa fa-group spac"></i>');
               $(".forward").eq(target).attr("disabled", true);
               $(".file-sent").eq(target/2).html($(".file-sent").eq(target/2).html() + filestring);
               kenin[0].selectize.clear();
        }
    });

} else {

  bootbox.alert("Falta el siguiente campo :" + checkDelExt());
}

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
              cc1 = all_on[i].id;
           }
     }

});
$(".swt-int").on('click', function(){

    var all_on = document.querySelectorAll('.swt-int');
    var ex = $(this).attr("id");
    var title_in = $(this).html();
    $(".display-pro-int").css({ display: "none"});
    $(".title-int").html(title_in);
     for(i=0; i < all_on.length ; i++){
           if(all_on[i].id !== ex){
              $('.' + all_on[i].id).css({ display : "none"});
           } else {
              $('.' + all_on[i].id).css({ display: "table-row"});
                cc2 =  all_on[i].id;
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
              cc3 = all_on[i].id;
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
           unlock(stsk_int, stsk_int , obj);
    }
  });
})

$(".golang").on('click', function(){
    if($(this).data("val") === undefined || $(this).data("val") == 0){
        $(this).data("val", 1);
            var object = $(this).parent().children('.toFront');
            var iss_id = $(this).parent().parent().prev().children('input').eq(1).val();
        
                uploaderInt(object, iss_id);
    } else {
    $(this).data("val", 0);
       $(this).parent().children('.toFront').fadeToggle('slow');
    }
});

setInterval(function(){
  thirdPulling();
}, 5000);

function thirdPulling(){
     $.ajax({ type: "POST",
              url:"../backend/incoming-ii.php?usr=" + mainuser,
              success : function (data){
                    var alpha = [];
                    var delta = data.split("\n");
                        for(i=0; i < delta.length ; i++){
                            alpha = delta[i].split("|");
                            if(alpha[1] !== undefined){
                             if(alpha !== aa_ii){
                              firstTask(alpha[0], alpha[2], "Administrador" , alpha[3], alpha[6], 0, alpha[1], 0);
                                console.info( alpha[0] + "/" + alpha[1] + "/" + alpha[2] + "/" + alpha[3] +  "/" + alpha[4]);
                                   showAlert(alpha[2], "ii" ,  alpha[7]);
                                   newthum(2);
                                     alpha[2] = aa_ii;
                                }
                            }
                       }
                  }
            });
   }

$("#sw-int-in-out").on('click', function(){

/*
$manu['STSK_ID'];
$manu['STSK_ISS_ID'];
$manu['STSK_DESCRIP'];
date('d/m/Y', strtotime($manu['FECHA_FINAL']));
date('d/m/Y', strtotime($manu['FECHA_INICIAL']));
$manu['STSK_TYPE'];
*/
/*
     $.ajax({ type: "POST",
              url:"../backend/incoming-ii.php?usr=" + mainuser,
              success : function (data){
                console.info(data);
                    var alpha = [];
                    var delta = data.split("\n");
                        for(i=0; i < delta.length ; i++){
                            alpha = delta[i].split("|");
                             console.info(delta[i]);
                            if(alpha[1] !== undefined){
                            firstTask(alpha[0], alpha[2], "Administrador" , alpha[3], alpha[6], 0, alpha[1]);
                                console.info( alpha[0] + "/" + alpha[1] + "/" + alpha[2] + "/" + alpha[3] +  "/" + alpha[4]);
                                   showAlert(alpha[2], "ii" ,  alpha[7]);
                                   newthum(2);
                            }
                       }
                  }
            });

*/

  if($(this).data("val") == 0 || $(this).data("val") == undefined){

    $(this).removeClass("fa-caret-right");
    $(this).addClass("fa-caret-left");
    $(this).css({ color: "orange"});
    $(this).data("val", 1);
    $("#int-require h3").html("Compromisos Internos Recibidos");
 
     $("#int-table").fadeOut(400, function(){
         $("#income-ing").fadeIn(400, function(){
            $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(0).fadeOut(100, function(){
                 $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(1).fadeIn(100, function(){
                    $("#sw-int-in-out").parent().parent().children("div.module-body").eq(0).fadeOut(100, function(){
                         $("#sw-int-in-out").parent().parent().children("div.module-body").eq(1).fadeIn(100, function(){
                              $(".seoEnv").fadeOut(100, function(){
                                  $(".seoRec").fadeIn(100);
                              })
                         });
                    });
                 });
            });
         });
         
     });

     } else {
   
    $(this).removeClass("fa-caret-left");
    $(this).addClass("fa-caret-right");
    $(this).css({ color: "blue"});
    $("#int-require h3").html("Compromisos Internos Enviados");
    $(this).data("val", 0);
      $("#income-ing").fadeOut(400, function(){
         $("#int-table").fadeIn(400, function(){
            $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(1).fadeOut(100, function(){
                 $("#sw-int-in-out").parent().parent().children("div.clearfix").eq(0).fadeIn(100, function(){
                    $("#sw-int-in-out").parent().parent().children("div.module-body").eq(1).fadeOut(100, function(){
                         $("#sw-int-in-out").parent().parent().children("div.module-body").eq(0).fadeIn(100, function(){
                              $(".seoRec").fadeOut(100, function(){
                                  $(".seoEnv").fadeIn(100);
                              })
                         });
                    })
                 });
            });
         });
     });

    }
    
});

function unlock(stsk_id, iss_id, object){

$.ajax({
       type: "POST",
       url: "../backend/unlock-new.php?stsk_id=" + stsk_id + "&iss_id=" + iss_id,
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

  if($("#D-drop").data("files") == undefined){
     $("#D-drop").data("files", "");
  } 
    event.preventDefault();
    var data = event.dataTransfer.getData("text");
    event.target.appendChild(document.getElementById(data));
    document.getElementById(data).style.width = "100%";
    $("p[title='" + data + "'] span").css("text-align", "left");
    var chargeuser = $("#delegates").val();
    moveAtDragDropfiles(data, mainuser, chargeuser);
    $("#D-drop:after").css("content", " ");
    $("#D-drop").data("files", $("#D-drop").data("files") + data + "|");

}

function allowDrop (event) {
    event.preventDefault();
}

function drag (event) {
    event.dataTransfer.setData("text", event.target.title);
}



/*
$(".ifile").on('click', function(){
  var chargeuser = $("#delegates :selected").val();
  var insert     = $(this).html();
  var data       = $(this).children('span').text();
  $("#D-drop").html( $("#D-drop").html() + insert);
  console.info("archivos dinamicos :" data + "," + mainuser + "," + chargeuser );
  moveAtDragDropfiles(data , mainuser, chargeuser);
  $(this).html('');
})
*/

function moveAtDragDropfiles(name, main_usr_id, charge_usr_id){

    $.ajax({ type: "POST",
        url : "../backend/switchfiles-new.php?fac=" + fac + "&file_name=" + name + "&main_usr_id=" + main_usr_id + "&charge_usr_id=" + charge_usr_id,
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

var randFiles = "";

uploader =  $(object).pluploadQueue({
        runtimes : 'html5',
        url : '../backend/upload_for_front.php?'  ,
        chunk_size : '3mb',
        unique_names : true,
  filters : {
            max_file_size : '3mb',
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
                if(object.attr("id") !== "up-own"){
                    up.setOption("url", '../backend/upload_for_front.php?fac_id=' + fac + "&iss_id="+ iss_id);
                } else {
                  
                   up.setOption("url", url);
                }
                console.info( "el ide del uploader" + object.attr("id"));
                
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
                  // when finish , enabe button 
                $("#send-int").attr("disabled", true);
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
                console.info("se removió sobre la marcha");
                plupload.each(files, function(file) {
                    console.log('  File:', file);
                });
            },
  
            FileUploaded: function(up, file, info) {
                // Called when file has finished uploading
                console.log('[FileUploaded] File:', file, "Info:", info);
                randFiles += file.name + "|";

                $("#up-own").data("files", randFiles);


                if(object.hasClass("front-response")){
                  graphAddedFiles(object.next().children(".front-sent"), randFiles)
                }

            },
  
            ChunkUploaded: function(up, file, info) {
                // Called when file chunk has finished uploading
                console.log('[ChunkUploaded] File:', file, "Info:", info);
            },
 
            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                   console.log("reponse", files);
                // when finish , enabe button 
                $("#send-int").attr("disabled", false)
                $("#SendRequest-free").attr("disabled", false);
                randFiles = "";

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
          url: "../backend/delegate_internal-new.php?muser=" + $("#muser").val() + 
          "&user=" + user + 
          "&fechaF=" + date + 
          "&subject=" + sub + 
          "&descript=" + des + 
          "&startD=" + fecha  + 
          "&fac="+ fac +
          "&main_stsk=" + mst + 
          "&keyfile=" + keyFile, 
          beforeSend: function(){
                $("#send-int").attr("disabled", true);
          },
          success : function (data){
           result = data.split("|");
           console.log(data);
                   bootbox.alert("Su requerimiento ha sido generado existosamente", function(){
                         $("#send-int").attr("disabled", false);
                         $("#del-int-req").removeClass('active in');$("#int-require").addClass('active in');
                         if (mode != "first"){
                            assoc_collar_int(user, ind);
                            
                         } else {

                            firstTask(result[0], des, result[1] , date, result[1], 1, "", 1);

                            for(i=2; i < result.length; i++){
                                  assoc_collar_int(result[i], ind);
                            }
                           

                           // firstTask(stsk_ident, descript, user_name, date, user_id, kind, issId, Ft)
                         }
                     });
                  newthum(1);
                    $("#del-int-req input, #del-int-req textarea").val('');
                      $("#up-int").empty();
                        $("#int-del").val(1);

                }
  })

}

//historial de eventos 

$(".events").on('click', function(){

  //get the Classes by ID 
  // cambio de fotos
 var ucla =  $(this).parent().prev().prev().children('a').children('input');

$("#events [class*='task u']").css({display : 'none'});

   for (i=0; i < ucla.length; i++){

        $("#events [class='task " + ucla.eq(i).val() + "']").css({display : 'table-row'});
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
                $("#events .task u").css({display : 'none'});
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
var iconShow = "../" + fac + "/img/task.jpg" ;

} else if(type == "ii"){

var title = usr_name + " te envió un requerimiento :";
var iconShow = "../" + fac + "/img/itask.jpg" ;

} else {

var title = usr_name + " ha marcado un progreso :";
var iconShow = "../" + fac + "/img/pro.jpg";
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
            if( type == "req" || type == "ii"){
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
    changeListener();
}, 3000);

function changeListener(){
            $.ajax({
            type: "POST",
            url: "../backend/time-new.php?usr="+mainuser+"&fac="+fac,
            success: function(data){
                packets = data.split("|");
           // si esta el asunto repetido...
          // console.info(data);
           //Javier Cisterna Valdes|119|aunt|otro progreso|13/05/2015|13|70||70|1|80
                var nest = 0
             if(previan !== packets[2]){
               //si no está vacio
                 if(parseInt(packets[0]) !== 0 && packets[0] !== "" ){
                       showAlert(packets[2], "pro", packets[0]);
                       //si es de tipo externo ==*.*==
                            if(parseInt(packets[9]) == 0){
                                indice = $("input.st[value=" + packets[5] + "]").index(".st");
                                var kilo =0 ;
                                    } else {
                                indice = $("input.hi-int-id[value=" + packets[5] + "]").index(".hi-int-id");
                                var kilo = 1;
                                    }
                             //==*.*==
                             //ponga fin si es final
                            if(packets[7] == "FINALIZADO"){
                                 newthum(kilo);
                               } 
                            console.info(packets.length);
                            if(packets.length == 12){
                              nest = packets[11];
                            }   
            updateProgress(packets[2], packets[3], packets[6], packets[4], packets[1], packets[0], indice, packets[5], packets[9], nest, packets[10] );
                          //aqui si es de tipo externo \./\./
                             console.info(indice);
                             console.info("progreso del usuario : " + packets[10]);
                        if(parseInt(packets[10]) >= 99.5){
                            $("#ext-tasks-table .due").eq(indice).parent().parent().next().children('td').children('div.collaborates').find('input[value=' + packets[1] + ']').prev().css({ opacity : "1"});
                            $("#ext-tasks-table .due").eq(indice).parent().parent().removeClass().addClass("task Hc");
                          }
                        if(parseInt(packets[6]) >= 99 && parseInt(packets[9]) == 1){
                            console.info(indice);
                            $(".int-desglo").eq(indice).html("Finalizada").css("background-color","#1CC131" );
                            $(".int-desglo").eq(indice).parent().parent().removeClass().addClass("task Hc-int");
                          }

                       if(parseInt(packets[6]) >= 99 && parseInt(packets[9]) == 0){
                            console.info(indice);
                            $("#ext-tasks-table .due").eq(indice).html("Finalizada").css("background-color","#1CC131" );
                            $(".int-desglo").eq(indice).parent().parent().removeClass().addClass("task Hc"); 
                          }
                      // \./\./
                    }
                    previan = packets[2];
                }
            }
        })

    }


if(typeof(EventSource) !== "undefined") {

    var source       = new EventSource("../backend/sse-event-new.php?usr=" + mainuser);
    source.onmessage = function(event) {

       var eventMessage = event.data.split('\n');
       if (eventMessage[0] !== previuosData){
        showAlert(eventMessage[0], 'req');
          inputTask(eventMessage[0], eventMessage[1], eventMessage[3], eventMessage[4], eventMessage[2], eventMessage[5] , eventMessage[6], eventMessage[7], eventMessage[8], eventMessage[9]);
        newthum(0);
        previuosData = eventMessage[0];
    } 
}
} else {

    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";

}

function assoc_collar_int(usr, ind){

    console.info("indice quele llega a las fotos internas es : " + ind);

var parent = document.querySelectorAll('.coll-int')[ind];

  var string =  '<a class="hovertip" title="" onclick="hovertip(this)">' +
        '<img src="../' + fac + '/img/'  + usr + '_opt.jpg" class="group" ><input type="hidden" value="u'  + usr + '">' +
        '</a>';

  var stringAl   = parent.innerHTML + string;   
parent.innerHTML = stringAl;   

}

function inputTask(stsk_descript, stsk, iss, ctz, desc, ctz_tel, ctz_address, date_fin, ctz_geoloc, ticket){

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
    td1.innerHTML = ticket;
    td2.className = "cell-title";
    td3.className = "cell-status";
    td4.className = "cell-title";
    td5.className = "cell-time align-right";
    td5.innerHTML = date_fin;

    btn.className   = "btn btn-small forward";
    btn.innerHTML   = "<i class='fa fa-chevron-circle-right'></i>";
    btn.style.marginRight = "1em";
    td4.appendChild(btn);

        b.onclick = function(){
        if(!$(this).data("val") || !$(this).data("val") === 0 ){
             $(this).parent().parent().next().css({ display: "table-row"});
             $(this).data("val", 1);
       } else  {
         $(this).parent().parent().next().css({ display: "none"});
        $(this).data("val", 0);
        }
    }


btn.onclick =  function(){
        // establecemos  quien es quien 
var stsk_id = $(this).parent().parent().children('input.st').val();
var iss_ident = $(this).parent().parent().children('input.iss_id').val();
var subject = $(this).parent().parent().children('td').eq(1).text();
var index_current = parseInt($(this).index(".forward"));
dateTime = AmericanDate($(this).parent().next().html());
console.info("current-index:" + index_current);
$("#current-task").val(index_current);

$("#own-descript").val('');
$("#own-subtasks").val('');

if($(this).next().attr("class") == "person-sw" ){

  if($(this).next().children('div').find(".swt-boo").bootstrapSwitch('state') == false){

$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile[class*='iss']").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});
$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

//$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

$(".incoming-files").css({ display : "none"});

} else {

$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#require").removeClass('active in');$("#set-pro-own").addClass('active in');
uploaderInt($("#up-own"), iss_ident, $("#muser").val(), stsk_id , 0);

}

} else if($(this).next().hasClass('fa-user') ){


$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#require").removeClass('active in');$("#set-pro-own").addClass('active in');
uploaderInt($("#up-own"), iss_ident, $("#muser").val(), stsk_id , 0);

} else if( $(this).next().hasClass('fa-group') ){


$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile[class*='iss']").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});

$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

//$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

$(".incoming-files").css({ display : "none"});


}
};
    div_s   = document.createElement('div');
    i_s     = document.createElement('i');
    i_s2    = document.createElement('i');
    i_s3    = document.createElement('i'); // este tiene que ir sujeto al td
    input_s = document.createElement('input');

        div_s.className   = "person-sw";
        i_s.className     = "fa fa-user spac";
        i_s2.className    = "fa fa-group spac";
        i_s2.style.color  = "gray";
        i_s3.className    = "fa fa-search viewToggle";
        i_s3.style.color  = "lightblue";
        i_s3.style.fontSize = "1.5em";


        input_s.className = "swt-boo";

             input_s.type ="checkbox";
             input_s.checked = "true";
             input_s.setAttribute( "data-label-width", 3);
             input_s.setAttribute( "data-size", "mini");
             input_s.setAttribute( "data-on-color", "info");
             input_s.setAttribute( "data-off-color", "default");
             input_s.setAttribute( "data-on-text", "&ensp;");
             input_s.setAttribute( "data-off-text", "&ensp;");
             
            
    div_s.appendChild(i_s);
    div_s.appendChild(input_s);
    div_s.appendChild(i_s2);
      
    i_s3.onclick = function (){
        $(this).parent().parent().next().children('td').children().not("info-content").fadeToggle("fast", function(){
       $(this).parent().parent().next().children('td').children("info-content").fadeToggle("fast");
  });
    }


    td4.appendChild(div_s);
    td4.appendChild(i_s3);



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


    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(inp1);
    tr1.appendChild(inp2);

    parent.appendChild(tr1);



//estructura 2ndo row
                                         
    var tr2  = document.createElement('tr');
    var td6  = document.createElement('td');
    

    var div0 = document.createElement('div');
    var div1 = document.createElement('div');
    var div2 = document.createElement('div');
    var div3 = document.createElement('div');
    var div4 = document.createElement('div');
    var div5 = document.createElement('div');

    var divFil = document.createElement('div');

    var i1   = document.createElement('i');
    var i2   = document.createElement('i');
    var i3   = document.createElement('i');
    var i4   = document.createElement('i');
   
    var p1   = document.createElement('p');
    var p2   = document.createElement('p');
    var p3   = document.createElement('p');

    var pTbl = document.createElement('table');
    var pTbo = document.createElement('tbody');
    var pThe = document.createElement('thead');


    var pTth1 = document.createElement('th');
    var pTth2 = document.createElement('th');
    var pTth3 = document.createElement('th');

     pTbl.className = "ex-del-par";
     pTbl.style.width = "100%";
   
     pTth1.innerHTML = "Asunto";
     pTth2.innerHTML = "Descripción";
     pTth3.innerHTML = "Fecha";

     pThe.appendChild(pTth1);
     pThe.appendChild(pTth2);
     pThe.appendChild(pTth3);
     
     pTbl.appendChild(pThe);
     pTbl.appendChild(pTbo);



 //=============================================


div_g  = document.createElement('div'); // g-wrap
div_g1 = document.createElement('div');//contenedor 1
div_g2 = document.createElement('div');//contenedor 2

div_g.className = "g-wrap";
div_g1.className = "file-sent";
div_g2.className = "file-contents";

div_g1.style.width = "80%";
div_g1.style.display = "inline-block";
div_g1.style.verticalAlign = "top";

 div_g2.style.width = "80%";
 div_g2.style.display = "inline-block";
 div_g2.style.verticalAlign = "top";

//****** ????? ****** //

//*************

 div_gf1  = document.createElement('div'); // contenedor flecha 1
 div_gf2  = document.createElement('div'); // contenedor flecha 2

 i_gf1  = document.createElement('i'); //flecha 1
 i_gf1.className = "fa fa-chevron-right fa-2x";
 i_gf1.style.color= "#66A4EE";

 i_gf2 = document.createElement('i');
 i_gf2.className = "fa fa-chevron-left fa-2x";
 i_gf2.style.color = "#8FEC68";

 div_gf2.style.width = "4em";
 div_gf2.style.verticalAlign = "top";
 div_gf2.style.display = "inline-block";

 div_gf1.style.width = "4em";
 div_gf1.style.verticalAlign = "top";
 div_gf1.style.display = "inline-block";

 div_gf1.appendChild(i_gf1);
 div_gf2.appendChild(i_gf2);
 
 div_g1.appendChild(div_gf1);
 div_g2.appendChild(div_gf2);

 div_gch = document.createElement('div');
 div_gch.className = "great-chart";
 div_gch.style.width = "18%";
 div_gch.style.height = "4em";

 div_g.appendChild(div_g1);
 div_g.appendChild(div_g2);

 div_g.appendChild(div_gch);



   //**** micro esctructura del panel de atencion al ciudadano

     div_ic      = document.createElement('div');
     div_ic_back = document.createElement('div');
     div_ic_pro  = document.createElement('div');
     div_ic_file = document.createElement('div');
     i_ic        = document.createElement('i');

     dl  = document.createElement('dl');
     if(ctz_geoloc == 0){
     img_dl = document.createElement('i');
     img_dl.style.color = "gray";
     img_dl.style.float = "right";
     img_dl.className = "fa fa-camera fa-5x"; 

     } else {
     img_dl = document.createElement('img');
     img_dl.src = "https://maps.googleapis.com/maps/api/staticmap?zoom=14&size=150x150&sensor=false&maptype=roadmap&markers=color:red|" + ctz_geoloc;
     img_dl.style.float = "right";
     }
     dl.appendChild(img_dl);

     dt1 = document.createElement('dt');
     dt2 = document.createElement('dt');
     dt3 = document.createElement('dt');
     dt4 = document.createElement('dt');

     dd1 = document.createElement('dd');   
     dd2 = document.createElement('dd');  
     dd3 = document.createElement('dd');
     dd4 = document.createElement('dd');

     p_pro    = document.createElement('p');
     str_pro  = document.createElement('strong');
     span_pro = document.createElement('span');
     pre_pro  = document.createElement('pre');

    //==== ***** classes ****

    div0.className        = "info-content";
    div0.style.display    = "none";
    div_ic.className      = "docs-example";
    div_ic_back.id        = "back";
    div_ic_pro.className  = "progress tight";
    div_ic_file.className = "files"
    i_ic.className        = "fa fa-chevron-circle-right fa-2x";
    i_ic.style.color      = "rgba(38, 134, 244, 0.9)";
    i_ic.style.cursor     = "pointer";


    dl.className       = "dl-horizontal";
    p_pro.className    = "ajuste"; 
    span_pro.className = "pull-right small muted";
    pre_pro.className  = "pre";

    
    str_pro.innerHTML = "Grado de progreso";
    span_pro.innerHTML = "0%";
    p1.appendChild(str_pro);
    p1.appendChild(span_pro);

    //************ inner HTML ******
    dt1.innerHTML = "Ciudadano";
    dt2.innerHTML = "Dirección";
    dt3.innerHTML = "Telefono";
    dt4.innerHTML = "Descripción";

    dd1.innerHTML = ctz;
    dd2.innerHTML = ctz_address;
    dd3.innerHTML = ctz_tel;
    dd4.innerHTML = desc;
  
   //********* appending **********
tr2.style.display = "table-row";
tr2.className = "display-progress";
td6.colSpan = "5";

dl.appendChild(dt1);
dl.appendChild(dd1);
dl.appendChild(dt2);
dl.appendChild(dd2);
dl.appendChild(dt3);
dl.appendChild(dd3);
dl.appendChild(dt4);
dl.appendChild(dd4);

div_ic_back.appendChild(i_ic);  
div_ic.appendChild(div_ic_back);
div_ic.appendChild(dl);
div_ic.appendChild(p_pro)
div_ic.appendChild(div_ic_pro);
div_ic.appendChild(div_ic_file);
div_ic.appendChild(pre_pro);

div0.appendChild(div_ic);

    div2.className  = "progress tight";
    div3.className  = "bar bar-warning";
    div4.className  = "collaborates";
var filestring = "";
 $.ajax({
          type: "POST",
          url: "../backend/dynamics_JSON_files.php?usr_id=" + mainuser + "&iss_id=" + iss + "&fac=" + fac,
          success: function(data){
            console.log(data);
    console.info("backend/dynamics_JSON_files.php?usr_id=" + mainuser + "&iss_id=" + iss + "&fac=" + fac);
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
         elem[n].title     = files[n];
         elem[n].setAttribute("draggable", true);
 
         elem[n].ondragstart = function(event){
                 drag(event);
          }
   
          elem[n].ondblclick = function () {
                 downloadFile("../" + fac + "/" + mainuser + "/" + files[n]);
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
            cor = "#44D933";
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
         
         elem_s[n]             = document.createElement('span');
         elem_s[n].className   = "iname";
         elem_s[n].innerHTML   = files[n];

         elem[n].appendChild(elem_i[n]);
         elem[n].appendChild(elem_s[n]);
        

       filestring +=  '<a href="../' + fac + '/' + mainuser + '/' + files[n] + '" download>' +
           '<p class="ifile" title="' + files[n] + '">' +
             '<i class="fa fa-file-' + setClass + ' fa-2x" style="color: ' + cor + '"></i>' +
             '<span class="iname"></span>' +
           '</p>'+
        '</a>';
         pre_pro.innerHTML = filestring;
         fileParent.appendChild(elem[n]);
      }
}
});

pre_pro.innerHTML = filestring;

    div4.appendChild(i4);
    td6.appendChild(div0);
    td6.appendChild(p1);
    div2.appendChild(div3);

    td6.appendChild(div1);
    tr2.appendChild(td6);
    td6.appendChild(div2);
    td6.appendChild(div4);
    td6.appendChild(div_g);
    td6.appendChild(i2);
    td6.appendChild(i3);
    td6.appendChild(pTbl);
    parent.appendChild(tr2);

  $(".swt-boo").eq($(".swt-boo").length-1).bootstrapSwitch();
    
}

function getFiles(iss_id, usr_id, callback){9
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


function updateProgress(subject, descript, percent, date, userId, usr_name, ind, stsk, kind, aux_stsk, customPro){

console.log(subject + ","  + descript + ","  + percent + "," +  date + "," +  userId + "," +  usr_name + ","  + ind + "," + stsk + "," + kind + "//admin??"+ aux_stsk);
    
if(parseInt(kind) == 0){

document.querySelectorAll("#ext-tasks-table td .bar")[ind*2].style.width = percent + "%";
document.querySelectorAll("#ext-tasks-table td p > span.muted")[ind*2].innerHTML = percent + "%";
console.info("porsica el ind es : " + ind);
$(".file-contents").eq(ind).parent().prev().find("a input[value= "+ userId +"]").parent().attr("data-val", customPro) ;

} else {

document.querySelectorAll("#int-table .bar")[ind].style.width = percent + "%";
document.querySelectorAll("#int-table p > span.muted")[ind].innerHTML = percent + "%";
insertScheduleTraffic(subject, descript ,date, userId, ind);

}

var parent = document.querySelectorAll(".ex-del-par tbody")[ind];

var tr_av  = document.createElement('tr');
var td1_av = document.createElement('td');
var td2_av = document.createElement('td');
var td3_av = document.createElement('td');

if(kind == 1 ){
  tr_av.style.display =  "none !important";
  tr_av.className = "trf-int-usr ust" + userId;

}

td1_av.innerHTML = subject;
td2_av.innerHTML = descript;
td3_av.innerHTML = date;

tr_av.appendChild(td1_av);
tr_av.appendChild(td2_av);
tr_av.appendChild(td3_av);

     if(kind == 0){
        tr_av.className = "eu" + userId;
        pseudoparent =  document.querySelectorAll(".ex-del-par tbody")[ind];
        pseudoparent.appendChild(tr_av);

      } else {

        tr_av.className = "eu" + userId;
        pseudoparent =  document.querySelectorAll(".ex-del-par tbody")[ind];
        pseudoparent.appendChild(tr_av);
      }

if(aux_stsk !== 0){
  //se le pone un argumento extra para verficar el origen y sis correponde a un admin-admin o  admin-back por parte del servidor
var file_url = "../backend/files_back_to_admin.php?fac=" + fac +  "&user=" + mainuser + "&stsk=" + aux_stsk + "&kind=" + kind + "&current=" + mainuser  ;
console.info("que se está enviando : " + file_url + " cuando aux_stsk !== 0");
} else {
var file_url = "../backend/files_back_to_admin.php?fac=" + fac +  "&user=" + mainuser + "&stsk=" + stsk + "&kind=" + kind + "&current=" + mainuser ;
console.info("que se está enviando : " + file_url + " cuando aux_stsk == 0");
}

$.ajax({ type:"POST",
         url: file_url,
         success : function (data){
            console.info(data);
            files = data.split("|");
            
              if(kind == 0 ){
                var arp = $(".file-contents").eq(ind);
              } else {
                var arp = $(".int-files-for").eq(ind);
              }

                arp.html('');      
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
            cor = "#44D933";
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

var fileN = filename(files[n]);

       if(parseInt(kind) == 0 && aux_stsk == 0){

      var sshot =  document.querySelectorAll(".file-contents")[ind].innerHTML;
      strHtml   =  sshot + '<a href="'+ files[n] + '" download>' +
      '<p class="ifile" title="' + fileN + '"><i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor+ ';"></i>'
      '<span class="iname"></span></p></a>';
      document.querySelectorAll(".file-contents")[ind].innerHTML =  strHtml;
       console.info("indice: " + ind + " aqui es parseInt(kind) == 0 && aux_stsk == 0");

       } else if(aux_stsk !== 0) {
         
                     var sshot =  document.querySelectorAll(".int-files-for")[ind].innerHTML;
      strHtml   =  sshot + '<a href="' + files[n] + '" download="' + fileN +'">' +
      '<p class="ifile" title="' + fileN + '"><i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor+ ';"></i>'
      '<span class="iname"></span></p></a>';
      document.querySelectorAll(".int-files-for")[ind].innerHTML = strHtml;
       console.info("indice: " + ind + " aqui es si else if(aux_stsk !== 0) ");

       } else{
                       var sshot =  document.querySelectorAll(".int-files-for")[ind].innerHTML;
      strHtml   =  sshot + '<a href="' + files[n] + '" download>' +
      '<p class="ifile" title="' + fileN + '"><i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor+ ';"></i>'
      '<span class="iname"></span></p></a>';
      document.querySelectorAll(".int-files-for")[ind].innerHTML = strHtml;
             console.info("indice: " + ind + " else ");
       }

      }

    }
})


    
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


function firstTask(stsk_ident, descript, user_name, date, user_id, kind, issId, Ft){

  // si el lo envia
  if (kind == 1){
      var parent_int =  document.getElementById("int-body");
  } else { // si el lo recibe
      var parent_int =  document.getElementById("income-int-body");
  }
    
    var tr1  = document.createElement('tr');
    var td1  = document.createElement('td');
    var td2  = document.createElement('td');
    var td3  = document.createElement('td');
    var td4  = document.createElement('td');
    var td5  = document.createElement('td');
    var i1   = document.createElement('i');
    var i2   = document.createElement('i');
    var b1   = document.createElement('b');
    var inp1 = document.createElement('input');
    
    td1.className = "cell-icon int-lock";
    if(kind == 0){
         tr1.className = "task Ec-int-ii";
    } else {
          tr1.className = "task Ec-int";
    }
    
    td3.className = "cell-status";
    td2.innerHTML = descript;
    td5.innerHTML = date;
    inp1.value    = stsk_ident;
    inp1.type     = "hidden";

    if(kind == 1){
       inp1.className = "hi-int-id";
    } else {
       inp1.className = "";
    }

    td4.className            = "int-forward";
    b1.innerHTML             = "En Curso";
    b1.className             = "due int-desglo"; 
    b1.style.backgroundColor = "#178FD0";

    i1.className   = "fa fa-exclamation";
    i1.style.color = "orange";
    i2.className   = "fa fa-chevron-circle-right";
    
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
if(kind == 1){

i2.onclick = function(){

 mode = "delegate";
 var indice = $(this).parent().index(".int-forward");
 var ids    = $(this).parent().parent().children('input').val();

console.info(ids);
console.info(indice);
stsk_send = ids;

$("#del-int-req").data("val",indice);
$("#send-int").data("val", ids);
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');
}

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
    console.info(obj.parent().parent().children('td').eq(3).children('i').attr("class"));
    if(obj.parent().parent().children('td').eq(3).children('i').hasClass("fa-chevron-circle-right")){
  var stsk_int = $(this).parent().children('input').val();
    bootbox.confirm("Esta seguro de cerrar este requerimiento?", function (confirmation){
    if (confirmation){
           unlock(stsk_int, stsk_int , obj);
            obj.parent().parent().children('td').eq(3).html('<i class="fa fa-times-circle"></i>');
    }
  })
    } 
}

if(kind == 0){


td4.className = "ii-forward";
// aqui yace
td4.onclick = function (){
dateTime = AmericanDate($(this).next().html());

 remoteUser = user_id;
 st_ii      = stsk_ident;
 ii_iss     = issId;
 ii_ind     = $(this).index(".ii-forward");

$("#stsk-code-ii").val(stsk_ident);
$("#stsk-user-ii").val(user_id);
$("#stsk-user-ii").attr("name", "muser");

console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

var percent = parseInt($(this).parent().next().children('td').children('p').children('span').html());

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);

$("#int-require").removeClass('active in');
$("#set-pro-int").addClass('active in');
}
}

var tr2    = document.createElement('tr');
var td_i1  = document.createElement('td');
var p      = document.createElement('p');
var strong = document.createElement('strong');
var span   = document.createElement('span');
var div1   = document.createElement('div');
var div2   = document.createElement('div');
var div3   = document.createElement('div');
var amd    = document.createElement('a'); //aqui es donde comienza
var img    = document.createElement('img');
var inp2   = document.createElement('input');
var div4   = document.createElement('div');

tr2.style.display = "none";
if(kind == 1){
  tr2.className     = "display-progress";
}
span.className    = "pull-right small muted";
div1.className    = "progress tight";
div2.className    = "bar bar-warning";
div3.className    = "coll-int";
div3.style.width  = "100%";
strong.innerHTML  = "Grado de progreso";


div4.style.width = "100%";

if(kind == 1){
  var div5 = document.createElement('div')
      div4.className ="int-files-for";
      div5.className = "int-files-to";
      div5.style.width = "100%";

} else {

    div4.className ="ii-files";
}


var amdString = '<a class="hovertip" title="' + user_name + '" onclick="hovertip(this)">' +
                   '<img src="../' + fac + '/img/' + user_id + '_opt.jpg" class="group">' +
                   '<i class="fa fa-check finished" style="opacity: 0"></i>' +
                   '<input type="hidden" value="u' + user_id + '">' +
                   '</a>';
/*
amd.className = "hovertip";
amd.title = user_name;

amd.onclick = function(event){
  console.info("desde firstTask");
  hovertip(event.target);
}

img.src ="../" + fac + "/img/" + user_id + "_opt.jpg";
img.className ="group";

icom = document.createElement('i');
icom.className = " fa fa-check finished";
icom.style.opacity = "0";

inp2.type= "hidden";
inp2.value = "u" + user_id;
*/
if(kind == 0){
  var table_string = '<table style="width: 100%" class="int-trf-descript">' +
       '<tbody class="ii-body-table">' +
           '<tr>' +
               '<td><span style="font-weight: bolder; font-style: italic">Asunto</span></td>' +
               '<td><span style="font-weight: bolder; font-style: italic">Descripcion</span></td>' +
               '<td><span style="font-weight: bolder; font-style: italic">Fecha</span></td>' +
          '</tr>' +
     '</tbody>' +
'</table>';
} else {
  var table_string = '<table style="width: 100%" class="int-trf-descript">' +
       '<tbody>' +
           '<tr>' +
               '<td><span style="font-weight: bolder; font-style: italic">Asunto</span></td>' +
               '<td><span style="font-weight: bolder; font-style: italic">Descripcion</span></td>' +
               '<td><span style="font-weight: bolder; font-style: italic">Fecha</span></td>' +
          '</tr>' +
     '</tbody>' +
'</table>';
}


td_i1.colSpan = "5";

if(kind == 0){
   var url_files =  "../backend/files_back_to_admin.php?fac=" + fac +  "&user=" + mainuser + "&stsk=" + stsk_ident + "&kind=1" ;
   console.info("lo que se manda desde firstTask es url_files : " + url_files);
} else {
   var url_files =  "../backend/files_back_to_admin.php?fac=" + fac +  "&user=" + user_id + "&stsk=" + stsk_ident + "&kind=" + kind + "&first=" + Ft;
   console.info("lo que se manda desde firstTask es url_files : " + url_files);
}

$.ajax({ type:"POST",
         url: url_files,
         success : function (data){
            console.info(data);
            files = data.split("|");
           var str_file = "";
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
            cor = "#44D933";
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
    var str_nameFil = filename(files[n]);

        str_file  += "<a href='" + files[n] + "' download>" +
            "<p class='ifile-ii' title='" + str_nameFil  + "'>" +
                "<i class='fa fa-file-" + setClass + " fa-2x' style='color: " + cor + "'></i>" +
                "<span class='iname '></span>" +
            "</p>" +
        "</a>";
        
    
}

  div4.innerHTML = str_file;

}
});


p.appendChild(strong);
p.appendChild(span);

div1.appendChild(div2);
//amd.appendChild(img);// 
//amd.appendChild(inp2);// elementos que se le adjuntan
//amd.appendChild(icom);//

div3.innerHTML = amdString;

td_i1.appendChild(p);
td_i1.appendChild(div1);
td_i1.appendChild(div3);
td_i1.appendChild(div4);

if(kind == 1){

    td_i1.appendChild(div5);

} 

td_i1.insertAdjacentHTML("beforeend",table_string);

tr2.appendChild(td_i1);
parent_int.appendChild(tr1);
parent_int.appendChild(tr2);

insertAfter(tr2, tr1);

}


function AmericanDate(date){
  var subs = date.substring(6) + "/"  + date.substring(3, 5) + "/" + date.substring(0, 2);
return subs;
}


function RandomString(length) {
    
    var str = '';
    for ( ; str.length < length; str += Math.random().toString(36).substr(2) );
    return str.substr(0, length);
}


function incoInt(sub, des, date, ind){

      var parent = document.querySelectorAll(".ii-body-table")[ind];
      var tr_ii  = document.createElement('tr');
      var td_ii1 = document.createElement('td'); 
      var td_ii2 = document.createElement('td');
      var td_ii3 = document.createElement('td');

      td_ii1.className = "cell-title";
      td_ii2.className = "cell-title";
      td_ii3.className = "align-right";

      td_ii1.innerHTML = sub;
      td_ii2.innerHTML = des;
      td_ii3.innerHTML = date;

      tr_ii.appendChild(td_ii1);
      tr_ii.appendChild(td_ii2);
      tr_ii.appendChild(td_ii3);

      parent.appendChild(tr_ii);
}



function thum(kind, type, ancient){

if(kind == "int"){
   var thum = $("a.Qint[title='" + type + "']");
   var ght = 1;
   var change = "Qint";
} else if (kind == "ii"){
   var thum = $("a.Qiii[title='" + type + "']");
   var ght = 2;
   var change = "Qiii"; 
} else { 
   var thum = $("a.Qext[title='" + type + "']");
   var ght = 0;
   var change = "Qext";
}

if(ancient !== '' && type == 'Finalizado'){

  $("a." + change + "[title='" + ancient + "']").children('p').html(parseInt(   $("a." + change + "[title='" + ancient + "']").children('p').html()) - 1 );

}

var current = parseInt(thum.children('p').html()) + 1 ;
thum.children('p').html(current);

//si no está 

if(thum.length == 0 ){
    switch (type){

    case "En Curso":
      var design = "fa-angle-double-right";
      var taint = "#178FD0";
    break;
    case "Por Vencer":
      var design = "fa-clock-o";
      var taint = "#EDB405";
    break;

    case "Atrasados":
      var design = "fa-exclamation-triangle";
      var taint = "#E70101";
    break;
    case "Finalizados":
      var design = "fa-check-circle";
      var taint = "#1CC131";
    break;

}

    prevHtml = $("div.pull-right").eq(ght).html();
    $("div.pull-right").eq(ght).html(
           prevHtml + 
    "<a class='btn " + change + "' title='" + type + "'><p style='display: inline-block; vertical-align: top;color:" + taint + "; font-size: 1.5em; font-weight: 800;' >1</p>" +
    "<i class='fa " + design + " fa-2x' style='display: inline-block; vertical-align: top;color: " + taint + "'></i></a>"
    )

}}


$(".span2").on("slide", function (slideEvt) {
   if (slideEvt.value < $(this).data("val")){
        alert("fuera de rango");
        $(".span2").slider('setValue', $(this).data("val"));
   }
});




$(".hovertip").on("click ", function(){

if($(this).data("val") == 0 || $(this).data("val") == undefined){

    $(".trf-int-usr").css({display :"none"});

   var val = parseInt($(this).children("input").val().replace("u" ,""));

   $(this).parent().next().next().next().children("tbody").children("tr.ust" + val).css({ display : "table-row"});

     $(this).data("val", 1);

} else {

   var val = parseInt($(this).children("input").val().replace("u" ,""));
   $(this).parent().next().next().next().children("tbody").children("tr.ust" + val).css({ display : "none"});
   $(this).data("val", 0);
}


});


function insertScheduleTraffic(subject, descript ,date, user, ind){

var parent = document.querySelectorAll(".int-trf-descript tbody")[ind];

var tr_av  = document.createElement('tr');
var td1_av = document.createElement('td');
var td2_av = document.createElement('td');
var td3_av = document.createElement('td');


tr_av.className = "trf-int-usr ust" + user;
td1_av.innerHTML = subject;
td2_av.innerHTML = descript;
td3_av.innerHTML = date;

tr_av.appendChild(td1_av);
tr_av.appendChild(td2_av);
tr_av.appendChild(td3_av);
parent.appendChild(tr_av);

}

function filename(name){

var regexp = new RegExp(/[^/\\&\?]+\.\w{3,4}(?=([\?&].*$|$))/g);
matches = regexp.exec(name);

mtObj = matches.toString().replace(",", "");

return mtObj;
}

     function doSearch(fr,t, tbl) {
var d1 = fr.split("/");
var d2 = t.split("/");

var from = new Date(d1[2], d1[1]-1, d1[0]);  
var to   = new Date(d2[2], d2[1]-1, d2[0]);

        var targetTable = document.getElementById(tbl);
        var targetTableColCount;
        for (var rowIndex = 1; rowIndex < targetTable.rows.length; rowIndex++) {

            var rowData = [];

            if (rowIndex == 1) {

                targetTableColCount = targetTable.rows.item(rowIndex).cells.length;

                rowIndex = rowIndex + 1;

                  for (var colIndex = 3; colIndex < targetTable.rows.length ; colIndex++) {
                      rowData.push(targetTable.rows.item(colIndex).cells.item(4).textContent);
                      console.info(targetTable.rows.item(colIndex).cells.item(4).textContent + " nuevo bucle");
                      colIndex = colIndex + 1;
                  }

                continue;   
            }
                  console.log(rowData.length);

        for(var i=0 ;i<rowData.length;i++){

                var c = rowData[i].split("/");
                var check = new Date(c[2], c[1]-1, c[0]);
                console.info(rowIndex + " total");
                console.info(check);
                console.info(from);
                console.info(to);

                if ((check >= from) && (check <= to)){
                     console.info(rowIndex + " is");

                       targetTable.rows.item(rowIndex).style.display = 'table-row';

                } else {
                       console.info(rowIndex + " else");
                       targetTable.rows.item(rowIndex).style.display = 'none'; 
                }
                    
        }
          rowIndex = rowIndex + 1;

        }

    }



function newthum(kind){

$("div.pull-right").eq(kind).html('');

var gitString = "";

if(kind == 0){
var Hc = $(".Hc").length;
var At = $(".At").length;
var Pe = $(".Pe").length;
var Ec = $(".Ec").length;
var Pv = $(".Pv").length;
} else if(kind == 1) {
var Hc = $(".Hc-int").length;
var At = $(".At-int").length;
var Pe = $(".Pe-int").length;
var Ec = $(".Ec-int").length;
var Pv = $(".Pv-int").length;
} else {
var Hc = $(".Hc-int-ii").length;
var At = $(".At-int-ii").length;
var Pe = $(".Pe-int-ii").length;
var Ec = $(".Ec-int-ii").length;
var Pv = $(".Pv-int-ii").length;

}

if(Hc != 0){
gitString += "<a class='btn' title='Finalizados'><p style='display: inline-block; vertical-align: top;color:#1CC131; font-size: 1.5em; font-weight: 800;' >" + Hc+ "</p>" +
    "<i class='fa fa-check-circle fa-2x' style='display: inline-block; vertical-align: top;color:#1CC131'></i></a>" ;
}
   
if(Ec != 0){
gitString += "<a class='btn' title='En Curso'><p style='display: inline-block; vertical-align: top;color:#178FD0; font-size: 1.5em; font-weight: 800;' >" + Ec + "</p>" +
    "<i class='fa fa-angle-double-right fa-2x' style='display: inline-block; vertical-align: top;color:#178FD0'></i></a>";
}
        
if(Pe != 0){
 gitString += "<a class='btn' title='Pendientes'><p style='display: inline-block; vertical-align: top;color:#EDB405; font-size: 1.5em; font-weight: 800;' >" + Pe+ "</p>" +
    "<i class='fa fa-flag fa-2x' style='display: inline-block; vertical-align: top;color:#EDB405'></i></a>";
}
   
if(Pv != 0){
gitString += "<a class='btn' title='Por Vencer'><p style='display: inline-block; vertical-align: top;color:#EDB405; font-size: 1.5em; font-weight: 800;' >" + Pv + "</p>" +
    "<i class='fa fa-clock-o fa-2x' style='display: inline-block; vertical-align: top;color:#EDB405'></i></a>";
}
    
if(At != 0){
    gitString += "<a class='btn' title='Atrasados'><p style='display: inline-block; vertical-align: top;color:#E70101; font-size: 1.5em; font-weight: 800;' >" +At +"</p>" +
    "<i class='fa fa-exclamation-triangle fa-2x' style='display: inline-block; vertical-align: top;color:#E70101'></i></a>";
}

$("div.pull-right").eq(kind).html(gitString);

}
function checkDelExt(){
if($("#subject").val() == ""){
  return "Asunto";
}
if($("#end-data").val() == ""){
  return "Fecha Final";
}
if($("#st-description").val() == "" ){
   return "Descripcion";
}
return true;
}
function checkIntDel(){

if($("#subj-int").val() == ""){
  return "Asunto";
}
if($("#descript-int").val() == ""){
  return "Descripcion";
}
if($("#int-del").val() == ""){
  return "Usuario";
}
if($(".date-int-finish").val() == ""){
  return "Fecha fecha de termino";
}
return true;
}



function hovertip(object){

console.info($(object).children('input').val() + " esto era para comp si era vacio");
if($(object).data("val") == 0 || $(object).data("val") == undefined){

    $(".trf-int-usr").css({display :"none"});

   var val = parseInt($(object).children("input").val().replace("u" ,""));

   $(object).parent().next().next().next().children("tbody").children("tr.ust" + val).css({ display : "table-row"});

     $(object).data("val", 1);

} else {

   var val = parseInt($(object).children("input").val().replace("u" ,""));
   $(object).parent().next().next().next().children("tbody").children("tr.ust" + val).css({ display : "none"});
   $(object).data("val", 0);
}
}










//funcion prototipo
$(".ifile").on('dblclick', function(){
  var fi = $(this).children('span').html();
    downloadFile("../" + fac + "/" + mainuser + "/" + fi);
});


function rewind(obj){
var  fnam = obj.previousSibling.childNodes[0].nodeValue;
var  usr = $("input[name=user]").val();
var stsk = $("input[name=issId]").val();
if($("form#upload").attr("action") == "../backend/upload.php"){
   var type = 0;
} else {
   var type= 1;
}
 $.ajax({
          type: "POST",
          url:"../backend/rewind.php?fac=" + fac + "&usr=" + usr  + "&fname=" + fnam + "&type=" + type + "&stsk=" + stsk,
          success : function (data){
              console.info(data);
              bootbox.alert("archivo eliminado");
          }
 });
}
function getFuzzyIndex(string, obj, def){

console.log("#" + obj + " tbody > tr." + def);

$("#" + obj + " tbody > tr.task").hide().filter(":containsCI('" + string + "')").show();

if(string == ""){

  $("#" + obj + " tbody > tr.task").css({ display : "none"});
  $("#" + obj + " tbody > tr." + def).css({ display : "table-row"});

}
}

document.onkeydown = checkKey;

function checkKey(e) {
    e = e || window.event;
    if (e.keyCode == '38') {

          if ($(".tt-selectable:contains('" + $(document.activeElement).val() + "')").index(".tt-selectable") == 0){
              $(".tt-selectable:contains('" + $(document.activeElement).val() + "')").css("background-color", "#FFF");
        } else {
              $(".tt-selectable:contains('" + $(document.activeElement).val() + "')").css("background-color", "#ACE1F2");
        }
        $(".tt-selectable:contains('" + $(document.activeElement).val() + "')").next().css("background-color", "#FFF");
    }
    else if (e.keyCode == '40') {
      $(".tt-selectable:contains('" + $(document.activeElement).val() + "')").css("background-color", "#ACE1F2");
      $(".tt-selectable:contains('" + $(document.activeElement).val() + "')").prev().css("background-color", "#FFF");
    }
}

function isOdd(num) { return num % 2;}

$("input.swt-boo").on('switchChange.bootstrapSwitch', function (event, state){
    if($(this).parent().parent().parent().children('i').eq(0).css("color") == "rgb(30, 87, 153)" ){
             $(this).parent().parent().parent().children('i').eq(0).css("color", "gray");
             $(this).parent().parent().parent().children('i').eq(1).css("color", "rgb(30, 87, 153)");
             switcher = 1;
             //swUsr($(this).parent().parent().parent().parent().parent().children("input.st").val());
    } else {
             $(this).parent().parent().parent().children('i').eq(1).css("color", "gray");
             $(this).parent().parent().parent().children('i').eq(0).css("color", "rgb(30, 87, 153)"); 
             //swUsr($(this).parent().parent().parent().parent().parent().children("input.st").val());
             switcher = 0;
    }
});

$(".padlock:first").on('click', function(){
   bootbox.confirm("Desea enviar el requerimiento a las personas designadas?", function (confirm){
     if(confirm){
        $.ajax({ type: "POST", url:"../backend/unlock-new.php?stsk_id=" + $(".sub-del").eq(0).children('input#current-task').val() + "&iss_id=" + $("#issId").val() + "&type=0&fac=" + fac,
          success : function(data){
          $(this).css({ color: "green"});
            console.info(data);
            kenin[0].selectize.clear();
        }
      });
            $("#kitkat li").eq(3).removeClass('active');$("#kitkat li").eq(2).addClass('active');
            $("#tasks-own").removeClass('active in');$("#require").addClass('active in');
            $("#D-drop").empty();
            $(".eras").val(''); 
     }
   })
});
function swUsr(stskId){
  $.ajax({
       type: "POST",
       url: "../backend/resp.php?stsk_id=" + stskId +"&fac=" + fac + "&muser="+ document.querySelector('#muser').value,
       success : function (data){
        console.info(data);
         
       }
  })
}


$("#upgrade-own").on('click', function (){
upgradeOwn($("#set-pro-own").attr("data-stsk"), $("#set-pro-own").attr("data-iss"), $('.span2').eq(1).val() , $("#own-descript").val(), $("#own-subtasks").val());

});

function upgradeOwn(stskId, issId, percent, descript, subject){

var ind = (parseInt($("#current-task").val())/2);
var date = new Date();

  $.ajax({
      type: "POST",
      url: "../backend/upgrade-own.php?stsk=" + stskId + 
      "&iss="+ issId + 
      "&percent=" + percent + 
      "&subject=" + subject+ 
      "&descript=" + descript +
      "&muser=" + $("#muser").val() +
      "&fac=" + fac,
      success: function (data){
          $(".task").eq(ind).next().children('td').children("div.progress").children('.bar').css({ width: percent + "%"});
          $(".task").eq(ind).next().children('td').find("span.muted").html(percent+"%");
          $(".task").eq(ind).find(".person-sw").replaceWith("<i class='fa fa-user spac'></i>");
          $(".task").eq(ind).next().find(".collaborates").html("<a onclick='alterExt(this)' class='hovertip extUsr' data-val='" + percent + "' title='Yo'>" +
            "<img src='../" + fac + "/img/" + mainuser + "_opt.jpg' class='group'>" +
            "<i class='fa fa-check-circle finished'></i>" +
            "<input type='hidden' value=" + mainuser +">" +
            "</a>");
          // adding The traffic

           $(".task").eq(ind).next().children('td').find(".ex-del-par > tbody").append( "<tr class='eu" + mainuser + "'><td>" + subject +"</td><td>" + descript + "</td><td>" + ('0' + date.getDay()).slice(-2) + "/" + ("0" + (date.getMonth()+1)).slice(-2) + "/" + date.getFullYear() + "</td></tr>");
          $("#kitkat li").eq(3).removeClass('active');$("#kitkat li").eq(2).addClass('active');
          //$("#tasks-own").removeClass('active in');$("#require").addClass('active in');
          $("#set-pro-own").removeClass('active in');$("#require").addClass('active in');
        bootbox.alert("Progreso Grabado!");
      }
  })
}


$(".extUsr").on('click', function (){
  
   var ind = $(this).parent().next().parent().parent().prev().index('tr.task');
   var percent = $(this).attr("data-val");
   var usrId = $(this).children('input').val();
   var filCont = $(this).parent().next();

   for (i=0; i < filCont.children('div.file-contents').children('a').length; i++){
        if (filCont.children('div.file-contents').children('a').eq(i).attr('href').search(usrId + "_in") == -1){
            filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "0.3"});
        } else {
           filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "1"});
        }
   }

  filCont.next().next().children('tbody').children('tr').css({ display : "none"});
  filCont.next().next().children('tbody').children('tr.eu' + usrId).css({ display : "table-row"});
        var selter = d3.select(document.querySelectorAll('.great-chart')[ind]).transition().each('start',function (d){ $("#pro-audio")[0].play() }).each('end', function (d){ setTimeout(function(){$("#pro-audio")[0].pause() ; $("#pro-audio")[0].currentTime = 0 }, 800)})
        var rp1 = radialProgress(document.querySelectorAll('.great-chart')[ind])
                .label('')
                .diameter(125)
                .value(percent)
                .render();
     //$(".radial-svg").

});

$(".viewToggle").on('click', function(){
  $(this).parent().parent().next().children('td').children().not("info-content").fadeToggle("fast", function(){
       $(this).parent().parent().next().children('td').children("info-content").fadeToggle("fast");
  });
});

function alterExt(object){
     var ind = $(object).parent().next().parent().parent().prev().index('tr.task');
   var percent = $(object).attr("data-val");
   var usrId = $(object).children('input').val();
   var filCont = $(object).parent().next();

   for (i=0; i < filCont.children('div.file-contents').children('a').length; i++){
        if (filCont.children('div.file-contents').children('a').eq(i).attr('href').search(usrId + "_in") == -1){
            filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "0.3"});
        } else {
           filCont.children('div.file-contents').children('a').eq(i).css({ opacity : "1"});
        }
   }

  filCont.next().next().children('tbody').children('tr').css({ display : "none"});
  filCont.next().next().children('tbody').children('tr.eu' + usrId).css({ display : "table-row"});
        var selter = d3.select(document.querySelectorAll('.great-chart')[ind]).transition().each('start',function (d){ $("#pro-audio")[0].play() }).each('end', function (d){ setTimeout(function(){$("#pro-audio")[0].pause() ; $("#pro-audio")[0].currentTime = 0 }, 800)})
        var rp1 = radialProgress(document.querySelectorAll('.great-chart')[ind])
                .label('')
                .diameter(125)
                .value(percent)
                .render();

}

$(".fr").on("click", function(){

  if($(this).data("val") == undefined || $(this).data("val") == 0 ){

     uploaderInt($(this).parent().prev());
      $(this).data("val", 1);
  } else {
    $(this).parent().prev().fadeToggle("slow");
  }
    
});

function graphAddedFiles(object, names){
  var nname = names.split("|");
  var filstr = "";

  for (i=0; i < nname.length-1 ; i++){
     var extension = nname[i].substring(nname[i].length -3 , nname[i].length);
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
            cor = "#44D933";
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
    
    filstr += '<a href="../reply/' + nname[i] + '" title="' + nname[i] +  '" download><i class="fa fa-file-' + setClass+ ' fa-2x" style="color:' + cor + '; margin: 0 0.4em"></i></a>';

  }

object.html(filstr);
filstr = "";

}
</script>

<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}
//"../backend/delegate_internal.php?muser=118&user=2&fechaF=2015-05-20 10:00:00&subject=manual&descript=manualmente conf&startD=2015-05-10 10:00:00&fac=10000&main_stsk=0&keyfile="
?>