
<?php

session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] == 'front-user'){

$datos = mysqli_connect('mysql.nixiweb.com', "u315988979_eque", "MoNoCeRoS", "u315988979_eque");

$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);


//TASKS


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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edmin</title>
    <link type="text/css" href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="../css/theme.css" rel="stylesheet">  
    <link type="text/css" href="../css/uploader_style.css" rel="stylesheet" />
    <link type="text/css" href="../images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
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


    </style>    

</head>
<body>
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
                            <img src="../images/ejecutivo4.jpg" class="nav-avatar" />
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
                            <li><a href="activity.php"><i class="menu-icon icon-bullhorn"></i>ingreso de Audiencias</a>
                            </li>
                            <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario<b class="label green pull-right">
                                11</b> </a></li>
                            <li><a href="task.php"><i class="menu-icon icon-tasks"></i>Control de Cumplimientos<b class="label orange pull-right">
                                19</b> </a></li>
                        </ul>
                        <!--/.widget-nav-->
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
                                        <img src="../images/ejecutivo4.jpg">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           <? printf($_SESSION["TxtUser"]) ?><small>Online</small>
                                        </h4>
                                        <p class="profile-brief">
                                         Gerente General En Profits Taggle Inc.
                                        </p>
                                        <div class="profile-details muted">
                                            <a href="#" class="btn"><i class="icon-plus shaded"></i>Send Friend Request </a>
                                            <a href="#" class="btn"><i class="icon-envelope-alt shaded"></i>Send message </a>
                                        </div>
                                    </div>
                                </div>
                                <ul class="profile-tab nav nav-tabs">
                                    <li class="active"><a href="#friends" data-toggle="tab" >Personal</a></li>
                                    <li><a href="#tasks-own" data-toggle="tab">Mis Requerimientos</a></li>
                                </ul>
                                <div class="profile-tab-content tab-content">
                                    <div class="tab-pane fade" id="friends">
                                        <div class="module-option clearfix">
                                            <form>
                                            <div class="input-append pull-left">
                                                <input type="text" class="span3" placeholder="Filtrar por nombre...">
                                                <button type="submit" class="btn">
                                                    <i class="icon-search"></i>
                                                </button>
                                            </div>
                                            </form>
                                            <div class="btn-group pull-right" data-toggle="buttons-radio">
                                                <button type="button" class="btn">
                                                    Todos</button>
                                                <button type="button" class="btn">
                                                    Planta</button>
                                                <button type="button" class="btn">
                                                    Contrata</button>
                                            </div>
                                        </div>
                                        <div class="module-body">
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/ejecutivo3.jpg">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                Hellen
                                                            </h3>
                                                            <p>
                                                                <small class="muted">Pakistan</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/ejecutivo1.jpg">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                Donga John</h3>
                                                            <p>
                                                                <small class="muted">Pakistan</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/.row-fluid-->
                                            <br />
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/ejecutivo5.jpg">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                Andre</h3>
                                                            <p>
                                                                <small class="muted">Pakistan</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/user.png">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                Donga John</h3>
                                                            <p>
                                                                <small class="muted">Pakistan</small></p>
                                                            <div class="media-option btn-group shaded-icon">
                                                                <button class="btn btn-small">
                                                                    <i class="icon-envelope"></i>
                                                                </button>
                                                                <button class="btn btn-small">
                                                                    <i class="icon-share-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/.row-fluid-->
                                            <br />

                                            </div>
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
            <b class="copyright">&copy; 2014 Eque-e  </b>Todos los derechos reservados
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
</body>

<script type="text/javascript">
    



</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../login.php'</script>";
}


?>