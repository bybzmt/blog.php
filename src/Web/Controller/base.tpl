<!DOCTYPE html>
<html>
  <head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />

        {% block head %}
            <title>{% block title %}{% endblock %} - My Webpage</title>
        {% endblock %}
    </head>
<body>

<div>
    <div id="header">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 logo-div">
                        <div class="logo-inner text-center">
                            <div class="logo-name">
                                <a href="index.html">
                                    <img src="/img/me.jpg" class="img-circle" />
                                </a>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-8 header-text-top " id="about">
                        <h1>Nice responsive template for blogger.</h1>
						This blogging template use bootstrap and html to create a very nice blogging page with great responsive. <br /> 
						Here you can write a general notes about your blog.<br />
						<h2><strong>Who I am ? </strong></h2>
                        <i>I am Jhon Deo </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END HEADER SECTION-->

    <div class="info-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    Say hello to me at <strong>hello</strong>@yourdomain.com
                </div>
                <div class="col-md-2">
                    <div class="social-link">
                        <a href="#" class="btn btn-default btn-xs">分享</a>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--END INFO SECTION-->

    {% block content %}{% endblock %}

    <!--END HOME PAGE SECTION-->
    <div class="footer-sec" style="margin-top: 0px;">
        <div class="container">
            <div class="row">
                <div class="col-md-12 foo-inner">
                    &copy; 2015 bybzmt.me
                </div>
            </div>
        </div>
    </div>
    <!-- END FOOTER SECTION -->

</div>

  </body>
</html>
