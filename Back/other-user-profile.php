<?php session_start(); header('Content-Type: text/html; charset=utf-8');

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'back-user'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

$Query_task = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_SUBJECT, A.STSK_DESCRIP, SUBSTRING(A.STSK_FINISH_DATE, 1, 10), B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.STSK_START_DATE, 1, 10) , A.STSK_PROGRESS FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE ( STSK_TYPE = 0 AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1)");
$Query_alerts = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " GROUP BY STSK_STATE");

$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1 ) ORDER BY STSK_ID DESC LIMIT 1";
$notify = mysqli_fetch_assoc(mysqli_query($datos, $str_query));

$query_internal= "SELECT A.STSK_ID,  A.STSK_ISS_ID , A.STSK_SUBJECT, A.STSK_DESCRIP, C.EST_DESCRIPT, A.STSK_PROGRESS, C.EST_COLOR, A.STSK_LOCK, A.STSK_FINISH_DATE FROM SUBTASKS A INNER JOIN USERS B ON(A.STSK_CHARGE_USR = B.USR_ID) INNER JOIN EST C ON(C.EST_CODE = A.STSK_STATE) WHERE (STSK_LOCK = 1 AND STSK_TYPE = 1 AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . ")";
$internal =  mysqli_query($datos, $query_internal);

if(!$notify){
     $manu = "";
} else {
    
    $manu = $notify['STSK_DESCRIP'];
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
.done{background:#daedb1;background:-moz-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#daedb1),color-stop(28%,#abd78d),color-stop(100%,#54ca50))!important;background:-webkit-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-o-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:-ms-linear-gradient(top,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;background:linear-gradient(to bottom,#daedb1 0%,#abd78d 28%,#54ca50 100%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#daedb1',endColorstr='#54ca50',GradientType=0)!important}.warning{background:#fefcea;background:-moz-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#fefcea),color-stop(0%,#fefcea),color-stop(26%,#f1da36))!important;background:-webkit-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-o-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:-ms-linear-gradient(top,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;background:linear-gradient(to bottom,#fefcea 0%,#fefcea 0%,#f1da36 26%)!important;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#fefcea',endColorstr='#f1da36',GradientType=0)!important}.delay{background:#ff5335;background:-moz-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-webkit-gradient(linear,left top,left bottom,color-stop(1%,#ff5335),color-stop(100%,#d00e04));background:-webkit-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-o-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:-ms-linear-gradient(top,#ff5335 1%,#d00e04 100%);background:linear-gradient(to bottom,#ff5335 1%,#d00e04 100%);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5335',endColorstr='#d00e04',GradientType=0)}.OwnComp{width:100%}.OwnComp-bars{background-color:#FFF;width:100%;margin:.5em;border:4px solid transparent;padding:1em 1.5em;width:80%}#Urgent-Display,#Audi-Display,#Com-Display{height:0;visibility:hidden;-webkit-transition:all 600ms ease-in-out;-moz-transition:all 600ms ease-in-out;transition:all 600ms ease-in-out}.sub-del{width:55%;display:inline-block;vertical-align:top}#delegates{width:50%;position:relative;float:left}.require-subtasks{padding:0 1em;margin:.5em}#st-description{width:80%}.attach{display:inline-block;vertical-align:top}.display-progress{display:none;-webkit-transition:all 800ms ease-in-out;-moz-transition:all 800ms ease-in-out;transition:all 800ms ease-in-out}.wrap-progress{width:100%;background-color:#FFF}.progress-go{width:85%;text-align:left}.slider-horizontal{width:100%!important}.At{display:table-row}.Ec,.Hc,.Pe,.Pv{display:none}#back{width:auto;cursor:pointer}#audititle{font-style:italic;color:gray;width:100%}#wrapaudi{display:block;width:100%}.down{display:inline-block;vertical-align:top;margin:0 .8em}.info-content{width:100%}.iss-descript{font-style:italic;font-size:.7em;display:inline-block;vertical-align:top}@media screen and (max-width: 1024px){.sub-del{width:100%}#upgrade{position:relative;margin:3em 0;left:35%}#upload{width:100%}#drop a{display:block}}
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
                            <img src="../img/<? printf($_SESSION['TxtCode']) ?>.jpg" class="nav-avatar" />
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
                                        <img src="../img/<? printf($_SESSION['TxtCode']) ?>.jpg">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION['TxtUser'])?> <? printf($_SESSION['TxtPass'])?><small>Offline</small>
                                        </h4>
                                        <p class="profile-brief">
                                         <? printf($_SESSION['TxtPosition']) ?> en SERVIU.
                                        </p>
                                        <div class="profile-details muted" id="kitkat">

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
                                            $Tuba = "Atrasadas";
                                          break;
                                          case 5:
                                             $type = "fa-check-circle";
                                             $taint = "#1CC131";
                                             $tuba = "Finalizadas";
                                          break;
                                          case 1:
                                             $type = "fa-flag";
                                             $taint = "#DED901";
                                             $tuba = "Pendientes";
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
                                            <td class="cell-time align-right">Fecha máxima de entrega</td>
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
                                            <td class="cell-status hidden-phone hidden-tablet"><b class="due" style="background-color: <? printf($stsk[6]) ?> !important;"><? printf($stsk[5]) ?></b></td>
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
                                            <tbody>
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
                                            <div class="pull-right"></div>
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
                                                                        <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila5[7]) ?>%</span>
                                                                    </p>
                                                                    <div class="progress tight">
                                                                        <div class="bar bar-warning" style="width: <? printf($fila5[7]) ?>%;"></div>
                                                                    </div>
                                                                    <div class="file-contents">
<?
                              if(!is_dir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/")) {
                                  
                                    mkdir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/", 0775, true); 
   
                              } 

                                        if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "_alt/" )){
                                        
                                          $file_extension = "";

                                           while (false !== ($archivos = readdir($handler))){
                                    

                                         if(preg_match_all("/_\[" . $fila_int[0] . "\]_/", $archivos) == 1){
                                             
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
                                                               <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($_SESSION['TxtCode'])  ?>_alt/<? printf($archivos)?>" class="down" download> 
                                                                <p class="ifile" title="<? printf($archivos) ?>">
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
                                                                </td>
                                                            </tr>
                                                    <? } ?>
                                                           </tbody>
                                                    </table> 
                                            </div>
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
</body>

<script type="text/javascript">
    

var fac = $("#facility").val();
var current_iss;
var inner = 0;
var progressbar;
var previuosData =  <?  printf("\"" . $manu . "\"")  ?>  ;
var mainuser = <? printf( $_SESSION['TxtCode'] )  ?>;


    $(document).on('ready', function(){


    
       progressbar =  $('.span2').slider({ step: 10 , max: 100, min: 0});


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
    });


 

$(".forward").on('click', function(){

   var subtask_id =  $(this).parent().parent().children('input').eq(0).val();
   current_iss =  $(this).parent().parent().children('input').eq(1).val();
   inner = $(this).parent().parent().index();
   subject = $(this).parent().parent().children('td').eq(1).text();
   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();

//obten el porcentaje


var percent = $(this).parent().parent().next().children('td').children('div').children('p').children('span').html();

$(".span2").slider('setValue', parseInt(percent));

$("#stsk-code").val(subtask_id);
$("#stsk-user").val(user);

$("#kitkat li").eq(0).removeClass('active');$("#kitkat li").eq(1).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');


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


//aqui va la funcion 


$("#upgrade").on('click', function(){

    upprogress($('.span2').val(), $("#muser").val(), $("#stsk-code").val(), current_iss, $("#st-description").val() , $("#subject").val(), inner);
    current_iss = 0;

    $("#subject").val('');
    $("#st-description").val('');

    $(".span2").slider('setValue', 0);

$("#kitkat li").eq(1).removeClass('active');$("#kitkat li").eq(0).addClass('active');
$("#tasks-own").removeClass('active in');$("#require").addClass('active in');



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


function upprogress(val, user, stsk_id, iss_id, des, subject, index){

var _fS = new Date();
date = _fS.getFullYear() + "-" + ('0' + (_fS.getMonth()+1)).slice(-2) + "-" + ('0' + _fS.getDate()).slice(-2) + " " + ('0' + _fS.getHours()).slice(-2) + ":" + ('0' + _fS.getMinutes()).slice(-2) + ":" + ('0' + _fS.getSeconds()).slice(-2);

    $.ajax({
           type: "POST", 
           url: "../backend/upgrade.php?val=" + val +
            "&stsk_id=" +  stsk_id + 
            "&iss_id=" + iss_id + 
            "&mmx=" + user + 
            "&subject=" + subject + 
            "&des=" + des + 
            "&date=" + date +
            "&fac=" + fac 
            , 
            success : function (data){
          
         if( parseInt(data) == 1){

             bootbox.alert("Progreso grabado existosamente", function(){
             console.info(index);

            $("#ext-tasks-table > tbody > tr").eq(index+1).children("td").children().eq(1).children().eq(0).children('span').html(val + "%");
            $("#ext-tasks-table > tbody > tr").eq(index+1).children("td").children().eq(1).children().eq(1).children().css({ width : val + "%"});
            
    progressTableUpdate(subject, des, date, document.querySelectorAll("#ext-tasks-table > tbody > tr")[index+1].childNodes[1].childNodes[5].childNodes[1]);
            
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
                    }

                 $("#ext-tasks-table > tbody > tr").eq(index).addClass("Hc");    
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
   $("#kitkat li").eq(1).removeClass('active');$("#kitkat li").eq(0).addClass('active');
$("#tasks-own").removeClass('active in');$("#require").addClass('active in'); 
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

if(typeof(EventSource) !== "undefined") {

    var source = new EventSource("../backend/sse-event-back.php?usr=" + mainuser);

    source.onmessage = function(event) {
   
       var eventMessage = event.data.split('\n');

    if(eventMessage[2] == "" ){
        previuosData = "";
    }
   
        if (eventMessage[2] !== previuosData || eventMessage[2] !== ""){

            previuosData = eventMessage[2];

                showAlert(eventMessage[2]);
     
                    inputTask(eventMessage[2], eventMessage[0], eventMessage[1], eventMessage[4], eventMessage[3], eventMessage[6], eventMessage[5] , eventMessage[7] );
        }
    }

} else {



}


function inputTask(stsk_descript, stsk, iss, ctz, desc, dateIn, dateOut, kind){


if(parseInt(kind) == 0){
   var parent =  document.querySelector("#ext-tasks-table tbody");
} else {
   var parent =  document.querySelector("#int-table tbody");
}

    var tr1 = document.createElement('tr');
    tr1.className = "task";

    var td1 = document.createElement('td');
    var td2 = document.createElement('td');
    var td3 = document.createElement('td');
    var td4 = document.createElement('td');
    var td5 = document.createElement('td');
    var td7 = document.createElement('td');


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
    td4.className = "cell-title";
    td5.className = "cell-time align-right";
    td7.className = "cell-time align-right";

    td5.innerHTML = dateIn;
    td7.innerHTML = dateOut;

     i0.className   = "fa fa-chevron-circle-right";
     btn.appendChild(i0);
     btn.className   = "btn btn-small forward";
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


    btn.onclick = function(){

   var subtask_id =  $(this).parent().parent().children('input').eq(0).val();
   current_iss    =  $(this).parent().parent().children('input').eq(1).val();
   inner          =  $(this).parent().parent().index();
   subject        =  $(this).parent().parent().children('td').eq(1).text();

   $("#audititle").html("\"" + subject + "\"");

   var user = $("#muser").val();

//obten el porcentaje

   var percent = $(this).parent().parent().next().children('td').children('div').children('p').children('span').html();

        $(".span2").slider('setValue', parseInt(percent));

        $("#stsk-code").val(subtask_id);
        $("#stsk-user").val(user);

        $("#kitkat li").eq(0).removeClass('active');$("#kitkat li").eq(1).addClass('active');
        $("#require").removeClass('active in');$("#tasks-own").addClass('active in');

};

//callback function

    var  divFile = document.createElement('div');
    var  a       = document.createElement('a');


    divFile.className = "file-contents";
    
    
    tr1.appendChild(td1);
    tr1.appendChild(td2);
    tr1.appendChild(td3);
    tr1.appendChild(td4);
    tr1.appendChild(td5);
    tr1.appendChild(td7);
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


   // div 4

   div4.className = "wrap-progress";
   td6.appendChild(div4);

    tr2.className = "display-progress";
    td6.colSpan = "6";
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

    

$.ajax({     

          type: "POST",
          url: "../backend/dynamics_JSON_files.php?usr_id=" + $("#muser").val() + "&iss_id=" + iss + "&fac=" + fac,
          success: function(data){

         
   arrayFiles = data.split("|");
 
 for (var i = 0; i < arrayFiles.length; i++){
    
    a           = document.createElement('a');
    a.href      = "../" + fac + "/" + $("#muser").val() + "/" + arrayFiles[i];
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
    div4.appendChild(divFile);

    insertAfter(div2, div4);

 }

     }
 });

// ==== llamada Asincronica fin ====

    p1.appendChild(str1);
    p2.appendChild(str2);
    p3.appendChild(str3);
    p3.appendChild(span1);

    div1.appendChild(p1);
    div1.appendChild(p2);
    div2.appendChild(div3);

    td6.appendChild(div1);
    tr2.appendChild(td6);
    td6.appendChild(p3);
    td6.appendChild(div2);



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

</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>