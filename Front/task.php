<?php session_start();


if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange']){


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASK

$query_str_issues = "SELECT A.ISS_ID, " . 
 "A.ISS_DATE_ING,  " . 
 "A.ISS_DESCRIP,  " . 
 "B.EST_DESCRIPT,  " . 
 "B.EST_COLOR, " . 
 "A.ISS_PROGRESS, " . 
 "A.ISS_FINISH_DATE, " . 
 "A.ISS_CTZ, " . 
 "CONCAT(C.CTZ_NAMES, ' ' , C.CTZ_SURNAME1, ' ', C.CTZ_SURNAME2 ) ,   " . 
 "C.CTZ_ADDRESS,  " . 
 "C.CTZ_GEOLOC,  " . 
 "C.CTZ_TEL, " .
 "D.CAT_DESCRIPT, " .
 "A.ISS_CHARGE_USR"  
"FROM ISSUES A INNER JOIN EST B ON(A.ISS_STATE = B.EST_CODE) " .
"INNER JOIN CITIZENS C ON(C.CTZ_RUT = A.ISS_CTZ) " .
"INNER JOIN CAT D ON(D.CAT_ID = A.ISS_TYPE)  WHERE A.ISS_FAC_CODE = " . $_SESSION['TxtFacility'];


$Query_task = mysqli_query($datos, $query_str_issues );

$Query_depts = mysqli_query($datos, "SELECT DISTINCT USR_DEPT FROM USERS WHERE USR_FACILITY = " .  $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");

$cantidad = mysqli_fetch_assoc(mysqli_query($datos, "SELECT COUNT( ISS_ID ) AS CANT FROM ISSUES WHERE (ISS_STATE = 1 AND ISS_FAC_CODE = " . $_SESSION['TxtFacility'] . ");"));

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
	<link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
	<link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/selectize.bootstrap3.css" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="../css/jquery.plupload.queue.css" type="text/css" media="screen" />
	<script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>

    	<script type="text/javascript">
		$(document).on('ready', function(){
           
		});

	</script>
	<style type="text/css">
     .description {
      width:90%;
     }
    .delegates{
    width:50%;
    }

.Pe{
    display: table-row;
}
.Ec, .Hc, .At, .Pv{
    display: none;
}

.progress , .adjuste{
	width:50%;
}

.file-contents{
	display:inline-block;
	vertical-align: top;
}

.ifile{
	display: inline-block;
	vertical-align: top;
	margin:.4em;
}

.requirement{
	display:none;
}
.display-progress{
	display:none;
}


	</style>


</head>
<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
					<i class="icon-reorder shaded"></i>
				</a>

			  	<a class="brand" href="index.html">
			  		E-Que
			  	</a>

				<div class="nav-collapse collapse navbar-inverse-collapse">
					<ul class="nav nav-icons">
						<li class="active"><a href="#">
							<i class="icon-envelope"></i>
						</a></li>
						<li><a href="#">
							<i class="icon-eye-open"></i>
						</a></li>
						<li><a href="#">
							<i class="icon-bar-chart"></i>
						</a></li>
					</ul>

					<form class="navbar-search pull-left input-append" action="#">
						<input type="text" class="span3">
						<button class="btn" type="button">
							<i class="icon-search"></i>
						</button>
					</form>
				
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Item No. 1</a></li>
								<li><a href="#">Don't Click</a></li>
								<li class="divider"></li>
								<li class="nav-header">Example Header</li>
								<li><a href="#">A Separated link</a></li>
							</ul>
						</li>
						<li><a href="#">
							Support
						</a></li>
						<li class="nav-user dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="images/user.png" class="nav-avatar" />
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#">Your Profile</a></li>
								<li><a href="#">Edit Profile</a></li>
								<li><a href="#">Account Settings</a></li>
								<li class="divider"></li>
								<li><a href="../backend/close.php">Logout</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.nav-collapse -->
			</div>
		</div><!-- /navbar-inner -->
	</div><!-- /navbar -->



	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="span3">
					<div class="sidebar">

						<ul class="widget widget-menu unstyled">
							<li>
								<a href="activity.php">
									<i class="menu-icon icon-bullhorn"></i>
									ingreso de Compromisos Externos
								</a>
							</li>

							
							<li>
								<a href="task.php">
									<i class="menu-icon icon-tasks"></i>
									Control de Cumplimientos
									<b id="counter-task" class="label orange pull-right"><? printf($cantidad['CANT']) ?></b>
								</a>
							</li>
						</ul><!--/.widget-nav-->

					</div><!--/.sidebar-->
				</div><!--/.span3-->
				<div class="span9">
					<div class="content">
						<div class="module message">
							<div class="module-head">
								<h3>Control de cumplimientos</h3>
							</div>
							<div class="module-option clearfix">
								<div class="pull-left">
									Filtro : &nbsp;
									<div class="btn-group">
										<button class="btn" id="filter-title">Pendientes</button>
										<button class="btn dropdown-toggle" data-toggle="dropdown">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
										    <li class="switcher" id="Pe"><a href="#" >Pendientes</a></li>
											<li class="switcher" id="Ec"><a href="#" >En Curso</a></li>
											<li class="switcher" id="Hc"><a href="#" >Finalizados</a></li>
											<li class="switcher" id="Pv"><a href="#" >Por Vencer</a></li>
											<li class="switcher" id="At"><a href="#" >Atrasados</a></li>
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
											<td class="cell-icon"></td>
											<td class="cell-title">Requerimiento</td>
											<td class="cell-status hidden-phone hidden-tablet">Estado</td>
											<td class="cell-time align-right">Fecha de Atención</td>
										</tr>

						<?
			$class = "";			
            $i = 1;
     
						 while ($fila1 = mysqli_fetch_row($Query_task)){ 

                                          switch ($fila1[3]){
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
										    <input type="hidden" value="<? printf($fila1[7])?>">
											<td class="cell-icon" style="margin-right: 1em;"><? printf($fila1[0]) ?></td>
											<td class="cell-title"><div><? printf($fila1[2]) ?></div></td>
											<td class="cell-status hidden-phone hidden-tablet"><b class="due done" style="background-color:<? printf($fila1[4])?>"><? printf($fila1[3]) ?></b></td>
											<td class="cell-time align-right"><? 
											if ($fila1[4] == 'pendiente'){
											printf(date("d/m/Y", strtotime(substr($fila1[1], 0, 10))));
										} else {
											printf(date("d/m/Y", strtotime(substr($fila1[6], 0, 10))));
										}
											 ?></td>
											<input type="hidden" value="" >		
										</tr>
                                        <? 
                                        if($class == "Pe"){ 
                                        	?>


										<tr class="requirement  <? printf($class) ?>">
											<td colspan="4" >
												<textarea class="description" placeholder="describa el requerimiento"></textarea>
												<label for="subject">Delegados</label>
												<select id="delegates<? printf($i)?> ">
                                       <? 

                                       while( $deptos = mysqli_fetch_row($Query_depts)){ 
                                       	?>
                                   
                                            <? 
        
                                   $Query_personal = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM `USERS` WHERE (USR_FACILITY = " .  $_SESSION['TxtFacility'] . " AND USR_DEPT= '" . $deptos[0] ."' AND USR_RANGE= 'admin'); ");
                                        while($per = mysqli_fetch_row($Query_personal)){ 
                                                
                             ?>
                                              <option value="<? printf($per[0]) ?>"><? printf($per[1])?> <? printf($per[2])?> - <? printf(strtoupper($deptos[0])) ?></option>
                             <?
                                            	}
                                         mysqli_data_seek($Query_personal, 0);

                                            	?>

                                          <?
                                          
                                          }

                                          mysqli_data_seek($Query_depts, 0);
                                        ?>

												</select>
												<i class="fa fa-warning"></i><i class="fa fa-envelope"></i>
                                                <input type="text" placeholder="Fecha Termino" class="datetimepicker" styles="vertical-align:top; display: inline-block;"/><br><br>
												<button class="btn-info enviar">Delegar Compromiso</button>
											</td>
										</tr>   

                                   <? } else { ?>

                                        <tr class="display-progress ">
                                            <td colspan="5">
                                  <div class="info-content" style="display:none">
                                 <div class="docs-example">
                                      <div id="back"><i class="fa fa-chevron-circle-right fa-2x" style="color: rgba(38, 134, 244, 0.9);cursor: pointer;"></i></div>
                                        <dl class="dl-horizontal">
                                            <dt></dt>
                                            <dd>
                                               </dd>
                                            <dt>Ciudadano</dt>
                                            <dd><? echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($fila1[8])))); ?></dd>
                                            <dt>Dirección</dt>
                                            <dd><? echo $fila1[9] ?></dd>
                                            <dt>Telefono</dt>
                                            <dd><? echo $fila[11] ?></dd>
                                            <dt>Descripción</dt>
                                            <dd><? echo $fila1[2] ?></dd>
                                            <dt>Origen</dt>
                                            <dd><? echo $fila[12] ?></dd>
                                        </dl>
                                        <p class="adjuste">
                                            <strong>Grado de progreso</strong><span class="pull-right small muted"></span>
                                        </p>
                                            <div class="progress tight">
                                                <div class="bar forward"></div>
                                            </div>
                                        <div class="files"></div>
                                        <pre class="pre" style="display:inline-flex; width: 100%">
                                                                          <?    
                                   
                                        if($handler2 = opendir("../" . $_SESSION['TxtFacility'] . "/" . $_SESSION['TxtCode'] . "/" )){

                                          $file_extension2 = "";
                                        
                                           while (false !== ($archivos2 = readdir($handler2))){
                                          
                                            if(preg_match_all("/_" . $fila[13] . "_/", $archivos2) == 1){
                                     
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
                                        </pre>
                                    </div>
                                   </div>
                                            <p class="adjuste" style="display: inline-block;">
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila1[5]) ?>%</span>
                                            </p>
                                            <div class="file-contents">
                                              
                                         <? 


                          if (!is_dir("../" . $_SESSION['TxtFacility'] . "/reply/")){

                          	   mkdir("../" . $_SESSION['TxtFacility'] . "/reply/", 0775, true);
                          }
                      

                            if($handler = opendir("../" . $_SESSION['TxtFacility'] . "/reply/" )){
                                     
                                         while (false !== ($archivos = readdir($handler))){ 
                                      
                                             if(preg_match_all("/_\[" . $fila1[0] . "\]_/", $archivos) == 1){

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

                                                <a href="../<? printf($_SESSION['TxtFacility'])?>/reply/<? printf($archivos)?>" download><p class="ifile" title="<? printf($archivos) ?>"><i class="fa fa-file-<? printf($file_extension) ?>o fa-2x" style="color: <? printf($cor) ?> "></i>
                                                 
                                                       </p>
                                                </a>          
                                                  <? }
                                                     } 
                                                       closedir($handler);
                                                     } // fin open dir
                                                    

                                                  ?>
                                                
                                            </div>
                                           <div class="progress tight" style="display: inline-block;">
                                                <div class="bar bar-warning" style="width: <? printf($fila1[5]) ?>%;"></div>
                                            </div>
                                            </td>
                                        </tr>

                    <? } ?>

                <? 
       $i = $i + 1;
               }  ?>
									</tbody>
								</table>


							</div>
							<div class="module-foot">
							   <div id="attach"></div>
							</div>
						</div>
						
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->
	<div class="footer">
		<div class="container">
			<b class="copyright">&copy; Eque-e.cl </b> Todos los derechos resevados.
		</div>
	</div>
	<script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
	<script type="text/javascript" src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="../scripts/plupload.full.min.js"></script>  
    <script type="text/javascript" src="../scripts/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="../scripts/es.js"></script>
</body>
<script type="text/javascript">


var fac = <?  printf($_SESSION['TxtFacility']) ?>;
var iden_iss;
$(document).on('ready', function(){
	//uploaderInt($("#attach"));
	//$("#attach").fadeOut(1);

$(".due").on('click', function(){
	if($(this).parent().parent().hasClass("Pe")){
		uploaderInt($("#attach"));
	}
});

});


$('.datetimepicker').datetimepicker({
	step:5,
	lang:'es',
    format:'Y/m/d',
    timepicker: false,
    onShow: function (ct){
        this.setOptions({
            minDate : '-1970/01/02',
            format:'d/m/Y'
        })
    }
});

$(".due").on('click', function (){
   $(this).parent().parent().next('tr').fadeToggle('slow');
   iden_iss = $(this).parent().parent().children('input').eq(0).val();

   if($(this).data("val") == 0 || $(this).data("val") == "" || $(this).data("val") == undefined){
   	  if($(this).parent().parent().hasClass("Pe")){
              $("#attach").fadeIn("slow");
   	     $(this).data("val", 1);
   	  }
   } else {
   	     $("#attach").fadeOut("slow");
              $(this).data("val", 0);
   }


});


$(".enviar").on('click', function () {

   var check  = checker($(this));
   
   if (!check || check == false){
     
     var iss_id = $(this).parent().parent().prev().children('td').eq(0).text();
	
	 $(this).parent().fadeOut('fast');
	 $(this).parent().parent().prev().fadeOut('fast');

     console.info("/" + iss_id +"/");
	 //variables 
    var msg = $(this).parent().children('textarea').val();

    var usr_id = $(this).parent().children('select').val();
    var fechaF = $(this).parent().children("input.datetimepicker").val();

    $("#attach").fadeOut("slow");

   delegate(usr_id, msg, fechaF, iss_id);
   switchTempToAsigned(iss_id, usr_id);

   } 

});

$(".switcher").on('click', function(){

	 var all_on = document.querySelectorAll('.switcher');
     var ex = $(this).attr("id");
     var name =  $(this).html();

 if(ex == "Pe"){

    document.querySelectorAll(".heading > td")[3].innerHTML = "Fecha de Atención";

} else {

    document.querySelectorAll(".heading > td")[3].innerHTML = "Fecha Máxima de entrega";
    
} 

     $(".display-progress").css({ display: "none"});
     $("#filter-title").html(name);

     for(i=0; i < all_on.length ; i++){
           if(all_on[i].id !== ex){
              $('.' + all_on[i].id).css({ display : "none"});
           } else {
              $('.' + all_on[i].id).css({ display: "table-row"});
           }
     }

})

function delegate(usr_id, msg, fechaF, iss_id){

var _fS = new Date();

fechaS = _fS.getFullYear() + "-" + ('0' + _fS.getMonth()+1).slice(-2) + "-" + ('0' + _fS.getDate()).slice(-2) + " 10:00:00";

var fech = reverseDate(fechaF) + " 10:00:00" ;
$.ajax({
	type: "POST",
	url: "../backend/delegate.php?fac=" + fac + 
	"&usr_id=" + usr_id + 
	"&msg=" + msg + 
	"&dataF=" + fech + 
	"&dataS=" + fechaS + 
	"&iss_id=" + iss_id + 
	"&fac=" + fac,
	success : function (data){

		if (parseInt(data) == 1){

       console.info('works');

       $("#counter-task").html(parseInt($(this).html()) -1);

		} else {

      console.info('not works');

		}
	}
});
}

function reverseDate(string){
 return string.substring(6,10) + "-" + string.substring(3,5) + "-" +string.substring(0,2);
}


function switchTempToAsigned(iss_id, usr_id){
$.ajax({
	type: "POST",
	url: "../backend/move.php?iss_id=" + iss_id + "&usr_id=" + usr_id + "&fac=" + fac,
	success :  function (data){
		console.info(data);
	}
})
}

function checker(object){
    if(object.parent().children('.datetimepicker').val() == ""){
    	bootbox.alert("igresar fecha de termino");
    	return true;
    }
    if(object.parent().children('.description').val() == ""){
    	bootbox.alert("Ingresar descripcion requerimiento")
          return true;
    }
        return false;
}



var uploaderInt = function(object){

uploader =  $(object).pluploadQueue({
        runtimes : 'html5',
        url : '../backend/upload_front.php?'  ,
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
                up.setOption("url", '../backend/upload_front.php?fac_id=' + fac + "&rut=" + iden_iss);
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
</script>
<?

} else {
	echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>

