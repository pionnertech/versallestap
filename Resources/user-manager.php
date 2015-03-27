<?php session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == "rrhh"){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");
$all_users = mysqli_query($datos, "SELECT USR_ID, CONCAT(USR_NAME , ' ', USR_SURNAME) , USR_RANGE, USR_NICK, USR_PASS, USR_DEPT FROM USERS WHERE USR_FACILITY = " . $_SESSION['TxtFacility'] );

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestión de Personas</title>
        <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="../css/theme.css" rel="stylesheet">
        <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600'
            rel='stylesheet'>

       <style type="text/css">

     .in-controls{
        width:50%;
        display: block;
        margin: 1em 0;
     }

     .in-controls input {
        width:100%;
     }


       </style>
    </head>
    <body>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                        <i class="icon-reorder shaded"></i></a><a class="brand" href="index.html">Eque-e</a>
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
                                <img src="images/user.png" class="nav-avatar" />
                                <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Your Profile</a></li>
                                    <li><a href="#">Edit Profile</a></li>
                                    <li><a href="#">Account Settings</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">Logout</a></li>
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
                        
                    </div>

    
                    <!--/.span3-->
                      <div class="span9">
                         <div class="content">
                             <div class="module">
                                  <div class="module-body" style="display: inline-block: vertical-align: top;">
                                      <div class="in-controls" align="left">
                                           <input type="text" placeholder= "Nombres" id="uNam">
                                      </div>
                                      <div class="in-controls" align="left">
                                           <input type="text" placeholder="Apellidos" id="uSur">
                                      </div>
                                      <div class="in-controls input-prepend" align="left">
                                        <span class="add-on">@</span>
                                           <input style="width: 93%" type="text" placeholder="Correo electronico" id="uEma" >
                                      </div>
                                      <div class="in-controls" align="left">
                                           <input type="text" placeholder="Nombre de Usuario" id="uNic">
                                      </div>
                                    <div class="in-controls" align="left">
                                         <div style="width:auto; display: inline-block; vertical-align: top; margin: 0 .5em 0 0"><input type="password" style="width: 100%" placeholder="Contraseña" id="uPas"></div> 
                                         <div style="width:auto; display: inline-block; vertical-align: top; margin: 0 0 0 .5em"><input type="password" style="width: 100%" placeholder="Repita la contraseña" id="uRpa"></div> 
                                      </div>
                                  </div>
                                 <div style="display: inline-block; vertical-align: top;">
                                   <div class="user-box">
                                       <div class="user-pic-box">
                                           <img src="../images/user.png" class="user-pic">
                                       </div>
                                       <form>
                                           <input type="file" id="user-pic-url">
                                       </form>
                                   </div>


                                 </div>
                             <div class="module-head">
                                    <h3>
                                        DataTables</h3>
                                </div>
                                <div class="module-body table">
                                    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped display"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Nombre
                                                </th>
                                                <th>
                                                    Depto
                                                </th>
                                                <th>
                                                    Rango
                                                </th>
                                                <th>
                                                    Usuario
                                                </th>
                                                <th>
                                                    Contraseña
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <? 
                                        while ($users = mysqli_fetch_row( $all_users)){ ?>
                                            <tr class="gradeA">
                                            <input type="hidden" value="<? printf ($users[0]) ?>" id="iss_id">
                                         
                                                <td>
                                                    <? echo str_replace('\' ', '\'', ucwords(str_replace('\'', '\' ', strtolower($users[1])))) ?>
                                                </td>
                                                <td>
                                                    <? echo $users[5] ?>
                                                </td>
                                                <td>
                                                    <? echo $users[2] ?>
                                                </td>
                                                <td>
                                                    <? echo $users[3] ?>
                                                </td>
                                                <td class="center">
                                                    <? echo $users[4] ?>
                                                </td>
                                            </tr>
                                       <? } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>
                                                    Nombre
                                                </th>
                                                <th>
                                                    Depto
                                                </th>
                                                <th>
                                                    Rango
                                                </th>
                                                <th>
                                                    Usuario
                                                </th>
                                                <th>
                                                    Contraseña
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                         </div>
                          
                      </div>            

                    <!--/.span9-->
                </div>
            </div>
            <!--/.container-->
        </div>
        <!--/.wrapper-->
        <div class="footer">
            <div class="container">
                <b class="copyright">&copy; 2015 Eque-e.cl </b>Todos los derechos reservados.
            </div>
        </div>
        <script src="../scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="../scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../scripts/bootbox.min.js" type="text/javascript" ></script>
        <script src="../scripts/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="../scripts/common.js" type="text/javascript"></script>
    </body>
</html>
<script type="text/javascript">
   
/*
 $("span.promo-table").dblclick( function() { 

  var OriginalContent = $(this).text();
  var row = $(this).parent().parent().children('input').val();
  var field =  $(this).parent().index();
if (field !== 2){
$(this).addClass("cellEditing"); 
    $(this).html("<input type='text' value='" + OriginalContent + "' />");
        $(this).children().first().focus(); 

$(this).children().first().keypress(function (e) { 
        if (e.which == 13) { 
            console.info('llega');
            var newContent = $(this).val();
            console.info(row + "/" + newContent + "/" + field);
                promoPanel(row, newContent, field)
                     $(this).parent().text(newContent); 
                         $(this).parent().removeClass("cellEditing"); 
                     }
                 });

      $(this).children().first().blur(function(){
          $(this).parent().text(OriginalContent); 
              $(this).parent().removeClass("cellEditing");
        //$(this).parent('tr').children('').attr()
      }); 
}

  });    
*/

$("uEma").on("input paste keydown keypress", function (){
    if(!IsEmail($(this).val())){
        $(this).css({ color : "red"});
    } else {
        $(this).css({ color : "green"});
    }
}); 


function IsEmail(email){
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

</script>

<?

}  else {

 echo "<script language='javascript'>window.location='../index.php'</script>";
}

?>