<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>DASHGUM - Bootstrap Admin Template</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/assets/html5shiv.js"></script>
      <script src="/assets/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">

		      <form id="login-form" class="form-login" action="{{ mkUrl('Admin.DoLogin')|raw }}">
		        <h2 class="form-login-heading">sign in now</h2>
		        <div class="login-wrap">
                    <div id="err-msg" class="hidden alert alert-danger">错误信息</div>
		            <input type="text" name="user" class="form-control" placeholder="User ID" autofocus>
		            <br>
		            <input type="password" name="pass" class="form-control" placeholder="Password">
                    <br>
                    <div class="row">
                              <div class="col-md-6">
                                <input type="text" name="captcha" class="form-control" placeholder="请输入验证码" />
                              </div>
                              <div class="col-md-6">
                                <a onclick="change_captcha()" href="javascript:void(0)"><img id="captcha" /></a>
                              </div>
                      </div>
                    <br>

		            <button class="btn btn-theme btn-block" onclick="doSubmit()" type="button">
                    <i class="fa fa-lock"></i> SIGN IN</button>

		        </div>

		      </form>

	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="/assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("/assets/img/login-bg.jpg", {speed: 500});

        function doSubmit()
        {
            var data = $("#login-form").serialize();
            var url = $("#login-form").attr("action");

            $.post(url, data, function(json){
                if (json.ret > 0) {
                    if (json.ret == 3) {
                        console.log(json.data);
                        json.data = "服务器异常";
                    }

                    $("#err-msg").html(json.data);
                    $("#err-msg").removeClass("hidden");
                    change_captcha();
                } else {
                    location.href = "/";
                }
            }, 'json');
        }

        function change_captcha()
        {
            var src = "{{ mkUrl('Admin.Captcha') }}";
            src += "?" + (new Date()).getTime()
            $("#captcha").attr("src", src);
        }

        $(change_captcha);
    </script>


  </body>
</html>
