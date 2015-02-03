<?php session_start();
if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'sadmin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
                                                                
$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtFacility']);


$Query_task = mysqli_query($datos, "SELECT A.ISS_SUBJECT, D.CTZ_NAMES,  C.USR_NAME, B.EST_DESCRIPT, B.EST_COLOR, SUBSTRING(A.ISS_FINISH_DATE, 1, 10) , C.USR_SURNAME, D.CTZ_SURNAME1, D.CTZ_SURNAME2 FROM ISSUES A INNER JOIN EST B ON(B.EST_CODE = A.ISS_STATE) INNER JOIN USERS C ON(C.USR_ID = A.ISS_CHARGE_USR)  INNER JOIN CITIZENS D ON(D.CTZ_RUT = A.ISS_CTZ) WHERE ISS_FAC_CODE = " . $_SESSION['TxtFacility'] . ";");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>E-Que</title>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
        <style type="text/css">
#DataTables_Table_0_filter input{
width:30em;
}

.dataTables_wrapper>div+div{
    float:left;
    right:-0.1em;
}


#DataTables_Table_0_length{
   float: right;
}
        #client_logo{
            max-width: 3em;
            border:0;
            margin:0;
            padding:0;
        }
        .progressDisplay{
            max-width:100%;
            -webkit-transition: all 600ms ease-in-out;
            -moz-transition: all 600ms ease-in-out;
             transition: all 600ms ease-in-out;
             margin-left: 1em;

        }

        .linerchart{
            width:33.33%;
            display: inline-block;
            vertical-align: top;
        }
    .wrap-progress{
width: 66%;
float: right;
display: inline-block;
vertical-align: top;
    }


@media (max-width : 768px){
   .wrap-progress{
    width: 100%;
   }
}

#suite{
    display:none;
}
#back{
position: relative;
float:right;
vertical-align: top;
}

.situation{
cursor:pointer;
}

.situation:hover{
background-color: white;
}
        </style>
        <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="../css/theme.css" rel="stylesheet">
        <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
            rel='stylesheet'>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html"><img id="client_logo" src="../images/vdm.jpg"> </a>
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
                                </ul>
                            </li>
                            <li><a href="#">Support </a></li>
                            <li class="nav-user dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
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
                                <li class="active">
                                    <a href="index.php">
                                    <i class="menu-icon icon-dashboard">
                                    </i>
                                     Vista Principal
                                    </a>
                                </li>
                                <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario </a></li>
                            </ul>

                            <!--/.widget-nav-->
                            
                            <!--
                            <ul class="widget widget-menu unstyled">
                                <li><a href="ui-button-icon.html"><i class="menu-icon icon-bold"></i> Buttons </a></li>
                                <li><a href="ui-typography.html"><i class="menu-icon icon-book"></i>Typography </a></li>
                                <li><a href="form.html"><i class="menu-icon icon-paste"></i>Forms </a></li>
                                <li><a href="table.html"><i class="menu-icon icon-table"></i>Tables </a></li>
                                <li><a href="charts.html"><i class="menu-icon icon-bar-chart"></i>Charts </a></li>
                            </ul> -->

                            <!--/.widget-nav-->
                            <ul class="widget widget-menu unstyled">
                                <li><a class="collapsed" data-toggle="collapse" href="#togglePages"><i class="menu-icon icon-cog">
                                </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
                                </i>Estadisticas</a>
                                    <ul id="togglePages" class="collapse unstyled">
                                        <li><a href="graph.php"><i class="icon-inbox"></i>Gráficos</a></li>
                                    </ul>
                                </li>
                                <li><a href="backend/close.php"><i class="menu-icon icon-signout"></i>Logout </a></li>
                            </ul>
                        </div>
                        <!--/.sidebar-->
                    </div>
                    <!--/.span3-->
                    <div class="span9">
                        <div class="content">
                            <div class="btn-controls">
                                <div class="btn-box-row row-fluid">
                                <a id="Audiencias" href="#" class="btn-box big span4" style="height: 266px; padding-top: 6em;"><i class="icon-user"></i><b>15</b>
                                        <p class="text-muted">
                                            Audiencias</p>
                                    </a>
                                    <div class="wrap-progress" >
                                     <ul class="widget widget-usage unstyled progressDisplay" id="Audi-Display">
                                        <li>
                                            <p>
                                             <strong>Audiencias Recibidas</strong> <span class="pull-right small muted">78%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 78%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias en Curso</strong> <span class="pull-right small muted">38%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 18%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias por vencer</strong> <span class="pull-right small muted">44%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 44%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias Atrasadas</strong> <span class="pull-right small muted">67%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 67%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                </div>
                            </div>
                            <!--/#btn-controls-->
                            <!--/.module-->
                            <div class="module hide">
                                <div class="module-head">
                                    <h3>
                                        Adjust Budget Range</h3>
                                </div>
                                <div class="module-body">
                                    <div class="form-inline clearfix">
                                        <a href="#" class="btn pull-right">Update</a>
                                        <label for="amount">
                                            Price range:</label>
                                        &nbsp;
                                        <input type="text" id="amount" class="input-" />
                                    </div>
                                    <hr />
                                    <div class="slider-range">
                                    </div>
                                </div>
                            </div>
                            <div class="module">
                                <div class="module-head">
                                    <h3>
                                        DataTables</h3>
                                </div>
                                <div class="module-body table">
                                    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Asunto
                                                </th>
                                                <th>
                                                    Ciudadano
                                                </th>
                                                <th>
                                                    Encargado
                                                </th>
                                                <th>
                                                    Situación
                                                </th>
                                                <th>
                                                    Fecha Finalización
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <? while ($issues = mysqli_fetch_row( $Query_task )){ ?>
                                            <tr class="gradeA">
                                                <td>
                                                    <? printf($issues[0]) ?>
                                                </td>
                                                <td>
                                                    <? printf($issues[1]) ?> <? printf($issues[7]) ?> <? printf($issues[8]) ?>
                                                </td>
                                                <td>
                                                    <? printf($issues[2]) ?> <? printf($issues[6])?>
                                                </td>
                                                <td class="center situation" style="color: <? printf($issues[4]) ?>">
                                                    <? printf($issues[3]) ?>
                                                </td>
                                                <td class="center">
                                                    <? printf(substr($issues[5], 0, 10)) ?>
                                                </td>
                                            </tr>
                                       <? } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>
                                                    Asunto
                                                </th>
                                                <th>
                                                    Ciudadano
                                                </th>
                                                <th>
                                                    Encargado
                                                </th>
                                                <th>
                                                    Situación
                                                </th>
                                                <th>
                                                    Fecha Finalización
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                              <div id="suite">
                                 <div class="docs-example">
                                      <div id="back"><i class="fa fa-chevron-circle-right fa-2x" style="color: rgba(38, 134, 244, 0.9);cursor: pointer;"></i></div>
                                        <dl class="dl-horizontal">
                                            <dt>Asunto</dt>
                                            <dd>
                                                A description list is perfect for defining terms.</dd>
                                            <dt>Encargado</dt>
                                            <dd>
                                                Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
                                            <dd>
                                                Donec id elit non mi porta gravida at eget metus.</dd>
                                            <dt>Detalle Audiencia</dt>
                                            <dd>
                                                Etiam porta sem malesuada magna mollis euismod.</dd>
                                            <dt>Fecha De Entrega</dt>
                                            <dd>
                                                Etiam porta sem malesuada magna mollis euismod.</dd>
                                        </dl>
                                        <p class="adjuste">
                                            <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                        </p>
                                            <div class="progress tight">
                                                <div class="bar bar-info"></div>
                                            </div>
                                        <pre class="pre files">
                                             

                                        </pre>
                                    </div>
                              </div>

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
                <b class="copyright">&copy; 2015 Eque-e - eque-e.cl </b>Todos los derechos reservados.
            </div>
        </div>
        <script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.pie.js" type="text/javascript"></script>
        <script src="../scripts/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../scripts/common.js" type="text/javascript"></script>
      
    </body>
<script type="text/javascript">
    
$(document).on('ready', function(){



    $("#Audiencias").on('click', function(){
      if($(this).data("val") === 1){
     $("#Audi-Display").css({ opacity : "0"}); 
          $(this).data("val", 0);
      } else {

          $("#Audi-Display").css({ opacity : "1"}); 
            $(this).data("val", 1);  
      }
    });


$("#cleanup").on("click", function(){
    $("input[type=text]").val('');
    $("textarea").val('');
})
});


// switch para colorear el icono segun valores pasados
function setIconState(objId){

var y = parseFloat($("#" + objId + " li:nth-child(2) span").html());
var delayed = parseFloat($("#" + objId + " li:nth-child(4) span").html());

 var x = parseInt(y);
console.info(x);

switch(true){
  case(x >= 0 && x < 9 ):
$("#" + objId + " > i").css({'color' : 'rgb(255,'+ parseInt((x*0.56)) + ',0)'});
 console.info('rgb(255,' + (x*2) + ',0)');
  break;
  case(x >= 10 && x < 19 ):
$("#" + objId + " > i").css({'color' : 'rgb(255,' + parseInt((x*0.56)) + ',0)'});
console.info('rgb(255,' + (x*2) + ',0)');
  break;
  case(x >= 20 && x < 29):
$("#" + objId + " > i").css({'color' : 'rgb(255,' + parseInt((x*0.56)) + ',0)'});
console.info('rgb(255,' + (x*2) + ',0)');
  break;
  case(x >= 30 && x < 39):
$("#" + objId + " > i").css({'color' : 'rgb(255,' + parseInt((x*0.56)) + ',0)'});
 console.info('rgb(255,' + parseInt(255 - (x*0.56)) + ',0)');
  break;
  case(x >= 40 && x < 49):
$("#" + objId + " > i").css({'color' : 'rgb('+ parseInt(255 - (x*0.56)) + ',255,0)'});
 console.info('rgb('+ parseInt(255 - (x*0.56)) + ',255,0)');
  break;
  case(x >= 50 && x < 59):
$("#" + objId + " > i").css({'color' : 'rgb(' + parseInt(255 - (x*0.56))  + ',255,0)'});
console.info('rgb(' + parseInt(255 - (x*0.56))  + ',255,0)');
  break;
  case(x >= 60 && x < 69):
$("#" + objId + " > i").css({'color' : 'rgb(' + parseInt(255 - (x*0.56)) +',255,0)'});
console.info('rgb(' + parseInt(255 - (x*0.56)) +',255,0)');
  break;
  case(x >= 70 && x < 79):
$("#" + objId + " > i").css({'color' : 'rgb(' + parseInt(255 - (x*0.56)) +',255,0)'});
console.info('rgb(' + parseInt(255 - (x*0.56))+',255,0)');
  break;
  case(x >= 80 && x < 100):
$("#" + objId + " > i").css({'color' : 'rgb(0,255,0)'});
break;
        }
}


$(".situation").on('click', function(){
    $(this).parent().parent().parent().parent().fadeOut("slow", function(){
            $("#suite").fadeIn("slow");
        });
});


$("#back").on('click', function(){
    $("#suite").fadeOut("slow", function(){
        $("#DataTables_Table_0_wrapper").fadeIn('slow');
    });
});


</script>

<?

 } else {
    echo "<script language='javascript'>window.location='../index.php'</script>";
 }  ?>