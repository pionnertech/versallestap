<?php session_start(); header('Content-Type: text/html; charset=utf-8');

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'back-user'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

$Query_task = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_SUBJECT, A.STSK_DESCRIP, SUBSTRING(A.STSK_FINISH_DATE, 1, 10), B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.STSK_START_DATE, 1, 10) , A.STSK_PROGRESS FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE ( STSK_TYPE = 0 AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1)");
$Query_alerts_ext = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_LOCK = 1 AND STSK_TYPE = 0 ) GROUP BY STSK_STATE");
$Query_alerts_int = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_MAIN_USR <> STSK_CHARGE_USR AND STSK_LOCK = 1 AND STSK_TYPE = 1 ) GROUP BY STSK_STATE");
$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1 AND STSK_TYPE= 0) ORDER BY STSK_ID DESC LIMIT 1";
$notify = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

$str_query_int = "SELECT STSK_ID, " .
"STSK_ISS_ID, " .
"STSK_SUBJECT, " .
"STSK_DESCRIP, " .
"STSK_FINISH_DATE AS FECHA_FINAL, " . 
"STSK_START_DATE AS FECHA_INICIAL, " . 
"STSK_TYPE " .
" FROM SUBTASKS " .  
"WHERE ( STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1 AND STSK_TYPE = 1) ORDER BY STSK_ID DESC LIMIT 1";

$notify_int = mysqli_fetch_assoc(mysqli_query($datos, $str_query_int));

$query_internal= "SELECT A.STSK_ID,  A.STSK_ISS_ID , A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . ")";
$internal =  mysqli_query($datos, $query_internal);

if(!$notify){
     $manu = "";
} else {
    
    $manu = $notify['STSK_DESCRIP'];
}

if(!$notify_int){
     $manu_int = "";
} else {
    
    $manu_int = $notify_int['STSK_DESCRIP'];
}

$quntum = mysqli_query($datos, "SELECT COUNT(STSK_ID) AS CONTADOR FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode']);

if(mysqli_num_rows($quntum) == 0){

    $contador = 0;
    
} else {

    $cont = mysqli_fetch_assoc($quntum);
    $contador = $cont['CONTADOR'];
}


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
    <link type="text/css" href="../css/slider.css" rel="stylesheet" />
    <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
    
    <style type="text/css">
.done{background:#daedb1;background:-moz-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#daedb1),color-stop(28%,#abd78d),color-stop(100%,#54ca50))!important;background:-webkit-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-o-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-ms-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:linear-gradient(to bottom,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#daedb1',endColorstr='#54ca50',GradientType=0)!important}.warning{background:#fefcea;background:-moz-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fefcea),color-stop(0%,#fefcea),color-stop(26%,#f1da36))!important;background:-webkit-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-o-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-ms-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:linear-gradient(to bottom,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fefcea',endColorstr='#f1da36',GradientType=0)!important}.delay{background:#ff5335;background:-moz-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ff5335),color-stop(100%,#d00e04));background:-webkit-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-o-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-ms-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:linear-gradient(to bottom,#ff5335 1%,#d00e04 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5335',endColorstr='#d00e04',GradientType=0)}.OwnComp{width:100%}.OwnComp-bars{background-color:#FFF;width:100%;margin:.5em;border:4px solid transparent;padding:1em 1.5em;width:80%}#Urgent-Display,#Audi-Display,#Com-Display{height:0;visibility:hidden;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.sub-del{width:55%;display:inline-block;vertical-align:top}#delegates{width:50%;position:relative;float:left}.require-subtasks{padding:0 1em;margin:.5em}#st-description{width:80%}.attach{display:inline-block;vertical-align:top}.display-progress{display:none;-webkit-transition:all 800ms ease-in-out;-moz-transition:all 800ms ease-in-out;transition:all 800ms ease-in-out}.wrap-progress{width:100%;background-color:#FFF}.progress-go{width:85%;text-align:left}.slider-horizontal{width:100%!important}.At-int{display:table-row}.Ec-int,.Hc-int,.Pe-int,.Pv-int{display:none}.At{display:table-row}.Ec,.Hc,.Pe,.Pv{display:none}#back{width:auto;cursor:pointer}#audititle{font-style:italic;color:gray;width:100%}#wrapaudi{display:block;width:100%}.down{display:inline-block;vertical-align:top;margin:0 .8em}.info-content{width:100%}.iss-descript{font-style:italic;font-size:.7em;display:inline-block;vertical-align:top}@media screen and (max-width: 1024px){.sub-del{width:100%}#upgrade{position:relative;margin:3em 0;left:35%}#upload{width:100%}#drop a{display:block}}
</style>    
</head>
<body>
<input id="muser" type="hidden" value="<? printf($_SESSION["TxtCode"]) ?>">
<input type="hidden" id="facility" value="<? printf($_SESSION['TxtFacility']) ?>">
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                    <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html">Eque-E</a>
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
                     
                        <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? printf($_SESSION['TxtCode']) ?>.jpg" class="nav-avatar" />
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
                    </div>
                    <!--/.sidebar-->
                </div>
                <!--/.span3-->
                <div class="span9">
                    <div class="content">
                        <div class="module">
                            <div class="module-body">
                                <div class="profile-head media">
                                    <a href="#" class="media-avatar pull-left">
                                        <img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? printf($_SESSION['TxtCode']) ?>.jpg">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION['TxtUser'])?> <? printf($_SESSION['TxtPass'])?><small>Offline</small>
                                        </h4>
                                        <p class="profile-brief">
                                         <? printf($_SESSION['TxtPosition']) ?> en SERVIU.
                                        </p>
                                        <div class="profile-details muted" id="kitkat">
                                        </div>
                                    </div>
                                </div>
                                <ul class="profile-tab nav nav-tabs">
                                    <li class="active"><a href="#require" data-toggle="tab">Compromisos Externos</a></li>
                                    <li><a href="#int-require" data-toggle="tab">Compromisos Internos</a></li>
                                </ul>
                                <div class="profile-tab-content tab-content">
                   <div class="tab-pane fade active in" id="require">
                    <div class="module message">
                            <div class="module-head">
                                <h3>Control de cumplimientos</h3>
                            </div>
                            <div class="module-option clearfix">
                                <div class="pull-left">
                                    Filtro : &nbsp;
                                    <div class="btn-group">
                                        <button class="btn" id="titlen">Pendientes</button>
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
                                             $taint = "#DED901";
                                             $tuba = "Pendiente";
                                          break;

                                       }

                                    ?>
<a class="btn Qext" title="<? printf($tuba) ?>"><p style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>; font-size: 1.5em; font-weight: 800;" ><? printf($fi[0]) ?></p>
<i class="fa <? printf($type) ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>"></i>
</a>

    <? } ?>




                                    <a href="#" class="btn btn-primary">Crear Requerimiento</a>
                                </div>
                            </div>
                        <div>
                            <div class="module-body table">                             
                                <table class="table table-message" id="ext-tasks-table">
                                    <tbody>
                                        <tr class="heading">
                                            <td class="cell-icon"></td>
                                            <td class="cell-title">Requerimiento</td>
                                            <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                            <td class="cell-title">Marcar Avance</td>
                                            <td class="cell-title">Inicio</td>
                                            <td class="align-right">Fecha máxima de entrega</td>
                                        </tr>
                                    <? 
                                     $class = "";

                                    while( $stsk = mysqli_fetch_row($Query_task) ){ 

                                    switch ($stsk[5] ){
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
                                            <td class="cell-icon"><i class="icon-checker high"></i></td>
                                            <td class="cell-title"><div><? printf($stsk[3]) ?></div></td>
                                            <td class="cell-status"><b class="due" style="background-color: <? printf($stsk[6]) ?> !important;"><? printf($stsk[5]) ?></b></td>
                                             <? if($class == "Hc"){
                                                  $enable = "disabled";
                                              } else {
                                                  $enable = "";
                                              }
                                              ?>
                                            <td class="cell-title"><button class="btn btn-default forward" <? printf($enable) ?> ><i class="fa fa-chevron-circle-right"></i></button></td>
                                            <td class="cell-time"><div><? printf(date("d/m/Y", strtotime(substr($stsk[7], 0, 10)))) ?></div></td>
                                            <td class="cell-time align-right"><div><? printf(date("d/m/Y", strtotime(substr($stsk[4], 0, 10)))) ?></div></td>
                                            <input type="hidden" value="<? printf($stsk[0]) ?>" >
                                            <input type="hidden" value="<? printf($stsk[1]) ?>" >
                                        </tr>
                                        <tr class="display-progress">
                                        <td colspan="6">
                                        <div class="info-content">
                                            <? 
$shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP ,  CONCAT(B.CTZ_NAMES , ' ', B.CTZ_SURNAME1, ' ',  B.CTZ_SURNAME2) AS NAME, B.CTZ_ADDRESS, B.CTZ_TEL   FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) WHERE ISS_ID = " . $stsk[1] ));
                                            ?>
                              <p class="iss-descript"><strong>Ciudadano</strong> : <? echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($shine['NAME'])))); ?></p> 
                              <p class="iss-descript"><strong>Teléfono</strong> : <? printf($shine['CTZ_TEL']) ?></p> 
                              <p class="iss-descript"><strong>Dirección</strong> : <? printf($shine['CTZ_ADDRESS']) ?></p> 
                              <p class="iss-descript"><strong>Descripcion compromiso</strong> : <? printf($shine['ISS_DESCRIP']) ?></p>         
                                        </div>
                                           <div class="wrap-progress">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($stsk[8]) ?>%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: <? printf($stsk[8]) ?>%;"></div>
                                            </div>
                                            <div class="file-contents">       
                              <?   
                                          

                              if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/", 0775, true); 
   
                              } 

                                        if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/" )){
                                        
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

                                             <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($_SESSION['TxtCode'])  ?>/<? printf($archivos)?>" class="down" download> 
                                              <p class="ifile" title="<? printf($archivos) ?>">
                                                 <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                 <span class="iname" ></span>
                                                </p>
                                             </a>
                                                  <? 
                                                  } 
                                        
                                    }
                                } //aqui cierra el opendir
                                closedir($handler);
                                                  ?>


                                            </div>
                                           </div>
<?    

$str_query_trf = "SELECT TRF_SUBJECT, TRF_DESCRIPT, TRF_ING_DATE FROM TRAFFIC WHERE (TRF_STSK_ID = " . $stsk[0] . "  AND TRF_USER = " . $_SESSION['TxtCode'] . ")";
$trf_hand = mysqli_query($datos, $str_query_trf);
      
?>
                                        <table class="table">
                                            <tbody class="body-int-tra">
                                                <tr>
                                                    <td>Asunto</td>
                                                    <td>Descripcion</td>
                                                    <td>Fecha</td>
                                                </tr>
                                             <?   while ($fetch_trf = mysqli_fetch_row($trf_hand)) { ?>
                                                <tr>
                                                    <td><? echo $fetch_trf[0]; ?></td>
                                                    <td><? echo $fetch_trf[1]; ?></td>
                                                    <td><? echo date('d/m/Y', strtotime($fetch_trf[2])); ?></td>
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
                            <div class="module-foot">
                            </div>
                        </div>
                       </div> 
                  </div>
                        <div class="tab-pane fade" id="tasks-own">
                           <div class="media-stream">
                                <div class="sub-del">
                                <div id="back"><i class="fa fa-chevron-circle-left fa-3x"></i></div>
                                    <h3>Subir Cumplimientos</h3>
                                    <strong id="wrapaudi"><small id="audititle"></small></strong>
                                    <input type="text" id="subject" class="require-subtasks" value="" placeholder="asunto">
                                    <textarea id="st-description" placeholder="Descripcion cumplimmiento" style="margin: 1.5em .5em"></textarea>
                                    <div class="progress-go">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                            </p>
                                             <input type="text" class="span2" />
                                    </div>
                                    <button class="btn btn-info" id="upgrade">Subir Progreso</button>
                                </div>
                                <div class="attach">
                                    <form id="upload" method="post" action="../backend/upload_back_to_admin.php" enctype="multipart/form-data">
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
                                    <div class="tab-pane fade" id="int-require">
                                            <div class="module message">
                                                   <div class="module-head">
                                                       <h3>Compromisos Internos</h3>
                                                   </div>
                                            <div class="module-option clearfix">
                                                    <div class="pull-left">
                                                        Filtro : &nbsp;
                                                        <div class="btn-group">
                                                            <button class="btn title-int">Atrasados</button>
                                                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li class="swt-int" id="Pe-int"><a href="#">Pendientes</a></li>
                                                                <li class="swt-int" id="Ec-int"><a href="#">En Curso</a></li>
                                                                <li class="swt-int" id="Pv-int"><a href="#">Por Vencer</a></li>
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
                                            $Tuba = "Atrasado";
                                          break;
                                          case 5:
                                             $type = "fa-check-circle";
                                             $taint = "#1CC131";
                                             $tuba = "Finalizado";
                                          break;
                                          case 1:
                                             $type = "fa-flag";
                                             $taint = "#DED901";
                                             $tuba = "Pendientes";
                                          break;

                                       }

                                    ?>
<a class="btn Qint" title="<? printf($tuba) ?>"><p style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>; font-size: 1.5em; font-weight: 800;" ><? printf($fi[0]) ?></p>
<i class="fa <? printf($type) ?> fa-2x" style="display: inline-block; vertical-align: top;color: <? printf($taint) ?>"></i>
</a>

    <? } ?>
                                            </div>
                                            </div>
                                            <div class="module-body table">
                                                   <table class="table table-message" id="int-table">
                                                      <tbody>
                                                          <tr class="heading">
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Requerimiento</td>
                                                              <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                                              <td class="cell-title">Responsable</td>
                                                              <td class="cell-time align-right">Fecha</td>
                                                            </tr>
                               <? while($fila_int = mysqli_fetch_row($internal)) { 

                                        switch ($fila_int[4] ){
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
                                                            <tr class="task <? echo $class ?>">
                                                            <input type="hidden" value="<? echo $fila_int[0] ?>" class="int-st">
                                                            <input type="hidden" value="<? echo $fila_int[1] ?>" class="int-st-src">
                                                                <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                                <td class="cell-title"><? echo $fila_int[3] ?></td>
                                                                <td class="cell-status"><b class="due int-desglo" style="background-color:<? echo $fila_int[6] ?> ; "><? echo $fila_int[4] ?></b></td>
                                                                <td class="cell-title int-forward" style="cursor:pointer;"><i class="fa fa-chevron-circle-right"></i></td>
                                                                <td class="cell-time align-right"><? echo date("d/m/Y", strtotime(substr($fila_int[8], 0, 10))) ?></td>
                                                            </tr>
                                                        <tr class="display-progress" style="display: none;">
                                                                <td colspan="5">
                                                                   <p>
                                                                        <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila_int[5]) ?>%</span>
                                                                    </p>
                                                                    <div class="progress tight">
                                                                        <div class="bar bar-warning" style="width: <? printf($fila_int[5]) ?>%;"></div>
                                                                    </div>
                                                                    <div class="file-contents">
<?
                              if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/", 0775, true); 
   
                              } 

                                        if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/" )){
                                        
                                          $file_extension = "";

                                           while (false !== ($archivos2 = readdir($handler))){

 echo "<script>console.info('"  . $archivos2 . "' + '/' + '" . $fila_int[0]  . "/' + '" . preg_match_all("/_\[" . $fila_int[0] . "\]_/", $archivos2) . "')</script>";

                                         if(preg_match_all("/_\[" . $fila_int[0] . "\]_/", $archivos2) == 1){
                                             
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
                                                               <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($_SESSION['TxtCode'])  ?>_alt/<? printf($archivos2)?>" class="down" download> 
                                                                <p class="ifile" title="<? printf($archivos2) ?>">
                                                                   <i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                                   <span class="iname" ></span>
                                                                  </p>
                                                               </a>
                                                    <? }
                                                            }
                                                         
                                                    } 
                                                     closedir($handler);  
                                                    ?>
                                                                    </div>
                                                         <table style="width:100%">
                                                                <tbody class="ii-events">
                                                                    <tr >
                                                                        <td><span style="font-weight: bolder">Asunto</span></td>
                                                                        <td><span style="font-weight: bolder">Descripcion</span></td>
                                                                        <td><span style="font-weight: bolder" class="align-right">Fecha progreso</span></td>
                                                                    </tr>
                                                    <?   
              $TII = mysqli_query($datos, "SELECT TII_SUBJECT, TII_DESCRIPT, TII_ING_DATE FROM TRAFFIC_II WHERE TII_STSK_ID =" . $fila_int[0]);  
                                    while ($ii_trf = mysqli_fetch_row($TII)) {
                                                    ?>
                                                        <tr>
                                                            <td class="cell-title"><? echo $ii_trf[0]?></td>
                                                            <td class="cell-title"><? echo $ii_trf[1]?></td>
                                                            <td class="align-right"><? echo date("d/m/Y", strtotime(substr($ii_trf[2], 0, 10))) ?></td>
                                                        </tr>
                                                    <? } ?>
                                                                </tbody>
                                                            </table>
                                                            </tr>
                                                    <? } ?>
                                                           </tbody>
                                                    </table> 
                                            </div>
                                                                </td>

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
        <audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="../backend/notify.mp3" type="audio/mpeg"><source src="notify.wav" type="audio/wav"></audio>
    </div>
    <!--/.wrapper-->
    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2015 Eque-e </b>todos los derechos reservados.
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
    <script type="text/javascript" src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script src="http://modernizr.com/downloads/modernizr-latest.js"></script>
<script src="../scripts/bootstrap-slider.js"></script>
    <!--<script src="../scripts/circular-slider.js"></script>-->
</body>

<script type="text/javascript">
    

var fac = $("#facility").val();
var current_iss;
var inner = 0;
var progressbar;
var previuosData =  <?  printf("\"" . $manu . "\"")  ?>  ;
var previuosDataInt = <?  printf("\"" . $manu_int . "\"")  ?>  ;
var mainuser = <? printf( $_SESSION['TxtCode'] )  ?>;
var argument = 0;

    $(document).on('ready', function(){

       progressbar =  $('.span2').slider({ step: 10 , max: 100, min: 0});

    });


 

$(".forward").on('click', function(){
   argument = 0;

   var subtask_id =  $(this).parent().parent().children('input').eq(0).val();
   current_iss =  $(this).parent().parent().children('input').eq(1).val();
   inner = $(this).parent().parent().index();
   subject = $(this).parent().parent().children('td').eq(1).text();
   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();

//obten el porcentaje


var percent = $(this).parent().parent().next().children('td').children('div').children('p').children('span').html();

$(".span2").slider('setValue', parseInt(percent));
$(".span2").data("val", parseInt(percent));


$("#stsk-code").val(subtask_id);
$("#stsk-user").val(user);

$("#kitkat li").eq(0).removeClass('active');$("#kitkat li").eq(1).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');


});


$(".int-forward").on('click', function(){

   argument = 1;

//change form action to the back to admin internal 
console.info($("#upload").attr("action"));
$("#upload").attr("action", "../backend/int_files_back_to_admin.php");
console.info($("#upload").attr("action"));

   var subtask_id =  $(this).parent().children('input').eq(0).val();
   current_iss    =  $(this).parent().children('input').eq(1).val();
   inner          =  $(this).parent().index();
   subject        =  $(this).parent().children('td').eq(1).text();

   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();

   var percent = $(this).parent().next().children('td').children('p').children('span').html();
  
   $(".span2").data("val", parseInt(percent));
   $(".span2").slider('setValue', parseInt(percent));
   $("#stsk-code").val(subtask_id);
   $("#stsk-user").val(user);

$("#int-require").removeClass('active');$("#tasks-own").addClass('active in');

})

$(".due").on('click', function(){

if(!$(this).data("val") || !$(this).data("val") === 0 ){

   $(this).parent().parent().next().css({ display: "table-row"});
     $(this).data("val", 1);
} else  {

  $(this).parent().parent().next().css({ display: "none"});
   $(this).data("val", 0);
}

});


//aqui va la funcion 


$("#upgrade").on('click', function(){

    upprogress($('.span2').val(), $("#muser").val(), $("#stsk-code").val(), current_iss, $("#st-description").val() , $("#subject").val(), inner, argument);
    current_iss = 0;

    $("#subject").val('');
    $("#st-description").val('');
    $(".span2").slider('setValue', 0);

if(argument === 1){

$("#tasks-own").removeClass('active in');$("#int-require").addClass('active in');
} else {

$("#tasks-own").removeClass('active in');$("#require").addClass('active in');
}


});



$(".switcher").on('click', function(){

    var all_on = document.querySelectorAll('.switcher');
    var ex = $(this).attr("id");
    var cur_name = $(this).html();
    var titlen = $("#titlen").html(cur_name );


   $(".display-progress").css({ display: "none"});

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


function upprogress(val, user, stsk_id, iss_id, des, subject, index, ar){

var _fS = new Date();
date = _fS.getFullYear() + "-" + ('0' + (_fS.getMonth()+1)).slice(-2) + "-" + ('0' + _fS.getDate()).slice(-2) + " " + ('0' + _fS.getHours()).slice(-2) + ":" + ('0' + _fS.getMinutes()).slice(-2) + ":" + ('0' + _fS.getSeconds()).slice(-2);

console.info("../backend/upgrade.php?val=" + val +
            "&stsk_id=" +  stsk_id + 
            "&iss_id=" + iss_id + 
            "&user=" + user + 
            "&subject=" + subject + 
            "&des=" + des + 
            "&date=" + date +
            "&fac=" + fac + 
            "&argument=" + ar);

    $.ajax({
           type: "POST", 
           url: "../backend/upgrade.php?val=" + val +
            "&stsk_id=" +  stsk_id + 
            "&iss_id=" + iss_id + 
            "&user=" + user + 
            "&subject=" + subject + 
            "&des=" + des + 
            "&date=" + date +
            "&fac=" + fac + 
            "&argument=" + ar, 
            success : function (data){

                console.info(data);
         if( parseInt(data) == 1){
             bootbox.alert("Progreso grabado existosamente", function(){
             console.info(index);
//para comopromisos externos
     if(argument == 0) {  

            $("#ext-tasks-table > tbody > tr").eq(index+1).children("td").children('div').eq(1).children('p').children('span').html(val + "%");
            $("#ext-tasks-table > tbody > tr").eq(index+1).children("td").children('div').eq(1).children('div').children('div').css({ width: val +"%"});


    var indexVal = (index-1)/2;
    progressTableUpdate(subject, des, date, document.querySelectorAll("#ext-tasks-table .body-int-tra")[indexVal]);

    thum("ext", "En Curso");

            if(val == 100){

               $("#ext-tasks-table > tbody > tr").eq(index).children().eq(2).children().html("FINALIZADA");
               $("#ext-tasks-table > tbody > tr").eq(index).children().eq(2).children().removeAttr("style");
               $("#ext-tasks-table > tbody > tr").eq(index).children().eq(2).children().css({backgroundColor : "#00BF00 !important"});

                    switch(true){

                        case $("#ext-tasks-table > tbody > tr").eq(index).hasClass("Pv"): 
                                 $("#ext-tasks-table > tbody > tr").eq(index).removeClass("Pv");
                        break;
                        case $("#ext-tasks-table > tbody > tr").eq(index).hasClass("At"):
                                 $("#ext-tasks-table > tbody > tr").eq(index).removeClass("At");
                        break;
                        case $("#ext-tasks-table > tbody > tr").eq(index).hasClass("Pe"):
                                 $("#ext-tasks-table > tbody > tr").eq(index).removeClass("Pe");
                        break;
                        case $("#ext-tasks-table > tbody > tr").eq(index).hasClass("Ec"):
                                 $("#ext-tasks-table > tbody > tr").eq(index).removeClass("Ec");
                        break;
                    }

                 $("#ext-tasks-table > tbody > tr").eq(index).addClass("Hc");   
                 thum("ext", "Finalizado");
                }

             } else {
              var weirdIndex =  (index-1)/2;

            $("#int-table > tbody > tr").eq(index + 1).children("td").children('p').children('span').html(val + "%");
            $("#int-table .bar").eq(weirdIndex).css({ width: val + "%"});

            holindex = index;
            index = (index-1)/2;
           
            if(val == 100){

               $("#int-table > tbody > tr").eq(holindex).children("td").eq(2).children().html("FINALIZADA");
               $("#int-table > tbody > tr").eq(holindex).children("td").eq(2).children().removeAttr("style");
               $("#int-table > tbody > tr").eq(holindex).children("td").eq(2).children().css({backgroundColor : "#00BF00 !important"});

                    switch(true){
                        case $("#int-table > tbody > tr").eq(index).hasClass("Pv-int"): 
                                 $("#int-table > tbody > tr").eq(index).removeClass("Pv-int");
                        break;
                        case $("#int-table > tbody > tr").eq(index).hasClass("At-int"):
                                 $("#int-table > tbody > tr").eq(index).removeClass("At-int");
                        break;
                        case $("#int-table > tbody > tr").eq(index).hasClass("Pe-int"):
                                 $("#int-table > tbody > tr").eq(index).removeClass("Pe-int");
                        break;
                       case $("#int-table > tbody > tr").eq(index).hasClass("Ec-int"):
                                 $("#int-table > tbody > tr").eq(index).removeClass("Ec-int");
                        break;
                    }
                     thum("int", "Finalizado");
                 $("#int-table > tbody > tr").eq(holindex).addClass("Hc-int");    
                }

            progressTableUpdate(subject, des, date, document.querySelectorAll(".ii-events")[index]);
            thum("int", "En Curso");
             }
             });
            } else {
            bootbox.alert("Falla en la conexion al servidor");
               };
                 $("#upload ul").empty();
                }
            });
}

$("#back").on('click', function(){

if (argument == 0){

$("#kitkat li").eq(1).removeClass('active');$("#kitkat li").eq(0).addClass('active');
$("#tasks-own").removeClass('active in');$("#require").addClass('active in'); 
} else {

$("#tasks-own").removeClass('active in');$("#int-require").addClass('active in'); 
}

});

    var Notification = window.Notification || window.mozNotification || window.webkitNotification;

    Notification.requestPermission(function (permission) {
        // console.log(permission);
    });

    function showAlert(message) {
        var instance = new Notification(
            "Te ha llegado un nuevo requerimiento:", {
                body: message,
                icon: "https://cdn4.iconfinder.com/data/icons/meBaze-Freebies/512/alert.png"
            }
        );
        instance.onclick = function () {
            // Something to do
        };
        instance.onerror = function () {
            // Something to do
        };
        instance.onshow = function () {
          $('#chatAudio')[0].play();
        };
        instance.onclose = function () {
            // Something to do
        };
        return false;
    }


// externos
setInterval(function(){
    $.ajax({ type: "POST", 
            url: "../backend/sse-event-back.php?usr=" + mainuser,
            success : function(data){
                     var msgExt = data.split('|');
                         if(msgExt[2] == "" ){
                                previuosData = "";
                            } 
                         if (msgExt[2] !== previuosData && msgExt[2] !== ""){
                                previuosData = msgExt[2];
                                     showAlert(msgExt[2]);
                                        inputTask(msgExt[2], msgExt[0], msgExt[1], msgExt[4], msgExt[3], msgExt[6], msgExt[5] , msgExt[7] , msgExt[8], msgExt[9]);
                            }

                    }
        })
}, 3000);


//interno

if(typeof(EventSource) !== "undefined") {

    var sourceInt = new EventSource("../backend/sse-int-back.php?usr=" + mainuser);

    sourceInt.onmessage = function(event) {

       var eventMessage = event.data.split('\n');
 
       console.info("tipo de requerimiento : " + eventMessage[5]);
    if(eventMessage[2] == "" ){

        previuosDataInt = "";
    }
        if (eventMessage[2] != previuosDataInt && eventMessage[2] !== ""){

            previuosDataInt = eventMessage[2];

                showAlert(eventMessage[2]);
             
inputTask(eventMessage[2], eventMessage[0], eventMessage[1], "", "", eventMessage[4], eventMessage[3] , eventMessage[5] , "", "");
        }
    }


} else {

}


function inputTask(stsk_descript, stsk, iss, ctz, desc, dateIn, dateOut, kind, ctz_tel, ctz_address){


if(parseInt(kind) == 0){
   var parent =  document.querySelector("#ext-tasks-table tbody");
   var specialUrl = "../backend/dynamics_JSON_files.php?usr_id=" + $("#muser").val() + "&iss_id=" + iss + "&fac=" + fac;
} else {
   var parent =  document.querySelector("#int-table tbody");
   var specialUrl = "../backend/files_int.php?usr_id=" + $("#muser").val() + "&stsk_id=" + stsk + "&fac=" + fac;
}

    var tr1 = document.createElement('tr');

    if(kind == 0){
       tr1.className = "task Ec";
    } else {
        tr1.className = "task Ec-int";
    }
    
    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td7 = document.createElement('td');
    var td8 = document.createElement('td');

    var inp1 = document.createElement('input');
    var inp2 = document.createElement('input');

    var b    = document.createElement('b');
    var btn  = document.createElement('button');
    var i0   = document.createElement('i');
    var icon = document.createElement('i');

    icon.className = "icon-checker high";
    td1.appendChild(icon);
    
    td1.className = "cell-icon";
    td2.className = "cell-title";
    td3.className = "cell-status";
    btn.className = "btn btn-default";
    td7.className = "cell-time align-right";

   // create date
   var todayDate = new Date();
   var today = ('0' + todayDate.getDate()).slice(-2) + "/" + ('0' + (todayDate.getMonth() + 1)).slice(-2) + "/" +  ('0' + todayDate.getFullYear()).slice(-2);

    td7.innerHTML = today;
    td8.innerHTML = dateOut;

     i0.className   = "fa fa-chevron-circle-right";
     btn.appendChild(i0);

     if(kind == 0){

         td4.className = "cell-title forward";

     } else {
       
         td4.className = "cell-title int-forward";
     }
     td4.appendChild(btn);  
   
    td2.innerHTML = stsk_descript;

    inp1.type = "hidden";
    inp2.type = "hidden";

    inp1.value = stsk;
    inp2.value = iss;

    inp1.id = "st";
    inp2.id = "iss_id";
    
    b.className = "due";
    b.style.backgroundColor = "#178FD0";
    b.innerHTML = "EN CURSO";
    td3.appendChild(b);

    b.onclick = function(){
        if(!$(this).data("val") || !$(this).data("val") === 0 ){
             $(this).parent().parent().next().css({ display: "table-row"});
             $(this).data("val", 1);
       } else  {
         $(this).parent().parent().next().css({ display: "none"});
        $(this).data("val", 0);
        }
    }

if(kind == 0){

    btn.onclick = function(){

   argument = 0;

   var subtask_id =  $(this).parent().parent().children('input').eq(0).val();
   current_iss =  $(this).parent().parent().children('input').eq(1).val();
   inner = $(this).parent().parent().index("");
   subject = $(this).parent().parent().children('td').eq(1).text();
   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();

//obten el porcentaje
var percent = $(this).parent().parent().next().children('td').children('div').children('p').children('span').html();

$(".span2").slider('setValue', parseInt(percent));
$(".span2").data("val", parseInt(percent));


$("#stsk-code").val(subtask_id);
$("#stsk-user").val(user);

$("#kitkat li").eq(0).removeClass('active');$("#kitkat li").eq(1).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

}

} else {

    btn.onclick = function(){

   argument = 1;

//change form action to the back to admin internal 

$("#upload").attr("action", "../backend/int_files_back_to_admin.php");


   var subtask_id =  $(this).parent().parent().children('input').eq(0).val();
   current_iss    =  $(this).parent().parent().children('input').eq(1).val();
   inner          =  $(this).parent().parent().index();
   subject        =  $(this).parent().parent().children('td').eq(1).text();

   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();
   var percent = $(this).parent().parent().next().children('td').children('p').children('span').html();
   $(".span2").data("val", parseInt(percent));
   $(".span2").slider('setValue', parseInt(percent));
   $("#stsk-code").val(subtask_id);
   $("#stsk-user").val(user);

$("#int-require").removeClass('active');$("#tasks-own").addClass('active in');

}

}


//callback function

    var  divFile = document.createElement('div');
    var  a       = document.createElement('a');


    divFile.className = "file-contents";
    
    
    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td7);
    tr1.appendChild(td8);
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

    var i3   = document.createElement('i');
    var i4   = document.createElement('i');
   
    var p1   = document.createElement('p');
    var p2   = document.createElement('p');
    var p3   = document.createElement('p');

   
    var str1 = document.createElement('strong');
    var str2 = document.createElement('strong');
    var str10 = document.createElement('strong');
    var str11 = document.createElement('strong');

    str1.style.margin = "0 .5em"; 
    str2.style.margin = "0 .5em"; 
   str10.style.margin = "0 .5em"; 
   str11.style.margin = "0 .5em"; 

    str1.style.fontWeight = "bolder";
    str2.style.fontWeight = "bolder";
   str10.style.fontWeight = "bolder";
   str11.style.fontWeight = "bolder";

   // div 4

   div4.className = "wrap-progress";
   

    tr2.className = "display-progress";
    td6.colSpan = "6";
    div1.className = "info-content";

  
    p1.className = "iss-descript";
    p2.className = "iss-descript";

    str1.innerHTML = "Ciudadano : " + ctz;
    str2.innerHTML = "Telefono Ciudadano: " + ctz_tel;
    str10.innerHTML = "Direcion: " + ctz_address;
    str11.innerHTML = "Descripción: " + desc;

    var str3  = document.createElement('strong');
    var span1 = document.createElement('span');

    str3.innerHTML  = "Grado de progreso";
    span1.innerHTML = "0%";
    span1.className = "pull-right small muted";
    div2.className  = "progress tight";
    div3.className  = "bar bar-warning";

$.ajax({     
          type: "POST",
          url: specialUrl,
          success: function(data){
            
    console.info(data);
    console.info(specialUrl);

   arrayFiles = data.split("|");
 
 for (var i = 0; i < arrayFiles.length; i++){
    
    a           = document.createElement('a');
    a.href      = "../" + fac + "/" + $("#muser").val() + "_alt/" + arrayFiles[i];
    a.className = "down";
    a.setAttribute('download', true);
   
    pS = document.createElement('p');
    pS.className = "ifile";
    pS.title = arrayFiles[i];

    iN = document.createElement('i');

     var setClass ="";
     var cor ="";
     var extension = arrayFiles[i].substring(arrayFiles[i].length -3 , arrayFiles[i].length);
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

    iN.className = "fa fa-file-" + setClass + " fa-2x";
    iN.style.color = cor;

    spanS = document.createElement('span');
    spanS.className = "iname";
   
    pS.appendChild(iN);
    pS.appendChild(spanS);

    a.appendChild(pS);
    divFile.appendChild(a);
    td6.appendChild(divFile);

 }

     }
 });

// ==== llamada Asincronica fin ====
  var tbl  = document.createElement('table');
  var tbo  = document.createElement('tbody');
  var trt  = document.createElement('tr');
  var tdt1 = document.createElement('td');
  var tdt2 = document.createElement('td');
  var tdt3 = document.createElement('td');
  var spnt1 = document.createElement('span');
  var spnt2 = document.createElement('span');
  var spnt3 = document.createElement('span');

  tbl.style.width = "100%";

  if(kind == 1){
   tbl.className = "table";

  } else {
        if(kind == 0){
        tbo.className = "body-int-tra";
     }  else {
        tbo.className   = "ii-events";
     }
 
  }
  

  spnt1.innerHTML = "Asunto";
  spnt2.innerHTML = "Descripcion";
  spnt3.innerHTML = "Fecha Progreso";
   
   tdt1.appendChild(spnt1);
   tdt2.appendChild(spnt2);
   tdt3.appendChild(spnt3);

  tbo.appendChild(tdt1);
  tbo.appendChild(tdt2);
  tbo.appendChild(tdt3);
   
   tbl.appendChild(tbo);
   
   
//table 
    p1.appendChild(str1);
    p2.appendChild(str2);
    p3.appendChild(str3);
    p2.appendChild(str10);
    p2.appendChild(str11);
    p3.appendChild(span1);

    div1.appendChild(p1);
    div1.appendChild(p2);
    div2.appendChild(div3);

    td6.appendChild(div1);
    td6.appendChild(div4);
    td6.appendChild(tbl);

    tr2.appendChild(td6);
    div4.appendChild(p3);
    div4.appendChild(div2);
  
   // td6.insertBefore(div4, div1);
   // insertAfter(tbl, div4);

    parent.appendChild(tr2);

}

function progressTableUpdate(subject, description, date, object){

var tr  = document.createElement('tr');
var td1 = document.createElement('td');
var td2 = document.createElement('td');
var td3 = document.createElement('td');

td1.innerHTML = subject;
td2.innerHTML = description;
td3.innerHTML = date;

tr.appendChild(td1);
tr.appendChild(td2);
tr.appendChild(td3);

object.appendChild(tr);

}

function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

$(".span2").on("slide", function (slideEvt) {
   if (slideEvt.value < $(this).data("val")){
        alert("fuera de rango");
        $(".span2").slider('setValue', $(this).data("val"));
   }
});

function thum(kind, type, ancient ){

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

  $("a." + change + "[title='" + ancient + "']").children('p').html(parseInt(   $("a." + change + "[title='" + ancient + "']").children('p').html()) - 1   );

}

var current = parseInt(thum.children('p').html()) + 1;

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

    case "Atrasado":
      var design = "fa-exclamation-triangle";
      var taint = "#E70101";
    break;
    case "Finalizado":
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
}


}

</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>