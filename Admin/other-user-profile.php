<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'admin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

$Query_team = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM USERS WHERE (USR_FACILITY = " . $_SESSION['TxtFacility'] . " AND USR_RANGE = 'back-user' AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "');");
$Query_subtask = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_DESCRIP, B.EST_DESCRIPT, A.STSK_FINISH_DATE, B.EST_COLOR, A.STSK_PROGRESS, A.STSK_LOCK FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE (STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_FAC_CODE = " . $_SESSION['TxtFacility'] . " )" );
$Query_alerts = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " GROUP BY STSK_STATE");

$str_trf_usr = "SELECT DISTINCT A.TRF_USER, CONCAT(B.USR_NAME , ' ' ,  B.USR_SURNAME) FROM TRAFFIC A INNER JOIN USERS B ON(A.TRF_USER = B.USR_ID) WHERE (TRF_FAC_CODE = " . $_SESSION['TxtFacility'] . " AND USR_DEPT = '" .  $_SESSION["TxtDept"] . "') ORDER BY TRF_USER; ";
$Query_trf_usr = mysqli_query($datos, $str_trf_usr);

$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " ORDER BY STSK_ID DESC LIMIT 1";
$notify = mysqli_fetch_assoc(mysqli_query($datos, $str_query));



if(!$notify){

    $manu = "";
} else {

    $manu = $notify['STSK_DESCRIP'];
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
    <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
    <link rel="stylesheet" href="../css/jquery.plupload.queue.css" type="text/css" media="screen" />
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
    width:95%;
}

.attach{
 display: none;
 vertical-align: top;
}


.file-contents{
width: 100%;
}

.file-contents, 
.file-contents p{
    display: inline-block;
    vertical-align: top;
}

.display-progress{
display:none;
}


.At{
    display: table-row;
}
.Ec, .Hc, .Pe, .Pv{
    display: none;
}

.ifile{
    margin:.5em;
    display: inline-block;
    vertical-align: top;
    cursor: pointer;
}


.iname{
    display:block;
    text-align: left;
}

#wrap-D{
    display: inline-block;
    max-height: 20em;
}

.toggle-attach{
    float:right;
    background-color: gray;
    border-radius: 15px;
}

.toggle-attach i{
    color:white;
    padding:.2em;
}
#D-drop{
height:20em;
width:20em;
float:right;
background-color: white ;
border-radius: 20px;
border: 1px orange solid;
overflow-y: auto;
overflow-x: hidden;
}

#D-drop:after{
content: "Arrastre aqui sus archivos";
color: gray;
position: relative;
top: 8em;
left: 2em;
font-style: italic;
font-size: 1.3em;
}

.attach, #wrap-D{
    -webkit-transition: all 600ms ease-in-out;
    -moz-transition: all 600ms ease-in-out;
    transition: all 600ms ease-in-out;
}

.after:after{
content: "Arrastre aqui sus archivos";
}

.no-after:after{
    content:"";
}

.collaborates{
    width:80%;
}

.collaborates, .collaborates p{
    display: inline-block;
    vertical-align: top;
    font-size: .8em;
    font-style: italic;

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

.incoming-files{
    display:none;
}

#froback{
    position: relative;
    float: right;
    color:darkgray;
    font-style: italic;

}

.spac{
    margin-right:.8em;
    color: #1e5799; /* Old browsers */
   filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1e5799', endColorstr='#207cca',GradientType=0 ); /* IE6-9 */

}

.golang i, .spac{
    font-size: 1.5em;
}
.golang, .collaborates, .wrap-events{
    display:inline-block;
    vertical-align: top;
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

.events{
color: #24B56C;
font-size: 1.5em;
}

.wrap-events{
width:auto;
margin: 0 .5em;
}
.chrono{
    display: none;
}

#back-to-main i{
cursor: pointer;
}
#back-to-main i:hover{
color: lightgreen;
}

.user-schedule{
    width:100%;
    height: auto;
}

      .wrap-charts{
        display:none;
      }
      strong {
        font-size:.8em;
      }
      .progressDisplay li{
        padding: 5px;
      }
  .utrf{
    display: none;
  }

.bolder{
    font-weight: bolder;
}

.group{
  width: 8%;
  border-radius: 50%;
  padding: 6px;
  border: 1px solid lightgrey;
  border-radius: 50%;
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
                                11</b> </a></li>
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
                                        

                                  <? }?>
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
                                                        <a class="media-avatar pull-left stusr"  >
                                                            <img src="../images/ejecutivo3.jpg">
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
                                                            <img src="../images/ejecutivo3.jpg">
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
          "ROUND((COUNT(A.STSK_ID)/(SELECT count(STSK_ID) FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $fila_per[0]. ")) * 100) AS percentage  " .
          "FROM SUBTASKS A RIGHT JOIN EST B ON(B.EST_CODE = A.STSK_STATE AND  STSK_CHARGE_USR = " . $fila_per[0]. ")  " .
          "GROUP BY B.EST_DESCRIPT";
$handler2 = mysqli_query($datos, $matrix2);

                                                        ?>
                                                        <div class="media">
                                                            <div class="wrap-charts wc">
                                                                <ul class="widget widget-usage unstyled progressDisplay">
                                                             <? while( $uI2 = mysqli_fetch_row($handler2)) {?>
                                                                    <li>
                                                                        <p>
                                                                         <strong>Compromisos <? printf($uI2[2]) ?></strong> <span class="pull-right small muted"><? printf($uI[0]) ?> / <? printf($uI[3]) ?>%</span>
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
                                        <button class="btn" id="showtitle" >Todos</button>
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
                                            <td class="cell-time align-right">Fecha</td>

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
                                            <input type="hidden" id="st" value="<? printf($stsk[0]) ?>">
                                            <input type="hidden" id="iss_id" value="<? printf($stsk[1]) ?>">
                                        </tr>
                                        <tr class="display-progress">
                                            <td colspan="5">
                                    <div class="info-content">
                                            <? 
$shine = mysqli_fetch_assoc(mysqli_query($datos, "SELECT A.ISS_DESCRIP ,  B.CTZ_NAMES FROM ISSUES A INNER JOIN CITIZENS B ON (A.ISS_CTZ = B.CTZ_RUT) WHERE ISS_ID = " . $stsk[1] ));
                                            ?>
                              <p class="iss-descript"><strong>Ciudadano</strong> : <? printf($shine['CTZ_NAMES']) ?></p> 
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
    <div style="display : inline-block">
        <a href="#" class="hovertip" style=" width:4em; height: 4em" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila_spec[0]))))) ?>">
            <img src="../img/<? echo $fila_spec[1]; ?>.jpg" class="group" >
        </a>
        <input type="hidden" value="u<? printf($fila_spec[1])?>">
    </div>
   
    <?  }  ?>
    
                                            </div>
                                            <p class="golang"><i class="fa fa-paperclip" style="color:darkyellow;"></i></p>
                                            <p class="wrap-events"><i class="fa fa-history events"></i></p>
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
                            <table class="table table-message">
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
                                                <a href="#" class="media-avatar pull-left hovertip" style=" width:4em; height: 4em" title="<? printf(str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($trf[1]))))) ?>">
                                                    <img src="../img/<? printf($trf[0]) ?>.jpg" style="width: 100%; height: 100%">
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
                                       <div class="tab-pane fade" id="int-require">
                                            <div class="module message">
                                                   <div class="module-head">
                                                       <h3>Compromisos Internos</h3>
                                                   </div>
                                            <div class="module-option clearfix">
                                                    <div class="pull-left">
                                                        Filtro : &nbsp;
                                                        <div class="btn-group">
                                                            <button class="btn">Todos</button>
                                                            <button class="btn dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="#"></a></li>
                                                                <li><a href="#">En Curso</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                <div class="pull-right">
                                </div>
                            </div>
                         <div class="module-body table">
                                                   <table class="table table-message">
                                                      <tbody>
                                                          <tr class="heading">
                                                              <td class="cell-icon"><i class="fa fa-exclamation"></i></td>
                                                              <td class="cell-title">Requerimiento</td>
                                                              <td class="cell-status hidden-phone hidden-tablet">Status</td>
                                                              <td class="cell-title">Responsable</td>
                                                              <td class="cell-time align-right">Fecha</td>
                                                            </tr>
                                                            <tr class="task">
                                                                <td class="cell-icon"><i class="icon-checker high"></i></td>
                                                                <td class="cell-title"><div>Enviar personal tecnico en terreno para verificar y reparar las fallas</div></td>
                                                                <td class="cell-status hidden-phone hidden-tablet"><b class="due done">Hecho</b></td>
                                                                <td class="cell-title">juanito perez</td>
                                                                <td class="cell-time align-right"><div>19/04/2013</div></td>
                                                            </tr>
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
    <script type="text/javascript" src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="../scripts/plupload.full.min.js"></script>  
    <script type="text/javascript" src="../scripts/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="../scripts/es.js"></script>
</body>

<script type="text/javascript">
    
    var st = 0;
    var fac = <? printf($_SESSION['TxtFacility'] ) ?>;
    var dateTime;
    var previuosData = <? printf("\"" . $manu . "\"")  ?>;
    var um = 0;
    var mainuser = <? printf($_SESSION['TxtCode'])  ?>;
    
    $(document).on('ready', function(){

 dateTime = $('.datetimepicker').datetimepicker({
    step:5,
    lang:'es',
    format:'d/m/Y',
    timepicker: false
});



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
                                    
var stsk_id = $(this).parent().parent().children('input#st').val();
var iss_ident = $(this).parent().parent().children('input#iss_id').val();
var subject = $(this).parent().parent().children('td').eq(1).text();
var index_current = parseInt($(this).index());


$("#audititle").html("\"" + subject + "\"");
$("#current-task").val(index_current);

$(".ifile").css({display : "none"});
$(".iss" + iss_ident).css({ display : "inline-block"});

console.info(index_current);

$("#issId").val(iss_ident);
$("#stsk-code").val(stsk_id);

$('#delegates option:first-child').attr("selected", "selected");

var current = $("#delegates").val();

//fades
$("#kitkat li").eq(2).removeClass('active');$("#kitkat li").eq(3).addClass('active');
$("#require").removeClass('active in');$("#tasks-own").addClass('active in');

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
                var current_collaborates =  $(".collaborates").eq(target).html(ancient + data + " -");
               
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
  var stsk =  $(this).parent().children('input').eq(0).val();
  var iss_id = $(this).parent().children('input').eq(1).val();
 
   unlock(stsk, iss_id, $(this).children('i'));

});


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




function unlock(stsk_id, iss_id, object){

$.ajax({
       type: "POST",
       url: "../backend/unlock.php?stsk_id=" + stsk_id + "&iss_id=" + iss_id,
       success : function(data){
        console.log(data);
           object.fadeOut(400, function(){
           object.removeClass("fa-warning");
           object.addClass("fa-lock");
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

var uploaderInt = function(object, iss_id){

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
                up.setOption("url", '../backend/upload_for_front.php?fac_id=' + fac + "&iss_id=" + iss_id);
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


//historial de eventos 

$(".events").on('click', function(){

  //get the Classes by ID 
  // cambio de fotos
 var ucla =  $(this).parent().prev().prev().children('div').children('input');

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

    var source   = new EventSource("../backend/sse-event.php?usr=" + mainuser);
    var progress = new EventSource("../backend/time-pro-event.php?usr=" + mainuser);
    
    source.onmessage = function(event) {

       var eventMessage = event.data.split('\n');

       if (eventMessage[0] !== previuosData){
          
   console.info( eventMessage[0] + "/" + previuosData);

        showAlert(eventMessage[0]);
        inputTask(eventMessage[0], eventMessage[1], eventMessage[3], eventMessage[4], eventMessage[2]);

        previuosData = eventMessage[0];

    } else {
        
    }
}

    progress.onmessage =  function (event){
       
        progressMessage = event.data.split('\n');

            if (progressMessage[0] != preProgress) {


            }


   }



} else {

    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";

}



function inputTask(stsk_descript, stsk, iss, ctz, desc){

    var parent =  document.querySelector("#ext-tasks-table tbody");

    var tr1 = document.createElement('tr');
    tr1.className = "task";

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

    inp1.id = "st";
    inp2.id = "iss_id";
    
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
 
          unlock(stsk, iss_id, $(this));

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

            var stsk_id = $(this).parent().parent().children('input#st').val();
            var iss_ident = $(this).parent().parent().children('input#iss_id').val();
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

getFiles(function (data){
    
      var files =  data.split("|");
      var elem = [];
      var fileParent = document.querySelector('.incoming-files');

      for (n=0; n < files.length ; n++){
      
         elem[n]           = document.createElement('p');
         elem[n].className = "ifile iss" + iss;
         elem[n].id        = files[i];
         elem[n].setAttribute("draggable", true);

         elem[n].ondragstart = function(event){
                 drag(event);
          }
        var extension = files[i].substring(files[n].length -3 , files[n].length);
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


})
    
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
    i3.className = "fa fa-history events";
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


function updateProgress(){


}



</script>

<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>


