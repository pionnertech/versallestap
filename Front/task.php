<?php session_start();


if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange']){


$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS
$Query_task = mysqli_query($datos, "SELECT A.ISS_ID, SUBSTRING(A.ISS_DATE_ING, 1, 10), A.ISS_DESCRIP, B.EST_DESCRIPT, B.EST_COLOR, A.ISS_PROGRESS FROM ISSUES A INNER JOIN EST B ON(A.ISS_STATE = B.EST_CODE) WHERE A.ISS_FAC_CODE = " .  $_SESSION["TxtFacility"] . ";" );

$Query_depts = mysqli_query($datos, "SELECT DISTINCT USR_DEPT FROM USERS WHERE USR_FACILITY = " .  $_SESSION['TxtFacility'] . " GROUP BY USR_DEPT;");


?>

<!DOCTYPE html>
<html lang="es">

<head>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edmin</title>
	<link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="../css/theme.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../scripts/jquery.datetimepicker.css">
	<link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="css/selectize.bootstrap3.css" />
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
									ingreso de Audiencias
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
									More Pages
								</a>
								<ul id="togglePages" class="collapse unstyled">
									<li>
										<a href="other-login.html">
											<i class="icon-inbox"></i>
											Login
										</a>
									</li>
									<li>
										<a href="other-user-profile.html">
											<i class="icon-inbox"></i>
											Profile
										</a>
									</li>
									<li>
										<a href="other-user-listing.html">
											<i class="icon-inbox"></i>
											All Users
										</a>
									</li>
								</ul>
							</li>
							
							<li>
								<a href="#">
									<i class="menu-icon icon-signout"></i>
									Logout
								</a>
							</li>
						</ul>
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
										<button class="btn">Todos</button>
										<button class="btn dropdown-toggle" data-toggle="dropdown">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
										    <li class="switcher" id="Pe"><a href="#" >Pendientes</a></li>
											<li class="switcher" id="Ec"><a href="#" >En Curso</a></li>
											<li class="switcher" id="Hc"><a href="#" >Hechos</a></li>
											<li class="switcher" id="Pv"><a href="#" >Por Vencer</a></li>
											<li class="switcher" id="At"><a href="#" >Atrasados</a></li>
										</ul>
									</div>
								</div>
								<div class="pull-right">
									<a href="#" class="btn btn-primary">Crear Requerimiento</a>
								</div>
							</div>
							<div class="module-body table">								

								<table class="table table-message">
									<tbody>
										<tr class="heading">
											<td class="cell-icon"></td>
											<td class="cell-title">Requerimiento</td>
											<td class="cell-status hidden-phone hidden-tablet">Status</td>
											<td class="cell-time align-right">Fecha</td>
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
											<td class="cell-icon" style="margin-right: 1em;"><? printf($fila1[0]) ?></td>
											<td class="cell-title"><div><? printf($fila1[2]) ?></div></td>
											<td class="cell-status hidden-phone hidden-tablet"><b class="due done" style="background-color:<? printf($fila1[4])?>"><? printf($fila1[3]) ?></b></td>
											<td class="cell-time align-right"><? printf($fila1[1]) ?></td>
											<input type="hidden" value="" >		
										</tr>
                                        <? if($class == "Pe"){ ?>


										<tr class="requirement">
											<td colspan="4" >
												<textarea class="description" placeholder="describa el requerimiento"></textarea>
												<label for="subject">Delegados</label>
												<select id="delegates<? printf($i)?> ">
                                       <? 

                                       while( $deptos = mysqli_fetch_row($Query_depts)){ 
                                       	?>
                                      <optgroup label="<? printf(strtoupper($deptos[0])) ?>">
                                            <? 
        
                                   $Query_personal = mysqli_query($datos, "SELECT USR_ID, USR_NAME, USR_SURNAME FROM `USERS` WHERE (USR_FACILITY = " .  $_SESSION['TxtFacility'] . " AND USR_DEPT= '" . $deptos[0] ."'); ");
                                        while($per = mysqli_fetch_row($Query_personal)){ 
                                                
                             ?>
                                              <option value="<? printf($per[0]) ?>"><? printf($per[1])?> <? printf($per[2])?></option>
                             <?
                                            	}?>
                                          
                                          </optgroup>

                                          <?
                                          }
                                        ?>

												</select>
												<i class="fa fa-warning"></i><i class="fa fa-envelope"></i>
                                                <input type="text" placeholder="Fecha Termino" class="datetimepicker" styles="vertical-align:top; display: inline-block;"/><br><br>
												<button class="btn-info enviar">Delegar task</button>
											</td>
										</tr>   

    <? } else { ?>


                                        <tr class="display-progress">
                                            <td colspan="5">
                                            <p>
                                                <strong>Grado de progreso</strong><span class="pull-right small muted"><? printf($fila1[5]) ?>%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: <? printf($fila1[5]) ?>%;"></div>
                                            </div>
                                            <div class="file-contents">
                                                <p class="ifile"><i class="fa fa-file-excel-o"></i></p>
                                            </div>
                                            </td>
                                        </tr>

    <? }?>

                <? 
       $i = $i + 1;
                } ?>
									</tbody>
								</table>


							</div>
							<div class="module-foot">
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
</body>
<script type="text/javascript">


var fac = <?  printf($_SESSION['TxtFacility']) ?>


$('.datetimepicker').datetimepicker({
	step:5,
	lang:'es',
	format:'d/m/Y',
	timepicker: false
});



$(".requirement").fadeOut('fast');
$(".display-progress").fadeOut('fast');

$(".due").on('click', function (){
$(this).parent().parent().next('tr').fadeToggle('slow');
});


$(".enviar").on('click', function () {

	var iss_id = $(this).parent().parent().prev().children('td').eq(0).text();
	
	 $(this).parent().fadeOut('fast');
	 $(this).parent().parent().prev().fadeOut('fast');

     console.info("/" + iss_id +"/");
	 //variables 
    var msg = $(this).parent().children('textarea').val();

    var usr_id = $(this).parent().children('select').val();
    var fechaF = $(this).parent().children("input.datetimepicker").val();

   delegate(usr_id, msg, fechaF, iss_id);

});

$(".switcher").on('click', function(){
	 var all_on = document.querySelectorAll('.switcher');
     var ex = $(this).attr("id");
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

$.ajax({
	type: "POST",
	url: "../backend/delegate.php?fac=" + fac + 
	"&usr_id=" + usr_id + 
	"&msg=" + msg + 
	"&dataF=" + fechaF + 
	"&dataS=" + fechaS + 
	"&iss_id=" + iss_id + 
	"&fac=" + fac,
	success : function (data){

		if (parseInt(data) == 1){

       console.info('works');

		} else {

      console.info('not works');

		}
	}
});


}





</script>
<?

} else {
	echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>



