<?php 

session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'front-user'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS

$Query_task = mysqli_query($datos, "SELECT * FROM ISS WHERE FAC_CODE = " . $_SESSION['TxtCode'] );


//categorias

$Query_cat = mysqli_query($datos, "SELECT * FROM CAT WHERE CAT_FAC = " . $_SESSION['TxtFacility']);

$Query_depts = mysqli_query($datos, "SELECT DISTINCT USR_DEPT FROM USERS WHERE USR_FACILITY = " .  $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;"  );

$cantidad = mysqli_fetch_assoc(mysqli_query($datos, "SELECT COUNT( ISS_ID ) AS CANT FROM ISSUES WHERE (ISS_STATE = 1 AND ISS_FAC_CODE = " . $_SESSION['TxtFacility'] . ");"));

?>


<!DOCTYPE html>
<html lang="es">
<head>
<head>
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Eque-e</title>
	<link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="../css/theme.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
	<link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
	<link type="text/css" media="screen" href="../css/bootstrap-select.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/jquery.plupload.queue.css" type="text/css" media="screen" />

	<link type="text/css" href="../css/style.css">
	
	<style type="text/css">

    .wrap-picker{
    	position:relative;
    	width:auto;
    	display: inline-block;
    	vertical-align: top;
    }
    .wrap-picker:first-child{
    	margin-left: 2em;
    }
     .wrap-picker:last-child{
    	margin-right: 2em;
    }
    .wrap-ing-form{
    	text-align: left;
    }

     .wrap-ing-form input{
     	width:70%;
     }
     #dtp1{
     	width: 150px !important;
     }

     #wrap-map, #map{
     	     -webkit-transition: all 600ms ease-in-out;
            -moz-transition: all 600ms ease-in-out;
             transition: all 600ms ease-in-out;
     }

 #sub-wrap{
 		width:100%;
 }
#Geo{
	padding: 2px 0 0 3px;
}

#direccion{
	margin-bottom: 1.6em;
}

  	#sub-wrap, #direccion, #Geo{
   	display: inline-block;	
   	vertical-align: top;
   }

#del-wrap {

   height:0;
   -webkit-transition: all 600ms ease-in-out;
   -moz-transition: all 600ms ease-in-out;
   transition: all 600ms ease-in-out;
}

#del-wrap  div , #del-wrap input{

  -webkit-transition: all 600ms ease-in-out;
   -moz-transition: all 600ms ease-in-out;
   transition: all 600ms ease-in-out;
}


#newOrgin, #newOrgin input, #newOrgin button{
display: inline-block;
vertical-align: top;
}

#newOrgin, #wrap-html5{
width: 100%;
}

#wrap-html5{
   display:none;
}


#cleanup{
padding: .5em;
border-radius: 39px;
display: inline-block;
vertical-align: top;
color: white;
float: right;
background-color: rgb(114, 232, 111);
cursor:pointer;
}

#map {
	height:calc(100% - 0px);
}
div.dropdown-menu,  ul.dropdown-menu{
	max-width: 5em;
}

select {
 width: 500px !important;
}

.bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn){
    width:50%;
}
	</style>


</head>

<body>
<input type="hidden" value="" id="latlng">
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
					<i class="icon-reorder shaded"></i>
				</a>

			  	<a class="brand" href="index.php">
			  		Eque-e
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
						</li>
						<li class="nav-user dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $_SESSION['TxtCode'] ?>.jpg" class="nav-avatar" />
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
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
									Ingreso compromisos externos
								</a>
							</li>
							<li>
								<a href="task-new.php">
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

						<div class="module">
							<div class="module-head">
								<h3 style="display: inline-block; vertical-align: top;">Ingreso Compromiso Externo</h3>
								<p id="cleanup"><i class="fa fa-refresh" style="font-size:1.5em;"></i></p>
							</div>
							<div class="module-body">
									<div class="media stream new-update" align="center" id="intext">
									<div class="wrap-ing-form" style="vertical-align: top; display: inline-block; width:100%;">
					R.U.T &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="RUT" placeholder="R.U.T" style="width: 30% !important;" maxlength="12" class="ctz_data"/>
					          &ensp;&ensp;&ensp;&ensp;Fecha compromiso&ensp;&ensp;&ensp;&ensp; 
                           <input type="text" placeholder="fecha del compromiso" value="" id="dtp1" class="datetimepicker" styles="vertical-align:top; display: inline-block;"/><br><br>
                                    </div>
                                        <div class="wrap-ing-form">
                    Apellido Paterno &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="apllP" placeholder="Apellido Paterno" class="ctz_data ctz_def"/><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Apellido Materno &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="applM" placeholder="Apellido Materno" class="ctz_data ctz_def"/><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Nombres &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="Nombres" placeholder="Nombres" class="ctz_data ctz_def" /><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Telefono &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="tel" placeholder="Teléfono" id="tel" maxlength="12" class="ctz_data ctz_def" /><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                                        <div id="sub-wrap">
                    Dirección &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="direccion" placeholder="Dirección" class="ctz_data ctz_def"/>
                     <i class="icon-map-marker icon-2x" id="Geo" ></i>
                    </div>
                    <div id="wrap-map" style="width: 100%; height:0px;">
                        <div id="map" style="width:100%; height: 0px"></div>
                    </div>
                                        </div>
                                        <div class="wrap-ing-form">
                    Correo Electronico &ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" value="" id="ctzmail" placeholder="Correo Electronico" class="ctz_data ctz_def"/><br><br>
                                        </div>
									</div>
								 <div class="control-group" >
										<label class="control-label">Origen del compromiso</label>
											<div class="controls" style="width: 100%;">

											<? while ($fila2 = mysqli_fetch_row($Query_cat)){ ?>

												<label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios"  value="<? printf($fila2[0])?>" >
													<? printf($fila2[1])?>
												</label> 


												<!--
												<label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios" id="optionsRadios2" value="Diputados - Senadores ">
													Diputados - Senadores 
												</label> 
												<label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios" id="optionsRadios3" value="Espontánea">
													Espontánea
												</label>
											    <label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios" id="optionsRadios3" value="Reclamos">
													Reclamos
												</label>
				     						    <label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios" id="optionsRadios3" value="Otros">
													Otros
												</label>
												-->
												<? } ?>
											</div>
											<div id="newOrgin">
											    <input type="text" id="newOr" value="" placeholder="Inserte un nuevo origen">
											    <button class="btn-info" onclick="createRadio(document.querySelector('#newOr').value)">Crear</button>
											</div>
										</div>
							 <h3><strong>Detalle del compromiso</strong></h3>
								<div class="stream-composer media">
									<a href="#" class="media-avatar medium pull-left">
										<img src="../<? echo $_SESSION['TxtFacility'] ?>/img/<? echo $_SESSION['TxtCode'] ?>.jpg">
									</a>
									<div class="media-body">
										<div class="row-fluid">
											<textarea id="descrip-audi" class="span12" style="height: 70px; resize: none;"></textarea>
										</div>
										<div class="clearfix">
											<button class="btn btn-small" rel="tooltip" data-placement="top" title="Marcar como urgente" >
												<i class="icon-warning-sign shaded" title="Marcar como urgente" id="mkur"></i>
											</button >
											<button class="btn btn-small" rel="tooltip" data-placement="top" data-original-title="Adjuntar Archivos">
												<i class="icon-paper-clip shaded" id="clip"></i>
											</button>
											<a href="#" class="btn btn-small" rel="tooltip" data-placement="top" data-original-title="Geolocalización">
												<i class="icon-map-marker shaded"></i>
											</a>
											<a class="btn btn-small" rel="tooltip" id="delegate" data-placement="top" data-original-title="Delegar directamente">
											   <i class="icon-retweet shaded"></i> 
											</a>
										</div>
										<div id="wrap-html5">
										     <div id="html5_uploader" style="width: 100%;">
                                       <!--
										<form action="../backend/upload_front.php" method="POST" enctype="multipart/form-data">
											<input type="file" id="filehand" name="upl" multiple />
											<input type="submit" value="subir" id="subir" style="display:none">
											<input type="hidden" value="" name="issue" id="issues">
											<input type="hidden" value="<? printf($_SESSION['TxtFacility']) ?>" name="fac">
									    </form>
									    -->
										     </div>
                                        </div>
										<div style="width: 100%;text-align: center;"><button class="btn-primary" id="SendRequest-free">Ingresar compromiso</button></div>
									</div>
								</div>

                             <div id="del-wrap">
                                <h3>Asignar Requerimientos</h3>
                                	<div style="width: 100%; position: relative; display: inline-block; vertical-align: top; ">
                                	<textarea id="requeriment" name="requeriment" placeholder="Describa el requerimiento" style="display: inline-block; vertical-align: top; width: 98%; "></textarea>
                                	<select class="selectpicker" data-live-search="true" data-size="5">
                                       <? 
 
                                       while( $deptos = mysqli_fetch_row($Query_depts)){ 

                                       	?>
                                      

                                            <? 
                                             
                                   $Query_personal = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM `USERS` WHERE (USR_FACILITY = " .  $_SESSION['TxtFacility'] . " AND USR_DEPT= '" . $deptos[0] ."' AND  USR_RANGE = 'admin'); ");
                                        while($per = mysqli_fetch_row($Query_personal)){ 
                                                
                             ?>
                                              <option value="<? printf($per[0]) ?>"><? printf($per[1])?> <? printf($per[2])?> - <small><? printf(strtoupper($deptos[0])) ?></small></option>
                             <?
                                            	}?>
                                          
                                        

                                          <?
                                          }
                                        ?>
								</select>
                                         <input type="text" placeholder="Fecha Máxima Respuesta" value="" id="dtp2" class="datetimepicker" style="vertical-align:top; display: inline-block; position: relative; float: right;"/>
                                	</div>
                                	<div style="width: 100%;text-align: center;"><button class="btn-primary" id="SendRequest">Ingresar compromiso delegado</button></div>
                                </div>
						</div><!--/.module-->
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

	<div class="footer">
		<div class="container">
			 

			<b class="copyright">&copy; Eque-e </b> Todos Los derechos reservados.
		</div>
	</div>
	<script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="../scripts/bootstrap-select.js"></script>
	<script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://maps.google.com.br/maps/api/js?v=3.10&sensor=false"></script>
	<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="../scripts/bootbox.min.js"></script>
    <script src="../scripts/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="../scripts/plupload.full.min.js"></script>  
    <script type="text/javascript" src="../scripts/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="../scripts/es.js"></script>
</body>
</html>
<script type="text/javascript">

//===================================
//******* VARIABLES GLOBALES *******
//===================================


var geocoder;
var map;
var GeoRef;
var argument = 0;
var fac = <? printf($_SESSION['TxtFacility']) ?>;
var rut_value = 0;
var UQ;
var exist = 0;


//===================================
//*********** EVENTS ****************
//===================================

$('.selectpicker').selectpicker({ dropupAuto : false });

$(document).on('ready', function(){

    $("#tel").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

$("#del-wrap  div , #del-wrap input, #del-wrap h3 ").addClass('hidden');

$(".ctz_def").attr("disabled", true);

$("#html5_uploader_browse").addClass('plupload_disabled');
$("#clip").addClass('disabled');

 //google maps...

 IntializeGMaps();
 uploaderInt();


uploader.bind('BeforeUpload', function (up, file) {
    up.settings.multipart_params = {"fac_id": fac , "rut" : $("#RUT").val()};
});
	$('#dtp1').datetimepicker({
	step:5,
	lang:'es',
	format:'d/m/Y',
	minDate: '-02/01/1970',
	maxDate: '+02/01/1970',
	timepicker: false
});

    $('#dtp2').datetimepicker({
	step:5,
	lang:'es',
	format:'d/m/Y',
	minDate: '-1970/01/02',
	timepicker: false,
	onShow :function(ctn){
		this.setOptions({
            format: 'd/m/Y'
		})
	}
});
	
	});

var uploaderInt = function(){

uploader =  $("#html5_uploader").pluploadQueue({
		runtimes : 'html5',
		url : '../backend/upload_front2.php?'  ,
		chunk_size : '2mb',
		unique_names : true,
  filters : {
			max_file_size : '2mb',
			mime_types: [
				{title : "General files", extensions : "jpg,gif,png,pdf,xls,xlsx,docx,doc,txt,ppt,pptx,mp3"},
				{title : "Zip files", extensions : "zip" }
			]
		},
  preinit : {
            Init: function(up, info) {
                console.log('[Init]', 'Info:', info, 'Features:', up.features);
            },
 
            UploadFile: function(up, file) {

                console.log('[UploadFile]', file);
                   console.info("file" + file.name);

                up.setOption("url", '../backend/upload_front2.php?fac_id=' + fac + "&rut=" + rut_value + "&full_name=" + file.name);
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
                
                         up.settings.multipart_params = {
                                   filename: file.name
                               };
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
                    console.log('File:', file);
                });

               

            },
  
            FileUploaded: function(up, file, info) {
                // Called when file has finished uploading
                console.log('[FileUploaded] File:', file, "Info:", info);
                fileIcons(file.name);

            },
  
            ChunkUploaded: function(up, file, info) {
                // Called when file chunk has finished uploading
                console.log('[ChunkUploaded] File:', file, "Info:", info);
            },
 
            UploadComplete: function(up, files) {
                // Called when all files are either uploaded or failed
                console.log('[UploadComplete]');
                $("#wrap-html5").fadeOut(400); 
                up.destroy();
                uploaderInt();
                $("#SendRequest-free").attr("disabled", false);
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




$(window).scroll(function(){

$(".dropdown-menu").removeClass("show");
     if(!$(".dropdown-menu").hasClass("hide")){
     	  $(".dropdown-menu").addClass("hide");
     }

})



$("#dtp1").on('click', function(){

  if(!$(this).data("val")){
  	$("#dtp1").datetimepicker('hide');
     	var currentDate = new Date();  
	        $("#dtp1").val(('0' + currentDate.getDate()).slice(-2) + "/" + ('0' + (currentDate.getMonth()+1)).slice(-2)  + "/" + currentDate.getFullYear());
	             $(this).data("val", 1);
  } else {

$("#dtp1").datetimepicker('show');
   
  }   
})


$('#RUT').on('change keydown paste input keypress' , function(){

	var To = Valida_Rut(document.getElementById('RUT'));

    if (To === false){

    	$('#RUT').css('color', 'rgba(222,6,1, 1)' );
    	$('.ctz_def').attr('disabled', true);
    	$("#html5_uploader_browse").addClass('plupload_disabled');
        $("#clip").addClass('disabled');

    } else {

    	$('#RUT').css('color', 'rgba(81,198,60, 1)');
        $('.ctz_def').attr('disabled', false);
        $("#html5_uploader_browse").removeClass('plupload_disabled');
        $("#clip").removeClass('disabled');
    }

});




$("#ctzmail").on('change keydown paste input keypress', function (){

	if(IsEmail($(this).val())) {

      $(this).css('color', 'rgba(81,198,60, 1)');

	} else {

		$(this).css('color', 'rgba(222,6,1, 1)' );
	}
});




$('#Geo').on('click', function(){

$("#map").trigger('resize');

 if (!$(this).data("val") || $(this).data("val") === 0){
	setTimeout(function(){
		$("#wrap-map").css({ height: "500px"});
	}, 1);

     $("#map").css({ height: "500px"});
     $(this).data("val", 1);

     getLatlgnBounds();
   
} else {

   	setTimeout(function(){
		$("#wrap-map").css({ height: "0px"});
	}, 1);
     $("#map").css({ height: "0px"});
     $(this).data("val", 0);
}



})

$("#urgent").on('click', function(){
   $(this).css("color", "#DD6F00");
})

$("#sendEmail").on('click', function(){
	   $(this).css("color", "#3E3EFF");
});


$("#SendRequest").on('click', function(){

$(this).data("val", 1);

var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value)
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls  input[type=radio]:checked").val();

setRequest(narray[0], narray[6] , narray[8], $("#latlng").val(), narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val(), narray[7], "" );

});


$("#delegate").on('click', function(){

	if($(this).data("val") === 1){

       $("#del-wrap  div , #del-wrap input, #del-wrap h3").removeClass('show');
       $("#del-wrap  div , #del-wrap input, #del-wrap h3").addClass('hidden');
       $("#del-wrap").css({height: "0"});
       $("#SendRequest").attr("disabled", true);
       $("#SendRequest-free").removeAttr("disabled");
       $(this).data("val", 0);

	} else {
        
     $("#del-wrap").css({height: "220px"});
     $("#del-wrap  div , #del-wrap input, #del-wrap h3").removeClass('hidden');
     $("#del-wrap  div , #del-wrap input, #del-wrap h3").addClass('show');
     $("#SendRequest-free").attr("disabled", true);
     $("#SendRequest").removeAttr("disabled")
     $(this).data("val", 1);
	}

});



$("#SendRequest-free").on('click', function(){
  
  if(argument == 1){
	bootbox.confirm("Se detectaron cambios en los datos del ciudadano, Desea actualizar la información", function (response){
        if(!response){
            argument = 0;
   bootbox.confirm("Desea ingresar el compromiso sin delegar?", function (outcome){

	if(outcome) {
var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value);
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls input[type=radio]:checked").val();
setRequest(narray[0], narray[6] , narray[8], $("#latlng").val(), narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val(), narray[7], "");
}	
});

} else {

 bootbox.confirm("Desea ingresar el compromiso sin delegar?", function (outcome){

	if(outcome) {
var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value)
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls input[type=radio]:checked").val();

setRequest(narray[0], narray[6] , narray[8], $("#latlng").val(), narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val(), narray[7], "");

	} else  {
		argument = 1;
	}
	
});

}
})
} else {

 bootbox.confirm("Desea ingresar el compromiso sin delegar?", function (outcome){

	if(outcome) {

var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value)
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls input[type=radio]:checked").val();

setRequest(narray[0], narray[6] , narray[8], $("#latlng").val(), narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val(), narray[7], "" );

	}
	
});
}
})

// events
//===============





// FUNCTIONS
function IntializeGMaps(){

geocoder = new google.maps.Geocoder();

var LatLnginit = new google.maps.LatLng(-33.485362, -70.639343);

    var options = {
        zoom: 12,
        zoomControl: true,
        zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE
                            },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };

	    map = new google.maps.Map(document.getElementById("map"), options);
	    map.setCenter(LatLnginit);

}

function getLatlgnBounds(){

 var address = $("#direccion").val();

    geocoder.geocode( { 'address': address + ' - Chile'}, function(results, status) {

      if (status == google.maps.GeocoderStatus.OK) {

        map.setCenter(results[0].geometry.location);

        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location,
            icon : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=1|41C74A|000000',
            draggable: true
        });

        $("#latlng").val(marker.position.lat() + "," + marker.position.lng());

        google.maps.event.addListener(marker , 'dragend', function (){
        	$("#latlng").val(marker.position.lat() + "," + marker.position.lng());
          bootbox.alert("Reubicación grabada", function() {
                console.log("ok");

            });
        })

      } else {

      	     bootbox.alert("La Geocodificación no se pudo realizar por el siguiente motivo: " + status, function() {
                console.log("Alert Callback");
            });
       
      }
    });

}




function setRequest(rut, direccion, audiencia, GeoLoc, Nombre, appm, appp, tipo, tel, deadD, mail, subject){
var fecha = new Date();
var fecha_or = fecha.getFullYear() + "-" + ('0' + (fecha.getMonth()+1)).slice(-2) + "-" + ('0' + fecha.getDate()).slice(-2)  + " " + ('0' + fecha.getHours()).slice(-2) + ":" + ('0' + fecha.getMinutes()).slice(-2) + ":" + ('0'+ fecha.getSeconds()).slice(-2);

if (GeoLoc == "" || GeoLoc == undefined){
	GeoLoc = 0;
}

var pase = missingField();


if (pase != false){
	
pre_rut = rut.replace(/\./gi, "");
rut = pre_rut.replace('-', "");

fecha_limit = Math.round((new Date(dateTrans(deadD)).getTime() - new Date(fecha_or).getTime()) / 86400000);
console.info("../backend/issGn.php?rut=" + rut + 
 	"&dn=" + direccion.replace(/\,/gi," " ) + 
 	"&iss=" + audiencia + 
 	"&Geoloc=" + GeoLoc + 
 	"&Nombre=" + Nombre +
 	"&appm=" + appm +
 	"&appp=" + appp +
 	"&tipo=" + tipo + 
 	"&tel=" + tel +
 	"&date=" + fecha_or + 
 	"&fecha_limit=" + deadD  + " 10:00:00" +
 	"&days=" + fecha_limit +
 	"&email=" + mail +
 	"&subject=null" + 
 	"&fac=" + fac +
 	"&argument=" + argument);

 $.ajax({
 	type: "POST",
 	url: "../backend/issGn.php?rut=" + rut + 
 	"&dn=" + direccion.replace(/\,/gi," " ) + 
 	"&iss=" + audiencia + 
 	"&Geoloc=" + GeoLoc + 
 	"&Nombre=" + Nombre +
 	"&appm=" + appm +
 	"&appp=" + appp +
 	"&tipo=" + tipo + 
 	"&tel=" + tel +
 	"&date=" + fecha_or + 
 	"&fecha_limit=" + deadD  + " 10:00:00" +
 	"&days=" + fecha_limit +
 	"&email=" + mail +
 	"&subject=null"+
 	"&fac=" + fac +
 	"&argument=" + argument
 	 ,
 	success : function (data){

$(".clearfix").find("i.fa").remove();

console.info(data);

if($("#SendRequest").data("val") == 1 ){
      
var name = $('.selectpicker').val();
var msg = $("#requeriment").val();
var dataF = $("#dtp2").val() + " 10:00:00";

delegateRequirement(name, 1, msg, dataF, fecha_or, data);

$("#SendRequest").data("val", 0);


        } else {

        	bootbox.alert("Compromiso ingresado con exito", function(){
 			 $("input[type=text], input[type=tel]").val('');
 			 $("textarea").val('');

 			 $("input[type=text], input[type=tel]").attr('disable', true);
 			 $("textarea").attr('disable', true);

 			 exist = 0;
 			 argument = 0;
 			 $("#wrap-html5").fadeOut(400);

 			 var addvalue = parseInt($("#counter-task").html());
             $("#counter-task").html(addvalue + 1);

 		});
 		

        }

 	}
 })

}


}


function createRadio(inputVal){

parent = document.querySelector(".controls");
radio = document.createElement('input');

radio.type = 'radio';
radio.name = 'optionsRadios';

label = document.createElement('label');
label.className = "radio inline";
label.style.marginRight = '2em';
label.style.marginLeft = '2em';
label.innerHTML = inputVal;

label.appendChild(radio);
parent.appendChild(label);

document.querySelector("#newOr").value="";

$.ajax({
	type: "POST",
	url: "../backend/catadd.php?des=" + inputVal + "&fac=" + fac,
	success : function (data){
       
		if (parseInt(data) == 0){

             console.info(data);

		} else {

              radio.value = inputVal;

		}
		
	}
});


}


function IsEmail(email){
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function Valida_Rut( Objeto )

{


	var tmpstr = "";
	var intlargo = Objeto.value;
	if (intlargo.length> 0)
	{
		crut = Objeto.value
		largo = crut.length;
		if ( largo <2 )
		{
			console.info('rut invalido');
			return false;
		}
		for ( i=0; i <crut.length ; i++ )
		if ( crut.charAt(i) != ' ' && crut.charAt(i) != '.' && crut.charAt(i) != '-' )
		{
			tmpstr = tmpstr + crut.charAt(i);
		}
		rut = tmpstr;
		crut=tmpstr;
		largo = crut.length;
	
		if ( largo> 2 )
			rut = crut.substring(0, largo - 1);
		else
			rut = crut.charAt(0);
	
		dv = crut.charAt(largo-1);
	
		if ( rut == null || dv == null )
		return 0;
	
		var dvr = '0';
		suma = 0;
		mul  = 2;
	
		for (i= rut.length-1 ; i>= 0; i--)
		{
			suma = suma + rut.charAt(i) * mul;
			if (mul == 7)
				mul = 2;
			else
				mul++;
		}
	
		res = suma % 11;
		if (res==1)
			dvr = 'k';
		else if (res==0)
			dvr = '0';
		else
		{
			dvi = 11-res;
			dvr = dvi + "";
		}
	
		if ( dvr != dv.toLowerCase() )
		{
			console.info('El Rut Ingreso es Invalido');
		
			return false;
		}
		
		
		var pre_rut = Objeto.value.replace(/\./g, "");
         pre_rut = pre_rut.replace('-', "");
         var rut = pre_rut.substring(0, pre_rut.length-1);
          
        if(exist !== 1){
            checkIfExist(rut);
        }
		return true;
	}
}


function dateTrans(string){

	data = string.split("/");
    return data[2] + "-" + data[1] + "-" + data[0];
}

function getParamNames(func) {
  var fnStr = func.toString().replace(STRIP_COMMENTS, '')
  var result = fnStr.slice(fnStr.indexOf('(')+1, fnStr.indexOf(')')).match(ARGUMENT_NAMES)
  if(result === null)
     result = []
  return result
}



function checkIfExist(rut){

console.info('el rut es:' + rut);

$.ajax({
	type: "POST",
	url: "../backend/Getctz.php?rut=" + rut + "&fac=" + fac,
	success : function (data){
	
       if(parseInt(data) === 0){

       	console.info('no existe');
       var rut_1 = $("#RUT").val();
var rut_2= rut_1.replace(/\./gi, "");
var rut_3= rut_2.replace('-', "");
var rut_4= rut_3.substring(0,rut_3.length -1);
rut_value = rut_4;

       } else {


var rut_1 = $("#RUT").val();
var rut_2= rut_1.replace(/\./gi, "");
var rut_3= rut_2.replace('-', "");
var rut_4= rut_3.substring(0,rut_3.length -1);
rut_value = rut_4;

recall = data.split("|");


for (i=0; i < recall.length; i++){
	console.info("posicion " + i + " tienes : "+ recall[i])
}

$("#apllP").val(recall[2]);
$("#applM").val(recall[1]);
$("#Nombres").val(recall[0]);
$("#direccion").val(recall[3]);
$("#tel").val(recall[8]);
$("#Geo").val(recall[4]);
$("#ctzmail").val(recall[9]);




exist = 1;

       }
	}
})
}

function delegateRequirement(usr_id, imp, msg, dataF, dataS, iss_id){

$.ajax({ type: "POST",
	     url: "../backend/delegate-new.php?fac=" +fac +
	           "&usr_id=" + usr_id + 
	           "&imp=" + imp +
	           "&msg=" + msg+
	           "&dataF=" + dataF +
	           "&dataS=" + dataS +
	           "&iss_id=" + iss_id,
	           success : function (data){
               console.info(data);
	           bootbox.alert("El compromiso fue ingresado y delegado exitosamente", function(){

	            $("input[type=tel] , input[type=text]").val('');
 			    $("textarea").val('');
 			    exist = 0;
 			    argument = 0;
 			    $("#wrap-html5").fadeOut(400);

	           })

	           }
})

}

function missingField(){

var matrix = document.querySelectorAll('#intext input');
//primera fase : todos los inputs.

var empty = [];

for(i=0 ; i < matrix.length ; i++){
	if(matrix[i].value == '' ){
		 if($("#intext input").eq(i).attr('placeholder') == "Correo Electronico"){
		 	continue;
		 } else {
		 	console.info($("#intext input").eq(i).attr('placeholder'))
		 	empty[empty.length] = $("#intext input").eq(i).attr('placeholder');
		 }
	}
}

if(empty.length == 0){


} else {

	bootbox.alert("Faltan los siguientes Campos:"  + empty.join('&ensp;/&ensp;'));
	return false;
}

if($("input[type=radio]:checked").length == 0){
	bootbox.alert("Seleccione origen del requerimiento");
	return false;
}

//segunda fase
if($("textarea").val() === "") {
  bootbox.alert("Por Favor ingrese la descripcion del compromiso");
  return false;
}

// 3era fase ... checkear validez de los datos ingresados
/*
if(!IsEmail($("#ctzmail").val())){
    bootbox.alert("E-mail ingresado no es valido");
    return false;
}
*/
if(!Valida_Rut(document.getElementById('RUT'))){
    bootbox.alert("el R.U.T ingresado no es valido");
    return false;
}

//4ta fase checkear datos 

if($("#delegate").data("val") == 1){
	if($("#requeriment").val() == ""){
		bootbox.alert("Por favor ingrese la descripción del requerimiento");
		return false;
	}
}

if($("#delegate").data("val") == 1){
	if($("#dtp2").val() == ""){
		bootbox.alert("Por favor ingrese fecha de respuesta");
		return false;
	}
}



return true;

}

//special event

$("input.ctz_data").on('change keypress keydown input paste', function (){
	if(exist == 1 && $(this).attr('id') !== "RUT"){
		argument = 1;
	}
});

$("#mkur").on('click', function(){
	var st = $(this).data("val");
	if(st != 0 || st != ""){
	   $(this).css({ color : "orange"});
	   $(this).data("val", 0)
     } else {
       $(this).css({ color : "gray"});
       $(this).data("val", 1);
     }
});


$("#clip").on('click', function(){
        $("#wrap-html5").fadeToggle(400); 
});

$("#cleanup").on('click', function(){
	$("input[type=text]").val('');
	$("input[type=tel]").val('');
	$("textarea").val('');
	$("#wrap-html5").fadeOut(400);
	exist = 0;
	argument = 0;
	$(".clearfix").find("i.fa").remove();
});



$('#Geo').on('click', function () {
  google.maps.event.trigger(map, 'resize');
})

$('#map').on('click', function () {
  google.maps.event.trigger(map, 'resize');
});

function fileIcons(file){

   var filter = "";
   var extension = file.substring(file.length -3 , file.length);

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
            cor = "#B8005C";
        break; 

    }

filter = "<i title='" + file + "' style='color:" + cor + ";float: right; margin:0 .3em' class='fa fa-file-" + setClass + " fa-2x'></i>";

$(".clearfix").append(filter);

filter = "";


}



</script>
<?

} else {
	echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>
