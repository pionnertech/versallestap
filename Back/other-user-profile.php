<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'back-user'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS

$Query_task = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_SUBJECT, A.STSK_DESCRIP, SUBSTRING(A.STSK_FINISH_DATE, 1, 10), B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.STSK_START_DATE, 1, 10) , A.STSK_PROGRESS FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE ( STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " AND STSK_LOCK = 1)");
$Query_alerts = mysqli_query($datos, "SELECT COUNT(STSK_ID), STSK_STATE FROM SUBTASKS WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " GROUP BY STSK_STATE");

$str_query = "SELECT STSK_DESCRIP FROM `SUBTASKS` WHERE STSK_CHARGE_USR = " . $_SESSION['TxtCode'] . " ORDER BY STSK_START_DATE DESC LIMIT 1";
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
                            <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario</a></li>
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
                              <p class="iss-descript"><strong>Descripcion Compromiso</strong> : <? printf($shine['ISS_DESCRIP']) ?></p>            
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
            $("tr").eq(index+2).children("td").children().eq(1).children().eq(0).children('span').html(val + "%");
            $("tr").eq(index+2).children("td").children().eq(1).children().eq(1).children().css({ width : val + "%"});

            if(val == 100){

               $("tr").eq(index).children().eq(2).children().html("FINALIZADA");
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

    var source = new EventSource("../backend/sse-event.php?usr=" + mainuser);
    source.onmessage = function(event) {

       if (event.data !== previuosData){

        var eventMessage = event.data.split('\n');
        showAlert(eventMessage[0]);

        previuosData = event.data;

    } else {
        
    }
}

} else {

    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";

}

</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../index.php'</script>";
}


?>