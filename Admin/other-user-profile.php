
<?php

session_start();

if(isset($_SESSION['TxtCode']) && $_SESSION['TxtRange'] === 'admin'){

$datos = mysqli_connect('localhost', "root", "MoNoCeRoS", "K_usr10000");


$Query_name = mysqli_query($datos, "SELECT FAC_NAME FROM FACILITY WHERE FAC_CODE = " . $_SESSION['TxtCode']);

//TASKS

$Query_subtask = mysqli_query($datos, "SELECT A.STSK_ID, A.STSK_ISS_ID, A.STSK_DESCRIP, B.EST_DESCRIPT, A.STSK_FINISH_DATE, B.EST_COLOR FROM SUBTASKS A INNER JOIN EST B ON(B.EST_CODE = A.STSK_STATE) WHERE (STSK_CHARGE_USR = '" . $_SESSION['TxtUser'] . " " . $_SESSION['TxtPass'] . "' AND SBTSK_FAC_CODE = " . $_SESSION['TxtFacility'] . ")" );


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
                    <div class="sidebar">
                        <ul class="widget widget-menu unstyled">
                            <li class="active"><a href="index.php"><i class="menu-icon icon-dashboard"></i>Vista Principal
                            </a></li>
                            <li><a href="activity.php"><i class="menu-icon icon-bullhorn"></i>ingreso de Audiencias</a>
                            </li>
                            <li><a href="other-user-profile.php"><i class="menu-icon icon-inbox"></i>Perfil de Usuario<b class="label green pull-right">
                                11</b> </a></li>
                            <li><a href="task.php"><i class="menu-icon icon-tasks"></i>Control de Cumplimientos<b class="label orange pull-right">
                                19</b> </a></li>
                        </ul>
                        <!--/.widget-nav-->
 
                        <!--/.widget-nav-->
                        <ul class="widget widget-menu unstyled">
                            <li><a class="collapsed" data-toggle="collapse" href="#togglePages"><i class="menu-icon icon-cog">
                            </i><i class="icon-chevron-down pull-right"></i><i class="icon-chevron-up pull-right">
                            </i>Vistas </a>
                                <ul id="togglePages" class="collapse unstyled">
                                    <li><a href="other-login.html"><i class="icon-bar-chart"></i> Estadisticas</a></li>
                                    <li><a href="other-user-profile.html"><i class="icon-upload-alt"></i>Progresos</a></li>
                                    <li><a href="other-user-listing.html"><i class="icon-time"></i>Historial Requerimientos</a></li>
                                </ul>
                            </li>
                            <li><a href="#"><i class="menu-icon icon-signout"></i>Logout </a></li>
                        </ul>
                        <h3>Mis Compromisos</h3>
                        <div id="Urgencias" class="OwnComp">
                            <div class="OwnComp-bars" style="border-right-color: #EA0000; border-left-color: #EA0000; cursor: pointer;" id="Urgent">Urgencias</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Urgent-Display">
                                        <li>
                                            <p>
                                             <strong>Urgencias Recibidas</strong> <span class="pull-right small muted">17%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 17%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias Activas</strong><span class="pull-right small muted">88%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 88%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias por vencer</strong> <span class="pull-right small muted">12%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 12%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Urgencias Atrasadas</strong> <span class="pull-right small muted">2%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 2%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                               <div id="Audiencias" class="OwnComp">
                <div class="OwnComp-bars" style="border-right-color: #009D00; border-left-color: #009D00; cursor: pointer;" id="Audi">Audiencias</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Audi-Display">
                                        <li>
                                            <p>
                                             <strong>Audiencias Recibidas</strong> <span class="pull-right small muted">17%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar" style="width: 17%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias Activas</strong><span class="pull-right small muted">88%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-success" style="width: 88%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias por vencer</strong> <span class="pull-right small muted">12%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-warning" style="width: 12%;">
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <p>
                                                <strong>Audiencias Atrasadas</strong> <span class="pull-right small muted">2%</span>
                                            </p>
                                            <div class="progress tight">
                                                <div class="bar bar-danger" style="width: 2%;">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <div id="Compromisos" class="OwnComp">
                              <div class="OwnComp-bars" style="border-right-color: #005FAA; border-left-color: #005FAA; cursor: pointer;" id="Com">Compromisos</div>
                                 <ul class="widget widget-usage unstyled progressDisplay" id="Com-Display">
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
                                    <a href="#" class="media-avatar pull-left">
                                        <img src="../images/ejecutivo4.jpg">
                                    </a>
                                    <div class="media-body">
                                        <h4>
                                           Juanito Perez Cotapos<small>Offline</small>
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
                                    <li class="active"><a href="#activity" data-toggle="tab">Eventos</a></li>
                                    <li><a href="#friends" data-toggle="tab">Equipo de trabajo</a></li>
                                    <li><a href="#require" data-toggle="tab">Control cumplimientos</a></li>
                                    <li><a href="#tasks-own" data-toggle="tab">Mis Requerimientos</a></li>
                                </ul>
                                <div class="profile-tab-content tab-content">
                                    <div class="tab-pane fade active in" id="activity">
                                        <div class="stream-list">
                                            <div class="media stream">
                                                <a href="#" class="media-avatar medium pull-left">
                                                    <img src="..7images/user.png">
                                                </a>
                                                <div class="media-body">
                                                    <div class="stream-headline">
                                                        <h5 class="stream-author">
                                                            John Donga <small>08 July, 2014</small>
                                                        </h5>
                                                        <div class="stream-text">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                                            unknown printer took a galley of type.
                                                        </div>
                                                        <div class="stream-attachment photo">
                                                            <div class="responsive-photo">
                                                                <img src="../images/img.jpg" alt="" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/.stream-headline-->
                                                    <div class="stream-options">
                                                        <a href="#" class="btn btn-small"><i class="icon-thumbs-up shaded"></i>Like </a>
                                                        <a href="#" class="btn btn-small"><i class="icon-reply shaded"></i>Reply </a><a href="#"
                                                            class="btn btn-small"><i class="icon-retweet shaded"></i>Repost </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/.media .stream-->
                                            <div class="media stream">
                                                <a href="#" class="media-avatar medium pull-left">
                                                    <img src="../images/user.png">
                                                </a>
                                                <div class="media-body">
                                                    <div class="stream-headline">
                                                        <h5 class="stream-author">
                                                            John Donga <small>08 July, 2014</small>
                                                        </h5>
                                                        <div class="stream-text">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                                            unknown printer took a galley of type.
                                                        </div>
                                                        <div class="stream-attachment video">
                                                            <div class="responsive-video">
                                                                <iframe src="//player.vimeo.com/video/20630217" width="560" height="315" frameborder="0"
                                                                    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                                                <p>
                                                                    <a href="http://vimeo.com/20630217">Google Car</a> from <a href="http://vimeo.com/user3524956">
                                                                        Henk Rogers</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/.stream-headline-->
                                                    <div class="stream-options">
                                                        <a href="#" class="btn btn-small"><i class="icon-thumbs-up shaded"></i>Like </a>
                                                        <a href="#" class="btn btn-small"><i class="icon-reply shaded"></i>Reply </a><a href="#"
                                                            class="btn btn-small"><i class="icon-retweet shaded"></i>Repost </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/.media .stream-->
                                            <div class="media stream">
                                                <a href="#" class="media-avatar medium pull-left">
                                                    <img src="../images/user.png">
                                                </a>
                                                <div class="media-body">
                                                    <div class="stream-headline">
                                                        <h5 class="stream-author">
                                                            John Donga <small>08 July, 2014</small>
                                                        </h5>
                                                        <div class="stream-text">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                                            unknown printer took a galley of type.
                                                        </div>
                                                    </div>
                                                    <!--/.stream-headline-->
                                                    <div class="stream-options">
                                                        <a href="#" class="btn btn-small"><i class="icon-thumbs-up shaded"></i>Like </a>
                                                        <a href="#" class="btn btn-small"><i class="icon-reply shaded"></i>Reply </a><a href="#"
                                                            class="btn btn-small"><i class="icon-retweet shaded"></i>Repost </a>
                                                    </div>
                                                    <div class="stream-respond">
                                                        <div class="media stream">
                                                            <a href="#" class="media-avatar small pull-left">
                                                                <img src="../images/user.png">
                                                            </a>
                                                            <div class="media-body">
                                                                <div class="stream-headline">
                                                                    <h5 class="stream-author">
                                                                        John Donga <small>10 July 14</small>
                                                                    </h5>
                                                                    <div class="stream-text">
                                                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                                    </div>
                                                                </div>
                                                                <!--/.stream-headline-->
                                                            </div>
                                                        </div>
                                                        <!--/.media .stream-->
                                                        <div class="media stream">
                                                            <a href="#" class="media-avatar small pull-left">
                                                                <img src="../images/user.png">
                                                            </a>
                                                            <div class="media-body">
                                                                <div class="stream-headline">
                                                                    <h5 class="stream-author">
                                                                        John Donga <small>12 July 14</small>
                                                                    </h5>
                                                                    <div class="stream-text">
                                                                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                                                        Ipsum is simply dummy text.
                                                                    </div>
                                                                </div>
                                                                <!--/.stream-headline-->
                                                            </div>
                                                        </div>
                                                        <!--/.media .stream-->
                                                    </div>
                                                    <!--/.stream-respond-->
                                                </div>
                                            </div>
                                            <!--/.media .stream-->
                                            <div class="media stream">
                                                <a href="#" class="media-avatar medium pull-left">
                                                    <img src="../images/user.png">
                                                </a>
                                                <div class="media-body">
                                                    <div class="stream-headline">
                                                        <h5 class="stream-author">
                                                            John Donga <small>08 July, 2014</small>
                                                        </h5>
                                                        <div class="stream-text">
                                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                                            unknown printer took a galley of type.
                                                        </div>
                                                    </div>
                                                    <!--/.stream-headline-->
                                                    <div class="stream-options">
                                                        <a href="#" class="btn btn-small"><i class="icon-thumbs-up shaded"></i>Like </a>
                                                        <a href="#" class="btn btn-small"><i class="icon-reply shaded"></i>Reply </a><a href="#"
                                                            class="btn btn-small"><i class="icon-retweet shaded"></i>Repost </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--/.media .stream-->
                                            <div class="media stream load-more">
                                                <a href="#"><i class="icon-refresh shaded"></i>Load more... </a>
                                            </div>
                                        </div>
                                        <!--/.stream-list-->
                                    </div>
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
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/user.png">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                John Donga</h3>
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
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="media user">
                                                        <a class="media-avatar pull-left" href="#">
                                                            <img src="../images/user.png">
                                                        </a>
                                                        <div class="media-body">
                                                            <h3 class="media-title">
                                                                John Donga</h3>
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
                                        <button class="btn">Todos</button>
                                        <button class="btn dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Todos</a></li>
                                            <li><a href="#">En Progreso</a></li>
                                            <li><a href="#">finalizados</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">Nuevo requerimiento</a></li>
                                            <li><a href="#">Atrasados</a></li>
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
                                             <td class="cell-title">Responsable</td>
                                             <td class="cell-time align-right">Fecha</td>

                                        </tr>
                                        <? while ( $stsk = mysqli_fetch_row($Query_subtask)){ ?> 
                                        <tr class="task">
                                            <td class="cell-icon"><i class="icon-checker high"></i></td>
                                            <td class="cell-title"><span><? printf($stsk[2])  ?></span></td>
                                            <td class="cell-status hidden-phone hidden-tablet"><b class="due" style="background-color: <? printf($stsk[5]) ?>;"><? printf($stsk[3]) ?></b></td>
                                            <td class="cell-title"></td>
                                            <td class="cell-time align-right"><span><? printf($stsk[4]) ?></span></td>
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
<!--  selecionar los nombres -->
                        <div class="tab-pane fade" id="tasks-own">
                           <div class="media-stream">
                                <div class="sub-del">
                                    <h3>Subdelegar tareas</h3>
                                                <select id="delegates">
                                                    <optgroup label="Area Tecnica">
                                                        <option val="10">Leandro Martinez</option>
                                                        <option val="11">Macarena Arraño</option>
                                                        <option val="12">Patricio bustamante</option>
                                                        <option val="13">Felipe Beringer</option>
                                                        <option val="14">Mario Gallardo</option>
                                                        <option val="15">Jose Victorino</option>
                                                        <option val="16">Eduardo Lasalle</option>
                                                        <option val="17">Lena Fensterseifer</option>
                                                    </optgroup>
                                                </select>
                                    <input type="text" id="subject" class="require-subtasks" val="" placeholder="asunto">
                                    <textarea id="st-description" placeholder="Descripcion del requerimiento" style="margin: 1.5em .5em"></textarea>
                                </div>
                                <div class="attach">
                                    <form id="upload" method="post" action="../backend/upload.php" enctype="multipart/form-data">
                                         <div id="drop">
                                             Arrastra Aqui
                                               <a>Buscar</a>
                                               <input type="file" name="upl" multiple />
                                               <input type="hidden" value="" name="code">
                                               <input type="hidden" value="" name="fac">
                                               <input type="hidden" value="" name="user">
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
    </div>
    <!--/.wrapper-->
    <div class="footer">
        <div class="container">
            <b class="copyright">&copy; 2014 Edmin - EGrappler.com </b>All rights reserved.
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
    

    $(document).on('ready', function(){

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

                })

    });



</script>
<?

}  else {

    echo "<script language='javascript'>window.location='../login.php'</script>";
}


?>