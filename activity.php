
<?php

session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'front-user'){

$datos = mysqli_connect('mysql.nixiweb.com', "u315988979_eque", "MoNoCeRoS", "u315988979_eque");
$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS

$Query_task = mysqli_query($datos, "SELECT * FROM ISS WHERE FAC_CODE = " . $_SESSION['TxtCode'] );

/*
ISS_ID
ISS_DATE_ING
ISS_SUBJECT
ISS_DESCRIP
ISS_CHARGE_USR
ISS_DEADLINE
ISS_DAYS
ISS_STATE
ISS_FINISH_DATE
ISS_DELAY_DAYS
ISS_SUBTASKS_CANT
*/



?>


<!DOCTYPE html>
<html lang="es">

<head>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Eque-e</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="scripts/jquery.datetimepicker.css">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link type="text/css" href="css/style.css">
	
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

#newOrgin{
width: 100%;
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
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Drops <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li class="divider"></li>
								<li class="nav-header">Nav header</li>
								<li><a href="#">Separated link</a></li>
								<li><a href="#">One more separated link</a></li>
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
								<li><a href="backend/close.php">Logout</a></li>
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
							<li class="active">
								<a href="index.php">
									<i class="menu-icon icon-dashboard"></i>
									Vista Principal
								</a>
							</li>
							<li>
								<a href="activity.php">
									<i class="menu-icon icon-bullhorn"></i>
									Ingreso de audiencias
								</a>
							</li>
							<li>
								<a href="other-user-profile.html">
									<i class="menu-icon icon-inbox"></i>
									Perfil de usuario
									<b class="label green pull-right">11</b>
								</a>
							</li>
							<li>
								<a href="task.php">
									<i class="menu-icon icon-tasks"></i>
									Control de Cumplimientos
									<b class="label orange pull-right">19</b>
								</a>
							</li>
						</ul><!--/.widget-nav-->


						<ul class="widget widget-menu unstyled">
							<li>
								<a class="collapsed" data-toggle="collapse" href="#togglePages">
									<i class="menu-icon icon-cog"></i>
									<i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right"></i>
									Vistas
								</a>
								<ul id="togglePages" class="collapse unstyled">
									<li>
										<a href="other-login.html">
											<i class="icon-inbox"></i>
											Progresos
										</a>
									</li>
									<li>
										<a href="other-user-profile.html">
											<i class="icon-inbox"></i>
											Estadisticas
										</a>
									</li>
									<li>
										<a href="other-user-listing.html">
											<i class="icon-inbox"></i>
											No Conformidades
										</a>
									</li>
								</ul>
							</li>
							
							<li>
								<a href="backend/close.php">
									<i class="menu-icon icon-signout"></i>
									Logout
								</a>
							</li>
						</ul>

						

					
					</div><!--/.sidebar-->
				</div><!--/.span3-->


				<div class="span9">
					<div class="content">

						<div class="module">
							<div class="module-head">
								<h3>Ingreso de Audiencias</h3>
							</div>
							<div class="module-body">
									<div class="media stream new-update" align="center">
									<div class="wrap-ing-form" style="vertical-align: top; display: inline-block; width:100%;">
					R.U.T &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="RUT" placeholder="R.U.T" style="width: 30% !important;" maxlength="12"/>
					          &ensp;&ensp;&ensp;&ensp;Fecha Audiencia&ensp;&ensp;&ensp;&ensp; 
                           <input type="text" placeholder="fecha de audiencia" value="" id="dtp1" class="datetimepicker" styles="vertical-align:top; display: inline-block;"/><br><br>
                                    </div>
                                        <div class="wrap-ing-form">
                    Apellido Paterno &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="apllP" placeholder="Apellido Paterno"/><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Apellido Materno &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="applM" placeholder="Apellido Materno" /><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Nombres &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="Nombres" placeholder="Nombres" /><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                    Telefono &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="tel" placeholder="Teléfono"  maxlength="12" /><br><br>
                                        </div>
                                        <div class="wrap-ing-form">
                                        <div id="sub-wrap">
                    Dirección &ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" id="direccion" placeholder="Dirección"/>
                     <i class="icon-map-marker icon-2x" id="Geo" ></i>
                    </div>
                    <div id="wrap-map" style="width: 100%; height:0px;">
                        <div id="map" style="width:100%; height: 0px"></div>
                    </div>
                                        </div>
                                        <div class="wrap-ing-form">
                    Correo Electronico &ensp;&ensp;&ensp;&ensp;&ensp;<input type="text" value="" placeholder="Correo Electronico"/><br><br>
                                        </div>
									</div>
								 <div class="control-group" >
										<label class="control-label">Origen de la Audiencia</label>
											<div class="controls" style="width: 100%;">
												<label class="radio inline"  style="margin:0 2em;">
													<input type="radio" name="optionsRadios" id="optionsRadios1" value="Institucional" checked="">
													Institucional
												</label> 
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
											</div>
											<div id="newOrgin">
											    <input type="text" id="newOr" value="" placeholder="Inserte un nuevo origen">
											    <button class="btn-info" onclick="createRadio(document.querySelector('#newOr').value)">Crear</button>
											</div>
										</div>
							 <h3><strong>Detalle de audiencia</strong></h3>
								<div class="stream-composer media">
									<a href="#" class="media-avatar medium pull-left">
										<img src="images/user.png">
									</a>
									<div class="media-body">
									  <input type="text" placeholder="asunto" id="subject" />
										<div class="row-fluid">
											<textarea id="descrip-audi" class="span12" style="height: 70px; resize: none;"></textarea>
										</div>
										<div class="clearfix">
											<a href="#" class="btn btn-small" rel="tooltip" data-placement="top" data-original-title="Marcar como urgente">
												<i class="icon-warning-sign shaded" title="Marcar como urgente"></i>
											</a>
											<a href="#" class="btn btn-small" rel="tooltip" data-placement="top" data-original-title="Adjuntar Archivos">
												<i class="icon-paper-clip shaded"></i>
											</a>
											<a href="#" class="btn btn-small" rel="tooltip" data-placement="top" data-original-title="Geolocalización">
												<i class="icon-map-marker shaded"></i>
											</a>
											<a class="btn btn-small" rel="tooltip" id="delegate" data-placement="top" data-original-title="Delegar directamente">
											   <i class="icon-retweet shaded"></i> 
											</a>
										</div>
										<div style="width: 100%;text-align: center;"><button class="btn-primary" id="SendRequest-free">Ingresar Audiencia</button></div>
									</div>
								</div>

                             <div id="del-wrap">
                                <h3>Asignar Requerimientos</h3>
                                	<div style="width: 100%; position: relative; display: inline-block; vertical-align: top; ">
                                	<textarea id="requeriment" name="requeriment" placeholder="Describa el requerimiento" style="display: inline-block; vertical-align: top; width: 98%; "></textarea>

                                		<input type="text" name="currency" class="biginput" id="autocomplete" autocomplete="off" style="max-width: 50%;position: relative; float: left;" placeholder="delegados"/> 
                     <i class="icon-warning-sign icon-2x" id="urgent" style="display: inline-block; vertical-align: top; margin: 5px; cursor: pointer" ></i>
                     <i class="icon-envelope-alt icon-2x" id="sendEmail" style="display: inline-block; vertical-align: top; margin: 5px; cursor: pointer" ></i>
                                         <input type="text" placeholder="Fecha Máxima Respuesta" value="" id="dtp2" class="datetimepicker" style="vertical-align:top; display: inline-block; position: relative; float: right;"/>
                                	</div>
                                	<div style="width: 100%;text-align: center;"><button class="btn-primary" id="SendRequest">Ingresar audiencia delegada</button></div>
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

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="http://maps.google.com.br/maps/api/js?v=3.10&sensor=false"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="scripts/bootbox.min.js"></script>
    <script src="scripts/jquery.datetimepicker.js"></script>
    <script src="scripts/jquery.autocomplete.min.js"></script>

</body>
</html>
 
<script type="text/javascript">

//===================================
//******* VARIABLES GLOBALES *******
//===================================


var geocoder;
var map;


//===================================
//*********** EVENTS ****************
//===================================



$(document).on('ready', function(){

$("#del-wrap  div , #del-wrap input, #del-wrap h3 ").addClass('hidden');

  var currencies = [
    { value: 'Pedro Fernandez', data: 'Finanzas' },
    { value: 'Alejandro Curaqueo', data: 'Contabilidad' },
    { value: 'Rodrigo Peña', data: 'Adquisiciones' },
    { value: 'Pedro Cortez', data: 'Ventas'},
    { value: 'Francisco Papal', data: 'Gerencia comercial' },
    { value: 'Liliana Avogadro', data: 'Gestión de Personas' },
    { value: 'Jefferson Pimentel', data: 'Gerencia general' },
    { value: 'Gabriella Santorielli', data: 'Mesa de Dinero' },
    { value: 'Laura Costa', data: 'Medio ambiente' },
    { value: 'Anita Acosta', data: 'Cobranzas' },
    { value: 'Pablo Suarez', data: 'Planificacipon Urbana' },
    { value: 'Leandro Martinez', data: 'Asistencia Tecnica' },
    { value: 'Macarena Arraño', data: 'Relaciones publicas' },
  ];
  

  // setup autocomplete function pulling from currencies[] array
  $('#autocomplete').autocomplete({
    lookup: currencies,
    onSelect: function (suggestion) {
      var thehtml = suggestion.value + ' - ' + suggestion.data;
      $(this).val(thehtml);
    }
  });
  

  // date time picker.

     //google maps...
 IntializeGMaps();


	$('#dtp1').datetimepicker({
	step:5,
	lang:'es',
	format:'d/m/Y',
	timepicker: false
});

    $('#dtp2').datetimepicker({
	step:5,
	lang:'es',
	format:'d/m/Y',
	timepicker: false
});
	});



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
    } else {
    	$('#RUT').css('color', 'rgba(81,198,60, 1)');
    }

})

$('#Geo').on('click', function(){

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

var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value)
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls  input[type=radio]:checked").val();

setRequest(narray[0], narray[6] , narray[8], 0, narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val());

});

$("#delegate").on('click', function(){

	if($(this).data("val") === 1){

       $("#del-wrap  div , #del-wrap input, #del-wrap h3").removeClass('show');
       $("#del-wrap  div , #del-wrap input, #del-wrap h3").addClass('hidden');
       $("#del-wrap").css({height: "0"});

       $(this).data("val", 0);

	} else {
        
     $("#del-wrap").css({height: "220px"});
     $("#del-wrap  div , #del-wrap input, #del-wrap h3").removeClass('hidden');
     $("#del-wrap  div , #del-wrap input, #del-wrap h3").addClass('show');
     $(this).data("val", 1);
	}

});



$("#SendRequest-free").on('click', function(){
   
    var bool = confirm("Deseas Guardad esta Audiencia sin delegar?");

    if(bool === true){
var narray = [];
var cont = document.querySelectorAll(".wrap-ing-form input");

for(i=0;i < cont.length ; i++){
   narray[i] = cont[i].value;
   console.info(cont[i].value)
}

narray[narray.length] = document.querySelector("#descrip-audi").value;
narray[narray.length] = $(".controls  input[type=radio]:checked").val();

setRequest(narray[0], narray[6] , narray[8], 0, narray[4], narray[2], narray[3], narray[9] , narray[5], $("#dtp2").val());

    } 

})

// events
//===============





// FUNCTIONS
function IntializeGMaps(){

geocoder = new google.maps.Geocoder();

var LatLnginit = new google.maps.LatLng(-33.485362, -70.639343);

    var options = {
        zoom: 14,
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

    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);

        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location,
            icon : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=1|41C74A|000000',
            draggable: true
        });

        google.maps.event.addListener(marker , 'dragend', function(){
          bootbox.alert("Reubicación grabada", function() {
                console.log("Alert Callback");
            });
        })

      } else {

      	     bootbox.alert("La Geocodificación no se pudo realizar por el siguiente motivo: " + status, function() {
                console.log("Alert Callback");
            });
       
      }
    });

}




function setRequest(rut, direccion, audiencia, GeoLoc, Nombre, appm, appp, tipo, tel, deadD){
var fecha = new Date();
var fecha_or = fecha.getFullYear() + "-" + ('0' + (fecha.getMonth()+1)).slice(-2) + "-" + ('0' + fecha.getDate()).slice(-2)  + " " + fecha.getHours() + ":" + fecha.getMinutes() + ":" + ('0'+ fecha.getSeconds()).slice(-2);

GeoLoc = typeof GeoLoc != 'undefined' ? GeoLoc : 0; 

   var args = Array.prototype.slice.call(arguments, 1);

    for(i=0; i < args.length; i++){
    	if(args[i] === ''){
    		alert('campos por llenar');
    		return false;
    	}
    }

pre_rut = rut.replace(/\./gi, "");
rut = pre_rut.replace('-', "");

fecha_limit = (new Date(dateTrans(deadD)).getTime() - new Date(fecha_or).getTime()) / 86400000;
console.info(fecha_limit);
console.info(Math.round(fecha_limit));

 $.ajax({
 	type: "POST",
 	url: "backend/issGn.php?rut=" + rut + 
 	"&dn=" + direccion + 
 	"&iss=" + audiencia + 
 	"&Geoloc=" + GeoLoc + 
 	"&Nombre=" + Nombre +
 	"&appm=" + appm +
 	"&appp=" + appp +
 	"&tipo=" + tipo + 
 	"&tel=" + tel +
 	"&date=" + fecha_or + 
 	"&fecha_limit=" + deadD +
 	"&days=" + fecha_limit
 	 ,
 	success : function (data){
 		alert("Audiencia ingresada con exito");
 		$(".wrap-ing-form input").val('');
 	}
 })




}


function createRadio(inputVal){


parent = document.querySelector(".controls");

radio = document.createElement('input');
radio.value = inputVal;
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

}


function IsEmail(email){
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function Valida_Rut( Objeto )

{
	var tmpstr = "";
	var intlargo = Objeto.value
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
		
		console.info('es correcto');
		return true;
	}
}


function dateTrans(string){

	data = string.split("/");
    return data[2] + "-" + data[1] + "-" + data[0];
}




</script>
<?

} else {
	echo "<script language='javascript'>window.location='login.php'</script>";
}

?>