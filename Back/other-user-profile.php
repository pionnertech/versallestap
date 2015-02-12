<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'back-user'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS

$Query_task = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_SUBJECT, A.STSK_DESCRIP, SUBSTRING(A.STSK_FINISH_DATE, 1, 10), B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.STSK_START_DATE, 1, 10) , A.STSK_PROGRESS FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE ( STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1)");

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
    .done{
background: #daedb1; /* Old browsers */
background: -moz-linear-gradient(top,  #daedb1 0%, #abd78d 28%, #54ca50 100%) !important; /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#daedb1), color-stop(28%,#abd78d), color-stop(100%,#54ca50)) !important; /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #daedb1 0%,#abd78d 28%,#54ca50 100%) !important; /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #daedb1 0%,#abd78d 28%,#54ca50 100%) !important; /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #daedb1 0%,#abd78d 28%,#54ca50 100%) !important; /* IE10+ */
background: linear-gradient(to bottom,  #daedb1 0%,#abd78d 28%,#54ca50 100%) !important; /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#daedb1', endColorstr='#54ca50',GradientType=0 ) !important; /* IE6-9 */

    }

    .warning{
background: #fefcea; /* Old browsers */
background: -moz-linear-gradient(top,  #fefcea 0%, #fefcea 0%, #f1da36 26%) !important; /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fefcea), color-stop(0%,#fefcea), color-stop(26%,#f1da36)) !important; /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #fefcea 0%,#fefcea 0%,#f1da36 26%) !important; /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #fefcea 0%,#fefcea 0%,#f1da36 26%) !important; /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #fefcea 0%,#fefcea 0%,#f1da36 26%) !important; /* IE10+ */
background: linear-gradient(to bottom,  #fefcea 0%,#fefcea 0%,#f1da36 26%) !important; /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fefcea', endColorstr='#f1da36',GradientType=0 ) !important; /* IE6-9 */

    }
   
    .delay{
    background: #ff5335; /* Old browsers */
background: -moz-linear-gradient(top,  #ff5335 1%, #d00e04 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#ff5335), color-stop(100%,#d00e04)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #ff5335 1%,#d00e04 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #ff5335 1%,#d00e04 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #ff5335 1%,#d00e04 100%); /* IE10+ */
background: linear-gradient(to bottom,  #ff5335 1%,#d00e04 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff5335', endColorstr='#d00e04',GradientType=0 ); /* IE6-9 */

    }

.OwnComp{
    width:100%;
}    
.OwnComp-bars{
    background-color: #FFF;
    width:100%;
    margin: .5em;
    border: 4px solid transparent;
    padding:1em 1.5em;
    width:80%;

}


#Urgent-Display, #Audi-Display, #Com-Display{
    height: 0px;
    visibility: hidden;
    -webkit-transition: all 600ms ease-in-out;
    -moz-transition: all 600ms ease-in-out;
    transition: all 600ms ease-in-out;
}


.sub-del{
    width: 55%;
    display: inline-block;
    vertical-align: top;
}

#delegates{
    width:50%;
    position: relative;
    float: left;

}
.require-subtasks{
    padding: 0 1em;
    margin:.5em ;
}


#st-description{
    width:80%;
}

.attach{
 display: inline-block;
 vertical-align: top;
}

.display-progress{

    display: none;
        -webkit-transition: all 800ms ease-in-out;
    -moz-transition: all 800ms ease-in-out;
    transition: all 800ms ease-in-out;
}

.wrap-progress{
  width:100%;
  background-color: #FFF;

}

.progress-go{
    width:85%;
    text-align: left;

}

.slider-horizontal{
    width: 100% !important;
}

.Pe{
    display: table-row;
}
.Ec, .Hc, .At, .Pv{
    display: none;
}

#back{
    width: auto;
    cursor:pointer;
}

#audititle{
    font-style: italic;
    color: gray;
    width:100%;
}
#wrapaudi{
    display:block;
    width: 100%;
}
.down{
    display: inline-block;
    vertical-align: top;
    margin:  0 .8em;
}
.info-content{
    width:100%;

}

.iss-descript{
    font-style: italic;
    font-size:.7em;
    display: inline-block;
    vertical-align: top;
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
                            <img src="images/ejecutivo4.jpg" class="nav-avatar" />
                            <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Your Profile</a></li>
                                <li><a href="#">Edit Profile</a></li>
                                <li><a href="#">Account Settings</a></li>
                                <li class="divider"></li>
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
                            <li class="active"><a href="index.html"><i class="menu-icon icon-dashboard"></i>Vista Principal
                            </a></li>
                            <li><a href="activity.php"><i class="menu-icon icon-bullhorn"></i>ingreso de Audiencias</a>
                            </li>
                            <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario<b class="label green pull-right">
                                11</b> </a></li>
                            <li><a href="task.html"><i class="menu-icon icon-tasks"></i>Control de Cumplimientos<b class="label orange pull-right">
                                19</b> </a></li>
                        </ul>
                        <!--/.widget-nav-->
 
                        <!--/.widget-nav-->
                        <ul class="widget widget-menu unstyled">
                            <li><a class="collapsed" data-toggle="collapse" href="#togglePages"><i class="menu-icon icon-cog">
                            </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
                            </i>Vistas </a>
                                <ul id="togglePages" class="collapse unstyled">
                                    <li><a href="other-login.html"><i class="icon-bar-chart"></i> Estadisticas</a></li>
                                    <li><a href="other-user-profile.html"><i class="icon-upload-alt"></i>Progresos</a></li>
                                    <li><a href="other-user-listing.html"><i class="icon-time"></i>Historial Requerimientos</a></li>
                                </ul>
                            </li>
                            <li><a href="../backend/close.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                        </ul>
                        <h3>Mis Compromisos</h3>
                        <div id="Urgencias" class="OwnComp">
                            <div class="OwnComp-bars" style="border-right-color: #EA0000; border-left-color: #EA0000; cursor: pointer;" id="Urgent">Urgencias</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Urgent-Display">
                                        <li>
                                            <p>
                                             <strong>Urgencias Recibidas</strong> <span class="pull-right small muted">17%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 17%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias Activas</strong><span class="pull-right small muted">88%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 88%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias por vencer</strong> <span class="pull-right small muted">12%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 12%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias Atrasadas</strong> <span class="pull-right small muted">2%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 2%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                               <div id="Audiencias" class="OwnComp">
                <div class="OwnComp-bars" style="border-right-color: #009D00; border-left-color: #009D00; cursor: pointer;" id="Audi">Audiencias</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Audi-Display">
                                        <li>
                                            <p>
                                             <strong>Audiencias Recibidas</strong> <span class="pull-right small muted">17%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 17%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias Activas</strong><span class="pull-right small muted">88%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 88%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias por vencer</strong> <span class="pull-right small muted">12%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 12%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias Atrasadas</strong> <span class="pull-right small muted">2%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 2%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <div id="Compromisos" class="OwnComp">
                              <div class="OwnComp-bars" style="border-right-color: #005FAA; border-left-color: #005FAA; cursor: pointer;" id="Com">Compromisos</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Com-Display">
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
                                    <a href="#" class="media-avatar pull-left">
                                        <img src="images/ejecutivo4.jpg">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION['TxtUser'])?> <? printf($_SESSION['TxtPass'])?><small>Offline</small>
                                        </h4>
                                        <p class="profile-brief">
                                         <? printf($_SESSION['TxtPosition']) ?> en SERVIU.
                                        </p>
                                        <div class="profile-details muted" id="kitkat">
                                            <a href="#" class="btn"><i class="icon-plus shaded"></i>Send Friend Request </a>
                                            <a href="#" class="btn"><i class="icon-envelope-alt shaded"></i>Send message </a>
                                        </div>
                                    </div>
                                </div>
                                <ul class="profile-tab nav nav-tabs">
                                    <li class="active"><a href="#require" data-toggle="tab">Control cumplimientos</a></li>
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
                                        <button class="btn">Pendientes</button>
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
                                <table class="table table-message">
                                    <tbody>
                                        <tr class="heading">
                                            <td class="cell-icon"></td>
                                            <td class="cell-title">Requerimiento</td>
                                            <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                            <td class="cell-title">Marcar Avance</td>
                                            <td class="cell-title">Inicio</td>
                                            <td class="cell-time align-right">Fecha final</td>
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
                                              case 'Hecha':
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
                                            <td class="cell-title"><button class="btn btn-default forward"><i class="fa fa-chevron-circle-right"></i></button></td>
                                            <td class="cell-time"><div><? printf(date("d/m/Y", strtotime(substr($stsk[7], 0, 10)))) ?></div></td>
                                            <td class="cell-time align-right"><div><? printf(date("d/m/Y", strtotime(substr($stsk[4], 0, 10)))) ?></div></td>
                                            <input type="hidden" value="<? printf($stsk[0]) ?>" >
                                            <input type="hidden" value="<? printf($stsk[1]) ?>" >
                                        </tr>
                                        <tr class="display-progress">
                                        <td colspan="6">
                                        <div class="info-content">
                                            <? 
$shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP ,  B.CTZ_NAMES FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) WHERE ISS_ID = " . $stsk[1] ));
                       
                                            ?>
                              <p class="iss-descript"><strong>Ciudadano</strong> : <? printf($shine['CTZ_NAMES']) ?></p> 
                              <p class="iss-descript"><strong>Descripcion audiencia</strong> : <? printf($shine['ISS_DESCRIP']) ?></p>            
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
                                           
                                        if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/" )){
                                        
                                          $file_extension = "";

                                           while (false !== ($archivos = readdir($handler))){
                                            echo "<script>console.info('" . $archivos . "' + '" . $stsk[1] . "');</script>";
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

                                             <a href="../<? printf($_SESSION['TxtFacility']) ?>/<? printf($_SESSION['TxtCode'])  ?>/<? printf($archivos)?>" class="down" download>  <p class="ifile" title="<? printf($archivos) ?>"><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                 <span class="iname" ></span>
                                                </p>
                                             </a>
                                                  <? 
                                                  } 
                                        
                                    }
                                }
                                closedir($handler);
                                 
                                
                                                  ?>


                                            </div>
                                           </div>
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
     <script src="../scripts/bootstrap-slider.js"></script>
</body>

<script type="text/javascript">
    

var fac = $("#facility").val();
var current_iss;
var inner = 0;
var progressbar;

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

   $(".display-progress").css({ display: "none"});

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
            $("tr").eq(index+1).children("td").children().eq(1).children().eq(0).children('span').html(val + "%");
            $("tr").eq(index+1).children("td").children().eq(1).children().eq(1).children().css({ width : val + "%"});

            if(val == 100){

               $("tr").eq(index).children().eq(2).children().html("HECHA");
               $("tr").eq(index).children().eq(2).children().css({backgroundColor : "#1CC131"});

                    switch(true){
                        case $("tr").eq(index).hasClass("Pv"): 
                                 $("tr").eq(index).removeClass("Pv");
                        break;
                        case $("tr").eq(index).hasClass("At"):
                                 $("tr").eq(index).removeClass("At");
                        break;
                        case $("tr").eq(index).hasClass("Pe"):
                                 $("tr").eq(index).removeClass("Pe");
                        break;
                    }

                 $("tr").eq(index).addClass("Hc");    
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



</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>