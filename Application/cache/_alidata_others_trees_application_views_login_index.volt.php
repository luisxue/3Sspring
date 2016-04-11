<?php

    //带有额外的html标签的恶意的文档标题
    $maliciousTitle = '</title><script>alert(1)</script>';

    //恶意的css类名
    $className = ';`(';

    //恶意的css字体名
    $fontName = 'Verdana"</style>';

    //恶意的Javascript文本
    $javascriptText = "';</script>Hello";

    //创建转义实例对象
    $e = new Phalcon\Escaper();

?>
<!DOCTYPE html>
<html>
	<head>
	   <meta charset="utf-8"/>
		<title><?php echo $e->escapeHtml("Trees") ?></title>
		<?php echo $this->tag->stylesheetLink('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/plugins/uniform/css/uniform.default.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/plugins/select2/select2.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/admin/pages/css/login.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/css/components.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/global/css/plugins.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/admin/layout2/css/layout.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/admin/layout2/css/custom.css'); ?>
        		<?php echo $this->tag->stylesheetLink('assets/admin/layout2/css/themes/default.css'); ?>
		<link href="http://fonts.useso.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
	</head>
<body class="login">
<!-- BEGIN LOGO -->
<div class="logo">
		<span style="">
		<font size="3" color="#ffffff" face="Times">
       IHMEDIA-Integral wall
        </font>
 </span>
</div>

<div class="content">

	<!-- BEGIN LOGIN FORM -->
	<?php echo $this->tag->form(array('loginv', 'onSubmit' => 'return beforeSubmit(this);', 'class' => 'login-form')); ?>
		<h3 class="form-title">登录</h3>
	    <?php echo $this->flash->output(); ?>
	    <div class="alert alert-danger display-hide" id="divError">
        			<span id="ErrorLogin">
        			 </span>
        		</div>

		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">账号</label>
			<div class="input-icon">
				<i class="fa fa-user"></i>
				<input class="form-control placeholder-no-fix"   type="text" autocomplete="off" placeholder="账号" name="Account"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">密码</label>
			<div class="input-icon">
				<i class="fa fa-lock"></i>
				<input class="form-control placeholder-no-fix"  type="password" autocomplete="off" placeholder="密码" name="Password"/>
			</div>
		</div>
		<div class="form-actions">
			<label class="checkbox">
			<input type="checkbox" name="remember" value="1"/> 记住账户 </label>
			<input type="hidden" name="<?php echo $this->security->getTokenKey() ?>"
                                                  value="<?php echo $this->security->getToken() ?>"/>
			<button type="submit" class="btn green pull-right">
			登录 <i class="m-icon-swapright m-icon-white"></i>
			</button>
		</div>
		<div class="forget-password">
			<h4></h4>
			<p>

			</p>
		</div>
	</form>


</div>
<!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 2015 &copy; 勤诚互动.
</div>

                               <?php echo $this->tag->javascriptInclude('assets/global/plugins/respond.min.js'); ?>
                               <?php echo $this->tag->javascriptInclude('assets/global/plugins/excanvas.min.js'); ?>
                               <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery-1.11.0.min.js'); ?>
                                 <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery-migrate-1.2.1.min.js'); ?>
                                 <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js'); ?>
                                 <?php echo $this->tag->javascriptInclude('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>
                                     <?php echo $this->tag->javascriptInclude('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>
                                                     <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>
                                                      <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery.blockui.min.js'); ?>
                                                       <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery.cokie.min.js'); ?>
                                                        <?php echo $this->tag->javascriptInclude('assets/global/plugins/uniform/jquery.uniform.min.js'); ?>
                                                         <?php echo $this->tag->javascriptInclude('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>
                                                          <?php echo $this->tag->javascriptInclude('assets/global/scripts/metronic.js'); ?>
                                                           <?php echo $this->tag->javascriptInclude('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>
                                                            <?php echo $this->tag->javascriptInclude('assets/global/plugins/select2/select2.min.js'); ?>
                                                             <?php echo $this->tag->javascriptInclude('assets/admin/layout2/scripts/layout.js'); ?>
                                                              <?php echo $this->tag->javascriptInclude('assets/admin/layout2/scripts/demo.js'); ?>
                                                                <?php echo $this->tag->javascriptInclude('assets/admin/pages/scripts/login.js'); ?>


                                             <script>
                                             jQuery(document).ready(function() {
                                               Metronic.init(); // init metronic core components


                                             });
                                             </script>


</body>
</html>