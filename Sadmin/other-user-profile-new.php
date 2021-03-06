<?php ini_set('session.gc_maxlifetime', 27000);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(27000);
session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'sadmin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//refresh variables
mysqli_query($datos, "DELETE FROM PSEUDO WHERE PSD_FAC_CODE = " . $_SESSION['TxtFacility']);
mysqli_query($datos, "UPDATE SUBTASKS SET STSK_ANCIENT_PRO = STSK_PROGRESS WHERE (STSK_CHARGE_USR <> " . $_SESSION['TxtCode'] . " AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_MAIN_USR = " . $_SESSION['TxtCode'] . " AND STSK_PROGRESS <> STSK_ANCIENT_PRO )");

$Query_team       = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'admin') ORDER BY USR_ID;");
$Query_subtask    = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_DESCRIP, B.EST_DESCRIPT, A.STSK_FINISH_DATE, B.EST_COLOR, A.STSK_PROGRESS, A.STSK_LOCK, A.STSK_TICKET, A.STSK_RESP FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 0 AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " ) ORDER BY STSK_FINISH_DATE " );
$Query_alerts_ext = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_TYPE = 0 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . ") GROUP BY STSK_STATE");
$Query_alerts_int = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_MAIN_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR = STSK_MAIN_USR AND STSK_TYPE = 1) GROUP BY STSK_STATE");
$Query_alerts_ii  = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR <> STSK_MAIN_USR AND STSK_LOCK = 1 AND STSK_TYPE = 1) GROUP BY STSK_STATE");

$str_trf_usr      = "SELECT DISTINCT A.TRF_USER, CONCAT(B.USR_NAME , ' ' ,  B.USR_SURNAME) FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE (TRF_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'admin') ORDER BY TRF_USER; ";
$Query_trf_usr    = mysqli_query($datos, $str_trf_usr);
$Query_team_int   = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'admin');");
// internal requirements

$query_internal = "SELECT A.STSK_ID, A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE, A.STSK_ISS_ID, A.STSK_TICKET FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_ISS_ID = STSK_ID)  ORDER BY STSK_FINISH_DATE";
$internal       = mysqli_query($datos, $query_internal);
$quntum         = mysqli_query($datos, "SELECT COUNT(STSK_ID) AS CONTADOR FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode']);

$vlist = "Jefaturas|";


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

$intList = "Jefaturas,";

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

$query_incoming = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_MAIN_USR, CONCAT(B.USR_NAME, ' ' , B.USR_SURNAME) , A.STSK_MAIN_USR, A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE, A.STSK_ISS_ID, A.STSK_RESP, A.STSK_TICKET FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_CHARGE_USR <> STSK_MAIN_USR)");

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
    <style type="text/css">.attach,.ifile,.ifile-ii,.sub-del{vertical-align:top}#audititle,#froback,.collaborates p,.iss-descript{font-style:italic}#back-to-main i,.padlock{cursor:pointer}.done{background:-moz-linear-gradient(top,#daedb1 0,#abd78d 28%,#54ca50 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#daedb1),color-stop(28%,#abd78d),color-stop(100%,#54ca50))!important;background:-webkit-linear-gradient(top,#daedb1 0,#abd78d 28%,#54ca50 100%)!important;background:-o-linear-gradient(top,#daedb1 0,#abd78d 28%,#54ca50 100%)!important;background:-ms-linear-gradient(top,#daedb1 0,#abd78d 28%,#54ca50 100%)!important;background:linear-gradient(to bottom,#daedb1 0,#abd78d 28%,#54ca50 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#daedb1', endColorstr='#54ca50', GradientType=0)!important}.warning{background:-moz-linear-gradient(top,#fefcea 0,#fefcea 0,#f1da36 26%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#fefcea),color-stop(0,#fefcea),color-stop(26%,#f1da36))!important;background:-webkit-linear-gradient(top,#fefcea 0,#fefcea 0,#f1da36 26%)!important;background:-o-linear-gradient(top,#fefcea 0,#fefcea 0,#f1da36 26%)!important;background:-ms-linear-gradient(top,#fefcea 0,#fefcea 0,#f1da36 26%)!important;background:linear-gradient(to bottom,#fefcea 0,#fefcea 0,#f1da36 26%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fefcea', endColorstr='#f1da36', GradientType=0)!important}.delay{background:#ff5335;background:-moz-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ff5335),color-stop(100%,#d00e04));background:-webkit-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-o-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-ms-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:linear-gradient(to bottom,#ff5335 1%,#d00e04 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5335', endColorstr='#d00e04', GradientType=0)}.OwnComp{width:100%}.OwnComp-bars{background-color:#FFF;margin:.5em;border:4px solid transparent;padding:1em 1.5em;width:80%}#Audi-Display,#Com-Display,#Urgent-Display{height:0;visibility:hidden;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.sub-del{width:55%;display:inline-block}#delegates{width:100%;position:relative;float:left}.require-subtasks{padding:0 1em;margin:.5em}#st-description{width:95%}.attach{display:none}.file-contents,.file-sent{width:80%}.file-contents,.file-contents p{display:inline-block;vertical-align:top}.display-progress{display:none}.At-int-ii{display:table-row}.Ec-int-ii,.Hc-int-ii,.Pe-int-ii,.Pv-int-ii{display:none}.At-int{display:table-row}.Ec-int,.Hc-int,.Pe-int,.Pv-int{display:none}.At{display:table-row}.Ec,.Hc,.Pe,.Pv{display:none}.ifile,.ifile-ii{margin:.5em;display:inline-block;cursor:pointer}.iname{display:block;text-align:left}#D-drop:after,#wrap-D,.collaborates p{display:inline-block}#wrap-D{max-height:20em}.toggle-attach{float:right;background-color:gray;border-radius:15px}.toggle-attach i{color:#fff;padding:.2em}#D-drop{height:15em;width:18em;float:right;background-color:#fff;border-radius:8px;border:29px solid #ececec;border-bottom:57px solid #ececec;overflow-y:auto;overflow-x:hidden}#D-drop:after{content:"ARRASTRE AQUI SUS ARCHIVOS";color:#c2bdbd;position:relative;vertical-align:bottom;text-align:center;top:8em;left:3.5em;font-size:.8em}#wrap-D,.attach{transition:all 600ms ease-in-out}#wrap-D,.attach,.spac{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out}.after:after{content:"Arrastre aqui sus archivos"}.no-after:after{content:""}.collaborates{width:80%}#audititle,#wrapaudi,.info-content{width:100%}.collaborates p{vertical-align:top;font-size:.8em}#audititle{color:gray}#wrapaudi{display:block}.incoming-files{display:none}.golang,.iss-descript,.wrap-events{vertical-align:top;display:inline-block}#froback{position:relative;float:right;color:#a9a9a9}.spac{margin-right:.3em;color:#1e5799;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1e5799', endColorstr='#207cca', GradientType=0);transition:all 600ms ease-in-out}.golang i,.spac{font-size:1.5em}.iss-descript{font-size:.7em}.chrono,.utrf,.wrap-charts{display:none}.events{color:#24B56C;font-size:1.5em}.wrap-events{width:auto;margin:0 .5em}#back-to-main i:hover{color:#90ee90}.user-schedule{width:100%;height:auto}strong{font-size:.8em}.progressDisplay li{padding:5px}.drag-drop,.group,.wrap-lock{display:inline-block}.bolder{font-weight:bolder}.group{width:8%;padding:6px;border:1px solid #d3d3d3;border-radius:50%;vertical-align:top;-webkit-transition:all 100ms ease-in-out;-moz-transition:all 100ms ease-in-out;transition:all 100ms ease-in-out}.group:hover{border:1px solid orange;width:10%}#descript-int{width:100%}.af{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.af:hover{color:#F70202;transition:all 600ms ease-in-out}.af:hover,.finished{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out}.finished{color:#17D221;font-size:1.5em;bottom:-2.2em;right:.5em;position:relative;opacity:0;transition:all 600ms ease-in-out}.trf-int-usr{transition:all 600ms ease-in-out}.trf-int-usr,.trf-int-usr:hover{-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out}.trf-int-usr:hover{background-color:#d3d3d3;transition:all 600ms ease-in-out}.padlock,.tt-selectable{background-color:#FFF;transition:all 600ms ease-in-out;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out}.padlock{color:gray;border:2px solid #fff;transition:all 600ms ease-in-out}.padlock:hover{font-size:2.5em;color:#3CF96B;border:2px solid #3CF96B;border-radius:50%;transition:all 400ms ease-in-out;padding:9px 16px}.file-opac,.padlock:hover{-webkit-transition:all 400ms ease-in-out;-moz-transition:all 400ms ease-in-out}.file-opac{transition:all 400ms ease-in-out}.drag-drop,.dropzone{transition:background-color .3s}.wrap-lock{vertical-align:top;float:right;position:relative;right:1em}#outer-dropzone{height:140px}#inner-dropzone{height:80px}.dropzone{background-color:#ccc;border:4px dashed transparent;border-radius:4px;margin:10px auto 30px;padding:10px;width:80%}.drop-active{border-color:#aaa}.drop-target{background-color:#29e;border-color:#fff;border-style:solid}.drag-drop{min-width:40px;padding:2em .5em;color:#fff;background-color:#29e;border:2px solid #fff;-webkit-transform:translate(0,0);transform:translate(0,0)}.display-pro-int,.ex-del-par>tbody>tr{display:none}.drag-drop.can-drop{color:#000;background-color:#4e4}@media screen and (max-width:1024px){.slider-horizontal{margin:0 20%}}@media screen and (max-width:640px){.slider-horizontal{margin:0 10%}}@media screen and (max-width:500px){.slider-horizontal{margin:0 5%}}.slider-horizontal{margin:0 25%}.great-chart,.int-files-for,.int-files-to,.person-sw,pre>a{display:inline-block;vertical-align:top}.g-wrap{width:100%}.great-chart{position:relative;top:-3em}.person-sw{position:relative}pre>a,pre>a>p{max-width:3em;max-height:3em}.int-files-for,.int-files-to{width:80%}.file-contents:before,.front-sent:before,.ii-files-sent:before,.int-files-for:before{content:"\f053";font-size:2em;color:#add8e6;font-family:FontAwesome;position:relative}.file-sent:before,.front-received:before,.ii-files:before,.int-files-to:before{content:"\f054";font-size:2em;color:#90ee90;font-family:FontAwesome;position:relative;top:.5em}svg{width:100px!important}.front-received:before,.front-sent:before{top:0}.drop-zone:after,.newtext:after{font-style:italic;font-family:arial;top:1.7em;left:33%;position:relative}.front-received,.front-sent{margin-left:1.5em;display:flex;width:100%}.wrap-int-files{width:100%;margin:1em 0}.drop-zone:after{content:"Arrastre aqui sus archivos";color:#d3d3d3;-webkit-transition:all 800ms ease-in-out;-moz-transition:all 800ms ease-in-out;transition:all 800ms ease-in-out}.drop-zone,.newtext{transition:all 800ms ease-in-out}.drop-zone,.newtext,.newtext:after{-webkit-transition:all 800ms ease-in-out;-moz-transition:all 800ms ease-in-out}.newtext:after{content:"Archivos Agregado!";color:#90ee90;transition:all 800ms ease-in-out}.comentary{width:85%;border-radius:15px;resize:none;padding:.5em 1em}.wcom{font-size:1.2em;color:#d3d3d3;font-style:italic}.file-flex{width:2em;height:2em}
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

                        <a href="oupn-test.php">Ambiente de prueba</a>
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
                                            <li class="switcher" id="Pe"><a href="#">Pendientes</a></li>
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
                                         case 1:
                                             $type = "fa-flag";
                                             $taint = "#EDB405";
                                             $tuba = "Pendiente";
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
                                         
                                        if($stsk[9] == 1 || $stsk[9] == 2){

                                           if($stsk[3] == "Finalizada"){
                                                $lock = "disabled";
                                              } else {
                                                $lock = "";
                                              }
                                                
                                            } else {

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
                                            ?> 
                                        <tr class="task <? printf($class) ?>">
                                            <td class="cell-icon"><? echo $stsk[8] ?></td>
                                            <td class="cell-title"><? printf($stsk[2])  ?></td>
                                            <td class="cell-status">
                                            <b class="due" style="background-color: <? printf($stsk[5]) ?>;"><? printf($stsk[3]) ?></b></td>
                                            <td class="cell-title" style="min-width: 80px;"><button it="" class="btn btn-small forward" style="margin-right: 1em"><i class="fa fa-chevron-circle-right"></i></button>
                                          <? if ($stsk[9] == 1  ) { ?>
                                                   <i it="<? echo $stsk[9]  ?>" class="fa fa-user spac"></i>
                                                   <i class="fa fa-search viewToggle" style="color: lightblue; font-size: 1.5em"></i>

                                          <?  } elseif ($stsk[9] == 0 ) {    ?>

                                                    <i it="<? echo $stsk[9]  ?>" class="fa fa-group spac"></i>
                                                    <i class="fa fa-search viewToggle" style="color: lightblue; font-size: 1.5em"></i>
                                                   
                                          <?  } else { ?>

                                                   <div class="person-sw">
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
<? $shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP , CONCAT(B.CTZ_NAMES , ' ', B.CTZ_SURNAME1, ' ', B.CTZ_SURNAME2) AS NAME, B.CTZ_ADDRESS, B.CTZ_TEL, A.ISS_TICKET, B.CTZ_GEOLOC, E.CAT_DESCRIPT, A.ISS_PROGRESS , A.ISS_COMENTARY FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) INNER JOIN CAT E ON(E.CAT_ID = A.ISS_TYPE) WHERE ISS_ID = " . $stsk[1] ));

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
                                        <div class="wcom" align="center">
                                            <? if( is_null($shine['ISS_COMENTARY']) ||  $shine['ISS_COMENTARY'] == "" ) { ?>
                                          <textarea class="comentary" placeholder="Respuesta al ciudadano"></textarea>
                                          <i class="fa fa-chevron-circle-right fa-3x send-com" style="color: lightgreen"></i>
                                            <? } else { 
                                               echo $shine['ISS_COMENTARY'];
                                              } ?>
                                        </div>
                                        <p class="adjuste">
                                            <strong>Grado de progreso</strong><span class="pull-right small muted"> <? echo $shine['ISS_PROGRESS'] ?>%</span>
                                        </p>
                                            <div class="progress tight">
                                                <div class="bar forward" style="width: <? echo $shine['ISS_PROGRESS'] ?>%"></div>
                                            </div>
                                            <div class="w-ap">
                                              <i class="fa fa-files-o fa-2x bk-fi" style="margin-right: 2em"></i>
                                              <div ondrop="dropBack(event, this)" ondragover="allowDrop(event)" class="drop-zone" style="display:none; width: 80%; margin: .7em 5em; border:5px dashed orange;height: 5em"></div>
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                          
                                            if(preg_match_all("/_\[" . $stsk[1] . "\]_/", $archivos2) == 1){
                                     
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                  break;
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                    <input id="end-data" type="text" placeholder="Fecha Termino" class="datetimepicker" styles="vertical-align:top; display: inline-block;"/><br><br>
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                            <div class="module-body table" style="padding: 15px 0;">
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
                                                                <td class="cell-icon" ><? echo $fila5[12]?></td>
                                                                <td class="cell-title"><div><? echo $fila5[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due int-desglo" style="background-color:<? echo $fila5[8]; ?>"  ><? echo $fila5[6]; ?></b></td>
                                                                <td class="cell-title int-forward" style="cursor:pointer;" data-lock="<? printf($lock) ?>"><i class="fa fa-chevron-circle-right"></i></td>
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

             <?  
             $pre_Ruan = mysqli_query($datos, "SELECT A.STSK_ID FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_TYPE= 1 AND STSK_TICKET = '" . $fila5[12] . "' AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'back-user' ) ;"); // progreso de los gomas...
             $part = mysqli_query($datos, "SELECT A.STSK_CHARGE_USR, CONCAT(B.USR_NAME, ' ', B.USR_SURNAME), A.STSK_ID, A.STSK_PROGRESS, A.STSK_ISS_ID, B.USR_RANGE, A.STSK_TICKET FROM SUBTASKS A INNER JOIN USERS B ON(B.USR_ID = A.STSK_CHARGE_USR) WHERE (STSK_TYPE= 1 AND STSK_TICKET = '" . $fila5[12] . "' AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " ) ORDER BY USR_RANGE;"); 
                               while($prt = mysqli_fetch_row($part)){
                                                             
                                                             if($prt[5] == 'admin'){   

                                                                 ?>                                          
                                                                        <a data-per="<? echo $prt[3] ?>" class="hovertip" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($prt[1]))))) ?>">
                                                                            <img src="../<? echo $_SESSION['TxtFacility']  ?>/img/<? echo $prt[0]; ?>_opt.jpg" class="group" >
                                                                            <i class="fa fa-check-circle finished" style="opacity: <? if ($prt[3] == 100 ) { ?> 1 <? } else { } ?>"></i>
                                                                            <input type="hidden" value="u<? printf($prt[0])?>">
                                                                        </a>

                                                                        <?
                                                                        } 
                                                                    }
                                                                    mysqli_data_seek($part, 0);
                                   

                                      $Ruan = array();
                                      while($a_Ruan = mysqli_fetch_row($pre_Ruan)){
                                          array_push($Ruan, $a_Ruan[0]);
                                      }


                                                                 ?>
                                                                    </div>
                                             <div class="wrap-int-files" >
                               <div class="int-files-to">
            <?    
                      while($fint = mysqli_fetch_row($part)){

                          if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/", 0775, true); 
                              } 
                           
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/" )){
                                         
                                          $file_extension = "";
                                          
                                           while (false !== ($archivos2 = readdir($handler2))){
                                           
                                         if(preg_match_all("/_\[" . ($fint[4]) . "\]_/", $archivos2) == 1){

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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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

                                               if($fint[5] == 'admin'){
                                                   break;
                                               }
                                               
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
                           

                                   if($fint[5] == "admin") {
                                  
                                        $a_files = scandir("../" . $_SESSION['TxtFacility'] . "/" . $fint[0] . "_alt/" );
                                           echo "<script>console.info('count ruan : " . $fint[0] . "')</script>";

                                        //ruan...
                                        for($i=0; $i < count($Ruan) ; $i++){
  
                                             foreach ($a_files as $str){ 

                                               if (preg_match ("/_\[" . $Ruan[$i] ."\]_/", $str, $m)){

                                              $extension = substr($str, -3);
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
                                                      break;
                                                 } 

                                                 ?>
                                                 <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? echo $fint[0] ?>_alt/<? printf($str) ?>" download>
                                                     <p class="ifile-ii" title="<? printf(preg_replace("/\](.*?)\./", "]_" . $fint[0] . ".", $str)) ?>">
                                                         <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                         <span class="iname"></span>
                                                     </p>
                                                 </a>

                                                  <?
                                                     
                                                       }
                                                   }
                                              }
                                           }//if admin
                          
                                       }// while fint

                                                mysqli_data_seek($part, 0);

                                   $s_files = scandir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/" );
                    
                                    foreach ($s_files as $fstr) {
                                       echo "<script>console.info('archivo encontrado : " . $fstr . "')</script>";
                                       while($sf = mysqli_fetch_row($part)){
                                       
                                             if (preg_match ("/_\[" . $sf[2] ."\]_/", $fstr, $m)){
                                             $extension = substr($fstr, -3);
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
                                                      break;
                                                 } ?>

                                                 <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? echo $_SESSION['TxtCode'] ?>_alt/<? printf($fstr) ?>" download>
                                                     <p class="ifile-ii" title="<? printf($fstr) ?>">
                                                         <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                         <span class="iname"></span>
                                                     </p>
                                                 </a>

                                                 <?
                                             }
                                           
                                        }
                                      mysqli_data_seek($part, 0);
                                    }

 mysqli_data_seek($part, 0);

                                                ?>
                                                </div>
                                             <div class="int-chart" style="display: inline-block; vertical-align: top;"></div>
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
                                                                <td class="cell-icon"><? echo $ii[13] ?></td>
                                                                <td class="cell-title"><div><? echo $ii[5]; ?></div></td>
                                                                <td class="cell-status"><b class="due ii-desglo" style="background-color:<? echo $ii[8]; ?>"><? echo $ii[6]; ?></b></td>
                                                                <td class="cell-title" style="cursor:pointer;"><i class="fa fa-chevron-circle-right ii-forward"></i>
                                          <? if ($ii[12] == 1 ) { ?>
                                                   <i it="<? echo $ii[12]  ?>" class="fa fa-user spac"></i>

                                          <?  } elseif ($ii[12] == 0 ) {    ?>

                                                    <i it="<? echo $ii[12]  ?>" class="fa fa-group spac"></i>
                                                    
                                          <?  } elseif ($ii[12] == 2) { ?>

                                                   <div class="person-sw-int" it="<? echo $ii[12] ?>">
                                                      <i class="fa fa-user spac" ></i>
                                                      <input type="checkbox" class="swt-boo-int" checked="true"  data-label-width="3" data-size="mini" data-on-color="info"  data-on-color="default" data-on-text="&ensp;" data-off-text="&ensp;">
                                                      <i class="fa fa-group spac" style="color: gray;" ></i>
                                                   </div>
                                                 
                                            <?   } ?>

                                                                </td>
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
                                                      case ($extension =='ppt' || $extension =='ptx' ):
                                                      $file_extension = "powerpoint-";
                                                      $cor = "#B8005C";
                                                      break;
                                                      case ($extension =='mp3'):
                                                      $file_extension = "audio-";
                                                      $cor = "#FF9900";
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
                                              <div class="ii-files-sent">




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
                                          <input type="text" placeholder="Fecha de entrega" class="datetimepicker date-int-finish" style=" width: 24%;display: inline-block; vertical-align: top;" >
                                          <textarea id="descript-int" value="" placeholder="Describa el requerimiento" style="width:93%"></textarea>
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
    var dateTime     = "2036/12/29";
    var  objeto, uploader;

    var ttt = "";

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

$("td[data-lock=disabled]").unbind("click");

kenin = $('#delegates').selectize({
plugins: ['remove_button'],
delimiter: ',',
placeholder: "Destinatarios",
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
placeholder: "Destinatarios",
hideSelected: true, 
persist: false,
create: false,
openOnFocus: true,
onChange : function(){

},
onItemAdd: function(item){
    console.info(item);
       user_send = $('#int-del').val();

       keyFile = RandomString(8);
       if($("#up-int").attr("id") == undefined){
              $("#up-own").empty();
              uploaderInt($("#up-own"), "", user_send, stsk_send , "internal", keyFile);
       } else {
               uploaderInt($("#up-int"), "", user_send, stsk_send , "internal", keyFile);
       }


      }
});

kenin[0].selectize.clear();
selectInt[0].selectize.clear();

$("input[type=checkbox].swt-boo").bootstrapSwitch();
$("input[type=checkbox].swt-boo-int").bootstrapSwitch();

progressbar =  $('.span2').slider({ step: 10 , max: 100, min: 0});

$("i.fa-lock").parent().unbind('click');
$("i.fa-lock").parent().parent().children('td:nth-child(5)').off();

$('.datetimepicker').datetimepicker({
    step:5,
    lang:'es',
    format:'Y/m/d',
    timepicker: false,
    minDate : '-1970/01/02', 
    onShow: function (ct){
      console.info(ct);
      console.info(dateTime);
        this.setOptions({
            maxDate : dateTime,
            format  : 'd/m/Y'
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

ticket = $()
dateTime = AmericanDate($(this).next().html());

       mode = "delegate";
 var indice = $(this).index();
 var ids    = $("#int-table > tbody .task").length;

stsk_send = ids;
console.log("stsk_send is :" + ids);
$("#del-int-req").data("val",indice );
$("#send-int").data("val", ids);


$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');


});

$(".ii-forward").click(function(){

dateTime = AmericanDate($(this).next().html());

 remoteUser = $(this).parent().parent().children("input").eq(1).val();
 st_ii      = $(this).parent().parent().children("input").eq(0).val();
 ii_iss     = $(this).parent().parent().children("input").eq(2).val();
 ii_ind     = $(this).index(".ii-forward");
$("#up-int").empty();

if($(this).next().hasClass('person-sw-int')){

if($(this).next().find('.swt-boo-int').bootstrapSwitch('state') == true){
$("#stsk-code-ii").val(st_ii);
$("#stsk-user-ii").val(remoteUser);
$("#stsk-user-ii").attr("name", "muser");

percent = parseInt($(this).parent().parent().next().children('td').children('p').children('span').html());
console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);
$("#back-own").data("val", 1); // esto es para que el boton back se devuelva a la vista interna
$("#int-require").removeClass('active in');
$("#set-pro-own").addClass('active in');

} else {

mode = "first";
$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');

}

} else if($(this).next().hasClass('fa-user')){

$("#stsk-code-ii").val(st_ii);
$("#stsk-user-ii").val(remoteUser);
$("#stsk-user-ii").attr("name", "muser");

percent = parseInt($(this).parent().parent().next().children('td').children('p').children('span').html());
console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);
$("#back-own").data("val", 1);
$("#int-require").removeClass('active in');
$("#set-pro-own").addClass('active in')

}  else {

mode = "first";
$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');


}

});

$("#back-ii").click(function(){
    $("#set-pro-int").removeClass('active in');$("#int-require").addClass('active in');
});

$("#back-own").click(function(){
  if($(this).data("val") == undefined || $(this).data("val") == 0 ){
       $("#set-pro-own").removeClass('active in');$("#require").addClass('active in');

  } else {
    $("#set-pro-own").removeClass('active in');$("#int-require").addClass('active in');
  }
  
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

$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');
$(".incoming-files").css({ display : "none"});
mode = "delegate";

} else {

$("#back-own").data("val", 0);
$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#require").removeClass('active in');$("#set-pro-own").addClass('active in');
uploaderInt($("#up-own"), iss_ident, $("#muser").val(), stsk_id , 0);

}

} else if($(this).next().hasClass('fa-user') ){


$("#set-pro-own").attr("data-stsk", stsk_id );
$("#set-pro-own").attr("data-iss", iss_ident );

$("#back-own").data("val", 0);

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

       user_send = $('#int-del').val();
       console.info(user_send);
      console.info($("#up-int").html());
           $("#up-int").empty();
           $("#up-own").empty();
      console.info($("#up-int").html());

       console.info('está llegado desde aqui $(.del-int) keyFile :' + keyFile );

$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');

});

$("#int-back").on('click', function(){
  
$("#del-int-req").removeClass('active in');$("#int-require").addClass('active in');
$("#up-int").empty();

});


$("#send-int").on('click', function(){
 if(checkIntDel() == true){
  if($("#back-own").data("val") == 0 || $("#back-own").data("val") == undefined){
     intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#int-table > tbody .task").length-1, 0 , "")
  } else {
     intDel($("#int-del").val() , $("#subj-int").val(), $("#descript-int").val() , $(".date-int-finish").val(), $("#int-table > tbody .task").length-1,  st_ii , $(".ii-forward").eq(ii_ind).parent().siblings('.cell-icon').html());
  }
  


   } else {
    bootbox.alert("Falta el siguiente campo :" + checkIntDel());
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

$(this).attr("disabled", "true");

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

           $(this).attr("disabled", "false");

           var filestring = "";
           var users = data.split("|");
        
           bootbox.alert("Requerimiento delegado existosamente");

                var target    =  $("#current-task").val();
                var key_main  = document.querySelectorAll(".collaborates")[target/2]; // aqui se cambió por una razón inexplicable

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

               for(i=0; i < far.length-1; i++ ){
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
            case "ppt" : 
            setClass = "powerpoint-o";
            cor = "#B8005C";
        break; 
               case "ptx" : 
            setClass = "powerpoint-o";
            cor = "#B8005C";
        break; 
              case "mp3" : 
            setClass = "audio-o";
            cor = "#FF9900";
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
                
                $(".sub-del textarea, .sub-del input").val('');
               kenin[0].selectize.clear();
               $("#kitkat li").eq(3).removeClass('active');$("#kitkat li").eq(2).addClass('active');
               $("#tasks-own").removeClass('active in');$("#require").addClass('active in');
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
              url:"../backend/super-listener-int.php?usr=" + mainuser + "&fac=" + fac,
              success : function (data){
                        console.info(data);
                            var alpha = data.split("|");
                            if(parseInt(alpha[1]) !== 0){
                                    console.info(alpha[7]);
                              upProAdmin(alpha[2], alpha[1], alpha[6], alpha[4], alpha[5], alpha[7]);
                              showAlert("Progreso en Incidencia " + alpha[6] , "" ,  alpha[1]);
                            
                       }
                  }
            });
   }





function upProAdmin(usr_id, usr, tck, rPer, tPer , files ){

  var row = $("#int-table tr td:contains('" + tck + "')")
  .parent()
  .next();

row.find('span.muted').html(tPer + "%");
row.find('.bar').css({ width: tPer + "%"});
row.find("input[value='u" + usr_id +"']").parent().attr("data-per", rPer);

if(parseInt(tPer) > 99.9){
    $("#int-table tr td:contains('" + tck + "')").siblings('.cell-status').children('b').html('Finalizado');
    $("#int-table tr td:contains('" + tck + "')").siblings('.cell-status').children('b').css( "background-color", '#1CC131');
    $("#int-table tr td:contains('" + tck + "')").parent().removeAttr("class").addClass("task Hc-int");
}
//file tratment
var ind = row.find(".int-files-for").index(".int-files-for");

graphAddedFiles($(".int-files-for").eq(ind), tck, usr_id, true);

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


function dropBack(event, object){

   event.preventDefault();
   var data     = event.dataTransfer.getData("text").split("|");
   var exo      = event.dataTransfer.getData("html");

   var frIn     = $(object).index(".drop-zone");
   var iss_ind  = $(".viewToggle").eq(frIn).parent().parent().children('input.iss_id').val();
   var usf      = data[1].substring(data[1].search("_in")-3, data[1].search("_in")); 

   console.info(exo);
   console.info(data[0] + "|" + data[1]);
   backToFront(data[0], usf, iss_ind);
   //$(".file-sent").eq(frIn).append()
   $(".drop-zone").eq(frIn).removeClass("drop-zone").addClass("newtext");
   setTimeout(function(){
      $(".newtext").removeClass("newtext").addClass("drop-zone");
   }, 1200);

   var newf = $(".file-contents").eq(frIn).find("a[href^='" + data[1] +"']").clone();
   newf.removeClass('file-opac').find('i').unwrap().parent().addClass('file-flex').find('span').remove()
   $(".front-sent").eq(frIn).append(newf);

}


function dragExt(event, object){
   console.info(object.attr('title'));
   event.dataTransfer.setData("text", object.attr("title") + "|" + object.attr("data-pseudo") );

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


   var url = '../backend/upload_int.php?fac_id=' + fac + "&stsk=" + stsk_id + "&user=" + usr_id + "&keyfile=" + keyFile;
   var keyGen = true;


var randFiles = "";

uploader =  $(object).pluploadQueue({
        runtimes : 'html5',
        url : '../backend/upload_for_front.php?'  ,
        chunk_size : '2mb',
        unique_names : true,
  filters : {
            max_file_size : '2mb',
            mime_types: [
                {title : "General files", extensions : "jpg,gif,png,pdf,xls,xlsx,docx,doc,txt,pptx,ppt"},
                {title : "Zip files", extensions : "zip" }
            ]
        },
  preinit : {
            Init: function(up, info) {
                console.log('[Init]', 'Info:', info, 'Features:', up.features);
            },
 
            UploadFile: function(up, file) {
                  
                   up.setOption("url", url);
               
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
                $("#D-drop").data("dfil", randFiles);
               
              

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
                
                if(object.hasClass("front-response")){
                   console.info("front");
                  graphAddedFiles(object.next().children(".front-sent"), $("#D-drop").data("dfil"));

                } else if (object.attr("id", "up-own") && kind !== "internal"){

                    var eind = $("#ext-tasks-table input.iss_id[value=" + iss_id +"]").parent().index("tr.task");

                     graphAddedFiles($(".file-sent").eq(eind), $("#D-drop").data("dfil"));

                       
                } 

                randFiles = "";
                keyGen = false;

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

function intDel(user, sub, des, date, ind, mst, tkt){

var pre_fecha  = new Date();
var fecha = pre_fecha.getFullYear() + "-" + ('0' + (pre_fecha.getMonth()+1)).slice(-2) + "-" +
 ('0' + pre_fecha.getDate()).slice(-2) + " " + ('0' + pre_fecha.getHours()).slice(-2) + ":" + ('0' + pre_fecha.getMinutes()).slice(-2)  + ":" + ('0' + pre_fecha.getSeconds()).slice(-2) ;


console.info("llega el ticket = " + tkt);
console.info("../backend/delegate_internal-new.php?muser=" + $("#muser").val() + 
          "&user=" + user + 
          "&fechaF=" + date + 
          "&subject=" + sub + 
          "&descript=" + des + 
          "&startD=" + fecha  + 
          "&fac="+ fac +
          "&main_stsk=" + mst + 
          "&keyfile=" + keyFile +
          "&ticket=" + tkt);
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
          "&keyfile=" + keyFile +
          "&ticket=" + tkt, 
          beforeSend: function(){

                $("#send-int").attr("disabled", true);
          },
          success : function (data){

           console.info(data);

           result = data.split("|");
          var string = "";
           if(result[result.length-1].length < 3 ){
            var titles = user.split(",");
           } else {
            var titles = result[result.length-1].split(",");
           }


           console.info("titles : " + titles);
           console.info("users :" + user );

                     if($("#back-own").data("val") !== 0 || $("#back-own").data("val") !== undefined){

                              $(".ii-forward").eq(ii_ind).parent().children(".person-sw-int").replaceWith('<i class="fa fa-group spac"></i>');
                      }

                   bootbox.alert("Su requerimiento ha sido generado existosamente", function(){
                         $("#send-int").attr("disabled", false);
                         $("#del-int-req").removeClass('active in');$("#int-require").addClass('active in');

                            firstTask(result[0], des, result[1] , date, result[1], 1, "", 1, result[result.length-2]);

                            for(i=1; i < result.length-2; i++){

                              if(result[i] != ""){
                            string +=  '<a class="hovertip" title="' + titles[i-1] + '" onclick="hovertip(this)" data-per="0">' +
                             '<img src="../' + fac + '/img/'  + result[i] + '_opt.jpg" class="group" >' +
                             '<i class="fa fa-check-circle finished" style="opacity: 0"></i>' + 
                             '<input type="hidden" value="u'  + result[i] + '">' +
                             '</a>'; 
                              }   
                            }

                           var parent = document.querySelectorAll('.coll-int')[ind+1];
                           console.info(ind+1);
                           parent.innerHTML = string;  
                console.info($("#up-int").html());
           $("#up-int").empty();
      console.info($("#up-int").html());
                         
                     });
                  newthum(1);

                    $("#del-int-req input, #del-int-req textarea").val('');
                        $("#int-del").val(1);
                        selectInt[0].selectize.clear();


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
      console.log("message :" + message);
      console.info("previan :" + previan);

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
              previan = packets[2];
               //si no está vacio
                 if(parseInt(packets[0]) !== 0 && packets[0] !== "" ){
                  console.info(data);
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
            updateProgress(packets[2], packets[3], packets[6], packets[4], packets[1], packets[0], indice, packets[5], packets[9], nest, packets[10]);
                          //aqui si es de tipo externo \./\./
                         
                        if(parseInt(packets[10]) >= 99.5){
                          if(packets[9] == 0){

                          $("#ext-tasks-table .due").eq(indice).parent().parent().next().children('td').children('div.collaborates').find('input[value=' + packets[1] + ']').prev().css({ opacity : "1"});
                          
                          } else {

                             $("#int-table .coll-int").eq(indice).find('input[value=u' + packets[1] + ']').siblings("i").css({ opacity : "1"});
                             
                          }
                            
                           
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


//clip independ files:

    



     dwco   = document.createElement('div');
     txarea = document.createElement('textarea');
     iwco   = document.createElement('i');
     
     dwco.className = "wcom";
     dwco.setAttribute("align", "center");

     iwco.className = "fa fa-chevron-circle-right fa-3x send-com";
     iwco.style.color = "lightgreen";
     txarea.className = "comentary";
     txarea.setAttribute("placeholder", "Respuesta al ciudadano");

iwco.onclick = function(){
  
    var obj       = $(this);
    var comentary = $(this).prev().val();
    var iss_ind   = $(this).parents("tr").prev().children(".iss_id").val();

  if( comentary.trim() !== ""){
        $.ajax({
                 type: "POST",
                 url: "../backend/coment.php?com=" + comentary + "&iss=" + iss_ind + "&fac=" + fac, 
                 success : function (data){
                  console.info(data);
                        obj.prev().replaceWith("\"" + comentary + "\"");
                        obj.remove();
                        bootbox.alert("Respuesta enviada satisfactoriamente");
                        
                 }
        })

  } else {

       bootbox.alert("ingrese la respuesta al requerimiento del ciudadano");
  }

}

dwco.appendChild(txarea);
dwco.appendChild(iwco);


   // files to back 
    dw_ap = document.createElement('div');
    iw_ap = document.createElement('i');
    ddrop = document.createElement('div');
   
   dw_ap.className = "w-ap";
   iw_ap.className = "fa fa-files-o fa-2x bk-fi";
   iw_ap.style.marginRight = "2em";
   ddrop.className = "drop-zone";

   ddrop.style.width = "80%"; 
   ddrop.style.margin =  "0.7em 5em";
   ddrop.style.border = "5px dashed orange";
   ddrop.style.height = "5em";
   ddrop.style.display = "none";
   

   ddrop.ondrop = function(event){
       dropBack(event, this);
   }  
   ddrop.ondragover = function(event){
     allowDrop(event);
   }


iw_ap.onclick = function(){

var idf = $(this).index(".bk-fi");

  if($(this).data("val") == undefined || $(this).data("val") == 0){
      
     
  $(".drop-zone").eq(idf).fadeToggle("slow");

  $(".file-contents").eq(idf).children('a').find('i').html(function(){
         $(this).attr("title", filename($(this).parent().parent().attr("href")));
         $(this).attr("draggable", true);
         $(this).data("pseudotitle",$(this).parent().parent().attr("href") );
  });
  var newElems = $(".file-contents").eq(idf).find('i').clone();
      newElems.css({ margin : "0 .2em"});
      newElems.on('dragstart', function(){
        dragExt(event , $(this))
      });
      newElems.insertAfter($('.w-ap').eq(idf).children("i"));

     $(this).data("val", 1)

  } else {

     $(this).siblings('i').remove();
       $(".drop-zone").eq(idf).fadeToggle("fast")
      $(this).data("val", 0);
    
  }
               
}

dw_ap.appendChild(iw_ap);
dw_ap.appendChild(ddrop);

     p_pro    = document.createElement('p');
     str_pro  = document.createElement('strong');
     span_pro = document.createElement('span');
     str_p1  = document.createElement('strong');
     span_p1 = document.createElement('span');
     pre_pro  = document.createElement('pre');
     bar_pro = document.createElement('div');
     pre_pro.style.width = "100%";
     pre_pro.style.display ="inline-flex";

    //==== ***** classes ****

    div0.className        = "info-content";
    div0.style.display    = "none";
    div_ic.className      = "docs-example";
    div_ic_back.id        = "back";
    div_ic_pro.className  = "progress tight";
    div_ic_file.className = "files";
    i_ic.className        = "fa fa-chevron-circle-right fa-2x";
    i_ic.style.color      = "rgba(38, 134, 244, 0.9)";
    i_ic.style.cursor     = "pointer";
    bar_pro.className     = "bar forward";


    dl.className       = "dl-horizontal";
    p_pro.className    = "ajuste"; 
    span_pro.className = "pull-right small muted";
    pre_pro.className  = "pre";
    div_ic_pro.appendChild(bar_pro);

    
    str_pro.innerHTML = "Grado de progreso";
    span_pro.innerHTML = "0%";

    str_p1.innerHTML = "Grado de progreso";
    span_p1.innerHTML = "0%";
   
    span_p1.className = "pull-right small muted";
    p_pro.appendChild(str_pro);
    p_pro.appendChild(span_pro);

    p1.appendChild(str_p1);
    p1.appendChild(span_p1);

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

// files to pre-pro 

ipre = document.createElement('i');
dpre1 = document.createElement('div');
dpre2 = document.createElement('div');

ipre.className= "fa fa-paperclip fa-2x fr";
ipre.style.display = "block";
ipre.setAttribute("title","Documentos de Respuesta" );
dpre1.style.width = "100%";

ipre.onclick = function(){
  if($(this).data("val") == undefined || $(this).data("val") == 0 ){

     uploaderInt($(this).parent().prev(), $(this).parent().parent().parent().parent().parent().prev().find(".iss_id").val());
      $(this).data("val", 1);
  } else {
    $(this).parent().prev().fadeToggle("slow");
  }
    
};


dpre1.className ="front-received";
dpre2.className = "front-sent";


pre_pro.appendChild(ipre);
pre_pro.appendChild(dpre1);
pre_pro.appendChild(dpre2);
//=======
div_ic_back.appendChild(i_ic);  
div_ic.appendChild(div_ic_back);
div_ic.appendChild(dl);
div_ic.appendChild(dwco);
div_ic.appendChild(p_pro);
div_ic.appendChild(div_ic_pro);
div_ic.appendChild(dw_ap);
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
               case 'ppt' :  
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'ptx':
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'mp3':
             setClass = "audio-o";
             cor = "#FF9900";
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
         dpre1.innerHTML = filestring;
         fileParent.appendChild(elem[n]);
      }
}
});

dpre1.innerHTML = filestring;

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


function updateProgress(subject, descript, percent, date, userId, usr_name, ind, stsk, kind, aux_stsk, customPro){

if(parseInt(kind) == 0){

document.querySelectorAll("#ext-tasks-table td .bar")[ind*2+1].style.width = percent + "%";
document.querySelectorAll("#ext-tasks-table td p > span.muted")[ind*2+1].innerHTML = percent + "%";
console.info("porsica el ind es : " + ind);
$(".file-contents").eq(ind).parent().prev().find("a input[value= "+ userId +"]").parent().attr("data-val", customPro) ;

 var tknum = $("#ext-tasks-table .task").eq(ind).children(".cell-icon").html();


} else {

document.querySelectorAll("#int-table .bar")[ind].style.width = percent + "%";
document.querySelectorAll("#int-table p > span.muted")[ind].innerHTML = percent + "%";
$(".int-files-for").eq(ind).parent().prev().find("a input[value=u"+ userId +"]").parent().attr("data-per", customPro);
insertScheduleTraffic(subject, descript ,date, userId, ind)
var tknum = $("#int-table .task").eq(ind).children(".cell-icon").html();

}

var parent = document.querySelectorAll(".ex-del-par tbody")[ind];

var tr_av  = document.createElement('tr');
var td1_av = document.createElement('td');
var td2_av = document.createElement('td');
var td3_av = document.createElement('td');

if(parseInt(kind) == 1 ){
  tr_av.style.display =  "none !important";
  tr_av.className = "trf-int-usr ust" + userId;

} else {
  
    tr_av.className = "eu" + userId;
    pseudoparent =  document.querySelectorAll(".ex-del-par tbody")[ind];
    pseudoparent.appendChild(tr_av);

td1_av.innerHTML = subject;
td2_av.innerHTML = descript;
td3_av.innerHTML = date;

tr_av.appendChild(td1_av);
tr_av.appendChild(td2_av);
tr_av.appendChild(td3_av);

}

console.info('ticket enviado: ' + tknum);
graphAddedFiles($(".int-files-for").eq(ind), tknum, userId );
/*
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
*/

    
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


function firstTask(stsk_ident, descript, user_name, date, user_id, kind, issId, Ft, ticket){

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
    var i2   = document.createElement('i');
    var b1   = document.createElement('b');
    var inp1 = document.createElement('input');
    
    td1.className = "cell-icon int-lock";
    if(kind == 0){
         tr1.className = "task Ec-int-ii";
    } else {
          tr1.className = "task Ec-int";
    }
    td1.innerHTML = ticket;
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


    i2.className   = "fa fa-chevron-circle-right ii-forward";
    
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
 var ids    = $("#int-table > tbody .task").length -1;
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

if(kind == 0){

td4.className = "cell-title";
// aqui yace
i2.onclick = function (){
dateTime = AmericanDate($(this).next().html());

 remoteUser = user_id;
 st_ii      = stsk_ident;
 ii_iss     = issId;
 ii_ind     = $(this).index(".ii-forward");
$("#up-int").empty();
// new wave
if($(this).next().hasClass('person-sw-int')){

if($(this).next().find('.swt-boo-int').bootstrapSwitch('state') == true){
$("#stsk-code-ii").val(st_ii);
$("#stsk-user-ii").val(remoteUser);
$("#stsk-user-ii").attr("name", "muser");

percent = parseInt($(this).parent().parent().next().children('td').children('p').children('span').html());
console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);
$("#back-own").data("val", 1); // esto es para que el boton back se devuelva a la vista interna
$("#int-require").removeClass('active in');
$("#set-pro-own").addClass('active in');

} else {

mode = "first";
$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');
$("#back-own").data("val", 1);

}

} else if($(this).next().hasClass('fa-user')){

$("#stsk-code-ii").val(st_ii);
$("#stsk-user-ii").val(remoteUser);
$("#stsk-user-ii").attr("name", "muser");

percent = parseInt($(this).parent().parent().next().children('td').children('p').children('span').html());
console.info("remoteUser:" + remoteUser + " st_ii :" + st_ii + " ii_iss : " + ii_iss + " ii_ind :" + ii_ind);

   $(".span2").data("val", percent);
   $(".span2").slider('setValue', percent);
$("#back-own").data("val", 1);
$("#int-require").removeClass('active in');
$("#set-pro-own").addClass('active in')

}  else {

mode = "first";
$("#del-int-req").data("val", $(this).index());
$("#int-require").removeClass('active in');$("#del-int-req").addClass('active in');
$("#back-own").data("val", 0);


}}}



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
  var div_special = document.createElement('div');
  var div_special2 = document.createElement('div');
      div_special2.className = "int-chart";
      div_special2.style.display = "inline-block";
      div_special2.style.verticalAlign = "top";
      div_special.className = "wrap-int-files";

      div_special.style.width = "100%"
  var div5 = document.createElement('div');
      div4.className ="int-files-for";
      div5.className = "int-files-to";

      div5.style.width = "80%";
      div4.style.width = "80%";

  var nname_pre = $("#D-drop").data("dfil");

  console.info("-")
  console.info( $("#D-drop").data("dfil"));
  console.log(nname_pre);
  console.info("-");

  if($("#D-drop").data("dfil") !== undefined){

  var nname = nname_pre.split("|");
  var setClass = "";
  var cor = "";
  var filstr= "";
  console.info('por aqui llega con kind ' + kind);

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
                case 'ppt' :  
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'ptx':
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'mp3':
             setClass = "audio-o";
             cor = "#FF9900";
        break; 

    }
    
    filstr += '<a href="../' + fac + '/' + nname[i] + '" title="' + nname[i] +  '" download><i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor + '; margin: 0 0.4em"></i></a>';
   console.info(filstr);
  }

      div5.innerHTML = filstr;
  }
      div_special.appendChild(div5);
      div_special.appendChild(div4);
      div_special.appendChild(div_special2);


} else {

      div4.className ="ii-files";
  var div5 = document.createElement('div');
      div5.className = "ii-files-sent";

    input_b = document.createElement('input');
    input_b.type = "checkbox";
    input_b.className = "swt-boo-int";

   div_ii = document.createElement('div');
   i_ii   = document.createElement('i');
   i_ii2  = document.createElement('i');

    div_ii.className  = "person-sw-int";
    i_ii.className    = "fa fa-user spac";
    i_ii2.className   = "fa fa-group spac";
    i_ii2.style.color = "gray";

             input_b.checked = "true";
             input_b.setAttribute( "data-label-width", 3);
             input_b.setAttribute( "data-size", "mini");
             input_b.setAttribute( "data-on-color", "info");
             input_b.setAttribute( "data-off-color", "default");
             input_b.setAttribute( "data-on-text", "&ensp;");
             input_b.setAttribute( "data-off-text", "&ensp;");

   div_ii.appendChild(i_ii);
   div_ii.appendChild(input_b);
   div_ii.appendChild(i_ii2);

}


var amdString = '<a class="hovertip" title="' + user_name + '" onclick="hovertip(this)">' +
                   '<img src="../' + fac + '/img/' + user_id + '_opt.jpg" class="group">' +
                   '<i class="fa fa-check-circle finished" style="opacity: 0"></i>' +
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
               case 'ppt' :  
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'ptx':
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'mp3':
             setClass = "audio-o";
             cor = "#FF9900";
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

if(div5.innerHTML == ""){
   div5.innerHTML = str_file;
}
 

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


if(kind == 1){
    td_i1.appendChild(div_special);
} else {
  td_i1.appendChild(div5);
  td_i1.appendChild(div4);
  td4.appendChild(div_ii);
}

td_i1.insertAdjacentHTML("beforeend",table_string);

tr2.appendChild(td_i1);
parent_int.appendChild(tr1);
parent_int.appendChild(tr2);

insertAfter(tr2, tr1);

if(kind == 0){
  var ix =$(".swt-boo-int").length;
  $(".swt-boo-int").eq(ix-1).bootstrapSwitch();
}

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

  $(".trf-int-usr").css({display :"none"});

   var val = parseInt($(this).children("input").val().replace("u" ,""));
  
   $(this).parent().next().next().children("tbody").children("tr.ust" + val).css({ display : "table-row"});

     $(this).data("val", 1);

var percent = $(this).attr("data-per");
var pseudoIndex = $(this).parent().next().find(".int-chart").index(".int-chart"); 
        var selter = d3.select(document.querySelectorAll('.int-chart')[pseudoIndex]).transition().each('start',function (d){ $("#pro-audio")[0].play() }).each('end', function (d){ setTimeout(function(){$("#pro-audio")[0].pause() ; $("#pro-audio")[0].currentTime = 0 }, 800)})
        var rp1 = radialProgress(document.querySelectorAll('.int-chart')[pseudoIndex])
                .label('')
                .diameter(125)
                .value(percent)
                .render();
                    $("svg").attr("width", 100);
    $("svg").attr("height", 100);
   $(this).parent().next().children(".int-files-for").children("a").css({ opacity: ".3" });
   $(this).parent().next().children(".int-files-for").find("p[title*='_" + val + ".']").parent().css({ opacity : "1"});
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

    $(".trf-int-usr").css({display :"none"});

   var val = parseInt($(object).children("input").val().replace("u" ,""));

   $(object).parent().next().next().children("tbody").children("tr.ust" + val).css({ display : "table-row"});

     $(object).data("val", 1);

var percent = $(object).attr("data-per");
var pseudoIndex = $(object).parent().next().find(".int-chart").index(".int-chart"); 
        var selter = d3.select(document.querySelectorAll('.int-chart')[pseudoIndex]).transition().each('start',function (d){ $("#pro-audio")[0].play() }).each('end', function (d){ setTimeout(function(){$("#pro-audio")[0].pause() ; $("#pro-audio")[0].currentTime = 0 }, 800)})
        var rp1 = radialProgress(document.querySelectorAll('.int-chart')[pseudoIndex])
                .label('')
                .diameter(125)
                .value(percent)
                .render();
    $("svg").attr("width", 100);
    $("svg").attr("height", 100);
   $(object).parent().next().children(".int-files-for").children("a").css({ opacity: ".3" });
   $(object).parent().next().children(".int-files-for").find("p[title*='_" + val + ".']").parent().css({ opacity : "1"});
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
            $(".datetimepicker").val('');
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
     if(checkOwn() === true){
        upgradeOwn($("#set-pro-own").attr("data-stsk"), $("#set-pro-own").attr("data-iss"), $('.span2').eq(1).val() , $("#own-descript").val(), $("#own-subtasks").val());
     } else {
       bootbox.alert(checkOwn());
     }
});

function upgradeOwn(stskId, issId, percent, descript, subject, ticket){

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
      "&ticket=" + ticket +
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
           
          if(percent > 99){
            $(".task").eq(ind).removeAttr("class").addClass("task Hc-int");
            $(".task").eq(ind).find('b').html("FINALIZADO");
            $(".task").eq(ind).find('b').css("background-color", "#1CC131");
            $(".task").eq(ind).children("button.forward").attr("disabled", "true");
          }

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

     uploaderInt($(this).parent().prev(), $(this).parent().parent().parent().parent().parent().prev().find(".iss_id").val());
      $(this).data("val", 1);
  } else {
    $(this).parent().prev().fadeToggle("slow");
  }
    
});

function graphAddedFiles(object, tt, bs, targus){

var rex = new RegExp(/\](.*?)\./g);

$.ajax({
     type: "POST",
     url: "../backend/filepack.php?ticket=" + tt + "&fac=" + fac, 
     success: function(data){

  var nname_pri = data.split("|"); 
  var nname = nname_pri[0].split(",");

  var filstr = "";
  var setClass = "";
  var cor = "";

  console.info(data);

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
               case 'ppt' :  
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'ptx':
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'mp3':
             setClass = "audio-o";
             cor = "#FF9900";
        break; 

    }

        filstr += '<a href="../' + fac +'/' + mainuser + '_alt/' + nname[i].trim() + '"  download>' +
                 '<p style="display: inline-block" title="' + nname[i].replace(rex, "]_" +  bs + ".") +  '"></p>' +
                  '<i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor + '; margin: 0 0.4em"></i>' +
                  '</a>';

}

var nname_sec = nname_pri[1].split(",");

     for (i=0; i < nname_sec.length-1 ; i++){
     var extension = nname_sec[i].substring(nname_sec[i].length -3 , nname_sec[i].length);
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
               case 'ppt' :  
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'ptx':
             setClass = "powerpoint-o";
             cor = "#B8005C";
        break;
              case 'mp3':
             setClass = "audio-o";
             cor = "#FF9900";
        break; 

    }
    //trated link 
     
          filstr += '<a href="../' + fac + '/' + mainuser + '_alt/' + nname_sec[i].trim() + '"  download>' +
                 '<p style="display: inline-block" title="' + nname_sec[i] +  '"></p>' +
                  '<i class="fa fa-file-' + setClass + ' fa-2x" style="color:' + cor + '; margin: 0 0.4em"></i>' +
                  '</a>';

}
  
object.html(filstr);

filstr = "";



     } 
})



}


$(".bk-fi").on('click', function(){

var idf = $(this).index(".bk-fi");

  if($(this).data("val") == undefined || $(this).data("val") == 0){
      
     
  $(".drop-zone").eq(idf).fadeToggle("slow");

  $(".file-contents").eq(idf).children('a').find('i').html(function(){
         $(this).attr("title", filename($(this).parent().parent().attr("href")));
         $(this).attr("draggable", true);
         $(this).attr("data-pseudo", $(this).parent().parent().attr("href"))
         
  });

  var newElems = $(".file-contents").eq(idf).find('i').clone();
      newElems.css({ margin : "0 .2em"});
      newElems.insertAfter($('.w-ap').eq(idf).children("i"));
      newElems.on('dragstart', function(){
          dragExt(event , $(this));
        });

     $(this).data("val", 1)


  } else {

     $(this).siblings('i').remove();
       $(".drop-zone").eq(idf).fadeToggle("fast")
      $(this).data("val", 0);
    
  }
               
});
// desde este punto se decide que se hace ocn task-own y su control de flujo 

// $("back-own").data("val") te da la distincion entre 0 or undefined para externos y 1 para internos 
// se tine que elaborar un req interno delegado... y elimnar el recibido 
// $("#send-int"). -> crea un first task and collar

$(".send-com").on('click', function(){
  
    var obj       = $(this);
    var comentary = $(this).prev().val();
    var iss_ind   = $(this).parents("tr").prev().children(".iss_id").val();

  if( comentary.trim() !== ""){
        $.ajax({
                 type: "POST",
                 url: "../backend/coment.php?com=" + comentary + "&iss=" + iss_ind + "&fac=" + fac, 
                 success : function (data){
                  console.info(data);
                        obj.prev().replaceWith("\"" + comentary + "\"");
                        obj.remove();
                        bootbox.alert("Respuesta enviada satisfactoriamente");
                        
                 }
        })

  } else {

       bootbox.alert("ingrese la respuesta al requerimiento del ciudadano");
  }

});

function backToFront(name, usrId, iss){
 console.info(usrId);
  $.ajax({ type: "POST",
   url: "../backend/backtofront.php?usr="+ usrId + "&fac=" + fac + "&file=" + name + "&iss=" + iss,
   success: function (data){
      
   }

})
}

function checkOwn(){
  if($("#set-pro-own").find("input[type=text]").val().trim() == ""){
    return "Falta el asunto del progreso";
  }
   if($("#set-pro-own").find("textarea").val().trim() == ""){
    return "Falta la descripción del progreso";
  }
return true;
}

</script>

<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}
?>
