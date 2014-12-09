<?php

session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'admin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS
$Query_task = mysqli_query($datos, "SELECT * FROM ISSUES");


?>

<!DOCTYPE html>
<html lang="en">

<head>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edmin</title>
	<link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="../css/theme.css" rel="stylesheet">
	<link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	<link rel="stylesheet" type="text/css" href="../css/selectize.bootstrap3.css" />
	<script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="../scripts/selectize.min.js"></script>
    <script type="text/javascript" src="../scripts/selectize.jquery.js"></script>
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
								<img src="../images/user.png" class="nav-avatar" />
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#">Your Profile</a></li>
								<li><a href="#">Edit Profile</a></li>
								<li><a href="#">Account Settings</a></li>
								<li class="divider"></li>
								<li><a href="#">Logout</a></li>
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
									ingreso de Audiencias
								</a>
							</li>
							<li>
								<a href="other-user-profile.php">
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
									Accessos
								</a>
								<ul id="togglePages" class="collapse unstyled">
									<li>
										<a href="index.html">
											<i class="icon-inbox"></i>
											Login
										</a>
									</li>
									<li>
										<a href="other-user-profile.html">
											<i class="icon-inbox"></i>
											Perfil
										</a>
									</li>
									<li>
										<a href="other-user-listing.html">
											<i class="icon-inbox"></i>
											Todos Los usuarios
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
											<li><a href="#">Todos</a></li>
											<li><a href="#">En Curso</a></li>
											<li><a href="#">Hechos</a></li>
											<li><a href="#">Atrasados</a></li>
											<li class="divider"></li>
											<li><a href="#">Reci</a></li>
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
            $i = 1;
						 while ($fila1 = mysqli_fetch_row($Query_task)){ ?>				
										<tr class="task">
											<td class="cell-icon"><? printf($fila1[0]) ?></td>
											<td class="cell-title"><div><? printf($fila1[3]) ?></div></td>
											<td class="cell-status hidden-phone hidden-tablet"><b class="due done"></b></td>
											<td class="cell-time align-right"><? printf($fila1[1]) ?></td>
											<input type="hidden" value="" >		
										</tr>
										<tr class="requirement">
											<td colspan="4" >
												<textarea class="description" placeholder="describa el requerimiento"></textarea>
												<label for="subject">Delegados</label>
												<select id="delegates<? printf($i)?>">
												 <optgroup label="Gerencia">
                                                     <option val="0">Alejandro Curaqueo</option>
                                                     <option val="1">Rodrigo Peña</option>
                                                     <option val="2">Pedro Cortez</option>
                                                  </optgroup>
                                                  <optgroup label="Informatica">
                                                     <option val="3">Francisco Papal</option>
                                                     <option val="4">Liliana Avogadro</option>
                                                  </optgroup>
                                                  <optgroup label="Contabilidad/Finanzas">
                                                     <option val="5">Jefferson Pimentel</option>
                                                     <option val="6">Gabriella Santorielli</option>
                                                     <option val="7">Laura Costa</option>
                                                     <option val="8">Anita Acosta</option>
                                                     <option val="9">Pablo Suarez</option>
                                                    </optgroup>
                                                    <optgroup label="Area Tecnica">
                                                     <option val="10">Leandro Martinez</option>
                                                     <option val="11">Macarena Arraño</option>
                                                     <option val="10">Patricio bustamante</option>
                                                     <option val="11">Felipe Beringer</option>
                                                     <option val="10">Mario Gallardo</option>
                                                     <option val="11">Jose Victorino</option>
                                                     <option val="10">Eduardo Lasalle</option>
                                                     <option val="11">Lena Fensterseifer</option>
                                                    </optgroup>

												</select>

                                               <script>
                                                $("#delegates<? printf($i) ?>").selectize();
                                               </script>
												<i class="fa fa-warning"></i><i class="fa fa-envelope"></i>
												<button class="btn-info enviar">Delegar task</button>
											</td>
										</tr>



                             
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
			 

			<b class="copyright">&copy; 2014 Edmin - EGrappler.com </b> All rights reserved.
		</div>
	</div>


	<script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>


</body>

<script type="text/javascript">
	
$(".requirement").fadeOut('fast');

$(".due").on('click', function (){

$(this).parent().parent().next('tr').fadeToggle('slow');

});








</script>
<?

} else {
	echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>