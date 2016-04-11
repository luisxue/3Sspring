<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
	<head>
	<meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>
		<title>聚有钱|</title>
		{{ stylesheet_link('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}
        		{{ stylesheet_link('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}
        		{{ stylesheet_link('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}
        		{{ stylesheet_link('assets/global/plugins/uniform/css/uniform.default.css') }}
        		{{ stylesheet_link('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}
        		{{ stylesheet_link('assets/global/plugins/gritter/css/jquery.gritter.css') }}
        		{{ stylesheet_link('assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}
        		{{ stylesheet_link('assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}
        		{{ stylesheet_link('assets/global/plugins/jqvmap/jqvmap/jqvmap.css') }}
        		{{ stylesheet_link('assets/admin/pages/css/tasks.css') }}
        		{{ stylesheet_link('assets/global/css/components.css') }}
        		{{ stylesheet_link('assets/global/css/plugins.css') }}
        		{{ stylesheet_link('assets/admin/layout2/css/layout.css') }}
        		{{ stylesheet_link('assets/admin/layout2/css/themes/default.css') }}
        		{{ stylesheet_link('assets/admin/layout2/css/custom.css') }}

	{{ stylesheet_link('assets/admin/pages/css/error.css') }}

        		{{ stylesheet_link('assets/global/plugins/select2/select2.css') }}
        		{{ stylesheet_link('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}
		<link href="http://fonts.useso.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	</head>
<body class="page-boxed page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed-hide-logo">

<div >
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner container">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="index.html">
            {{image("assets/admin/layout2/img/logo-default.png", "class": "logo-default","alt":"logo")}}
            </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE ACTIONS -->
        <!-- DOC: Remove "hide" class to enable the page header actions -->
        <div class="page-actions hide">
            <div class="btn-group">
                <button type="button" class="btn btn-circle red-pink dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-bar-chart"></i>&nbsp;<span class="hidden-sm hidden-xs">New&nbsp;</span>&nbsp;<i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="#">
                            <i class="icon-user"></i> New User </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-present"></i> New Event <span class="badge badge-success">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-basket"></i> New order </a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-flag"></i> Pending Orders <span class="badge badge-danger">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-users"></i> Pending Users <span class="badge badge-warning">12</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-circle green-haze dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-bell"></i>&nbsp;<span class="hidden-sm hidden-xs">Post&nbsp;</span>&nbsp;<i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="#">
                            <i class="icon-docs"></i> New Post </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-tag"></i> New Comment </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-share"></i> Share </a>
                    </li>
                    <li class="divider">
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-flag"></i> Comments <span class="badge badge-success">4</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-users"></i> Feedbacks <span class="badge badge-danger">2</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END PAGE ACTIONS -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN HEADER SEARCH BOX -->
            <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
            <form class="search-form search-form-expanded" action="extra_search.html" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="query">
					<span class="input-group-btn">
					<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
					</span>
                </div>
            </form>
            <!-- END HEADER SEARCH BOX -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    <!-- END NOTIFICATION DROPDOWN -->
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- END INBOX DROPDOWN -->
                    <!-- BEGIN TODO DROPDOWN -->
                    <!-- END TODO DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <li class="dropdown dropdown-quick-sidebar-toggler hide">
                        <a href="javascript:;" class="dropdown-toggle">
                            <i class="icon-logout"></i>
                        </a>
                    </li>
                    <!-- END QUICK SIDEBAR TOGGLER -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-user">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<?php echo $this->tag->image(
                                  array(
                                     "assets/admin/layout2/img/avatar4.jpg",
                                     "class" => "img-circle hide1"
                                  )
                              ) ?>
						<span class="username username-hide-on-mobile">
						{{ elements.getLogoutHtml() }} </span>
						<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu">

<li>
							<?php echo $this->tag->linkTo(array("sysuser/updatepassword", "<i class=\"icon-key\"></i>修改密码")) ?></td>

							</li>
							<li>
							<?php echo $this->tag->linkTo(array("loginve", "<i class=\"icon-key\"></i>退出")) ?></td>

							</li>
						</ul>


                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="container">
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
 <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-auto-scroll="true" data-slide-speed="200">
{{ elements.getMenu() }}
 </ul>
                <!-- END SIDEBAR MENU -->
            </div>
        </div>
        <!-- END SIDEBAR -->
<div class="page-content-wrapper">
<div class="page-content">

{{ flash.output() }}
    {{ content() }}
    </div>
</div>
        <!--Cooming Soon...-->
        <!-- END QUICK SIDEBAR -->

</div>

  <div class="page-footer">
          <div class="page-footer-inner">
              2014 &copy;  IHMEDIA-Integral wall.
          </div>
          <div class="scroll-to-top">
              <i class="icon-arrow-up"></i>
          </div>
      </div>
</div>

   {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js') }}
      {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js') }}
  {{ javascript_include('assets/global/plugins/flot/jquery.flot.resize.min.js') }}
    {{ javascript_include('assets/global/plugins/flot/jquery.flot.min.js') }}
      {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js') }}
        {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js') }}
          {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js') }}
            {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js') }}
              {{ javascript_include('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js') }}
{{ javascript_include('assets/global/plugins/gritter/js/jquery.gritter.js') }}
               {{ javascript_include('assets/global/plugins/jquery.sparkline.min.js') }}
{{ javascript_include('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') }}
               {{ javascript_include('assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js') }}

               {{ javascript_include('assets/global/plugins/bootstrap-daterangepicker/moment.min.js') }}
             {{ javascript_include('assets/global/plugins/jquery.pulsate.min.js') }}
               {{ javascript_include('assets/global/plugins/flot/jquery.flot.categories.min.js') }}
                {{ javascript_include('assets/global/plugins/jquery-1.11.0.min.js') }}
             {{ javascript_include('assets/global/plugins/jquery-migrate-1.2.1.min.js') }}

           {{ javascript_include('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js') }}
             {{ javascript_include('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}
       {{ javascript_include('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}
       {{ javascript_include('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}
        {{ javascript_include('assets/global/plugins/jquery.blockui.min.js') }}
        {{ javascript_include('assets/global/plugins/jquery.cokie.min.js') }}

          {{ javascript_include('assets/global/plugins/uniform/jquery.uniform.min.js') }}
       {{ javascript_include('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}
       {{ javascript_include('assets/global/scripts/metronic.js') }}
       {{ javascript_include('assets/admin/pages/scripts/tasks.js') }}
        {{ javascript_include('assets/admin/pages/scripts/index.js') }}


        {{ javascript_include('assets/admin/layout2/scripts/layout.js') }}
         {{ javascript_include('assets/admin/layout2/scripts/demo.js') }}
          {{ javascript_include('assets/global/plugins/select2/select2.min.js') }}
          {{ javascript_include('assets/global/scripts/metronic.js') }}


                 {{ javascript_include('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
                 {{ javascript_include('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}
                  {{ javascript_include('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}
                   {{ javascript_include('assets/admin/pages/scripts/table-editable.js') }}

                      {{ javascript_include('assets/global/scripts/datatable.js') }}
                    {{ javascript_include('assets/admin/pages/scripts/table-ajax.js') }}
                        {{ javascript_include('assets/admin/pages/scripts/UIhelper.js') }}


<script>
jQuery(document).ready(function() {
  {{ elements.GetCheckedJs() }} // init metronic core componets
   Layout.init(); // init layout
   Demo.init(); // init demo features

{{ elements.GetJs() }}
   UserPass.init();
});
</script>

</body>
{{ javascript_include('js/jquery.qrcode.min.js') }}
<script type="text/javascript">
$(function(){
	var str = <?php echo "'".$qrurl."'"; ?>;
	$('#myqrcode').qrcode(str);
})


</script>
</html>