{% extends "base.tpl" %}

{% block title %}Contact Me{% endblock %}

{% block breadcrumb %}
    <span>Register</span>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="{{ mkUrl("User.Login") }}">Login</a>
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 clean-superblock" id="contact">
                    <h2>Register</h2>

                    <form action="{{mkUrl("User.RegisterExec")}}" method="post" class="contact-form" />
                        {% if msg %}
                        <div class="col-md-12">
                            <p class="alert alert-warning">{{msg}}</p>
                        </div>
                        {% endif %}

                        <div class="col-md-12">
                            <input type="text" name="username" placeholder="UserName" class="form-control input-lg" />
                        </div>
                        <div class="col-md-12">
                            <input type="password" name="password" placeholder="Password" class="form-control input-lg" />
                        </div>
                        <div class="col-md-12">
                            <input type="text" name="nickname" placeholder="NickName" class="form-control input-lg" />
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="captcha" placeholder="Captcha" class="form-control input-lg" />
                        </div>
                        <div class="col-md-4 form-group">
                            <img style="margin-top:15px" src="/captcha?{{random()}}" onclick="this.src='/captcha?'+(new Date()).getTime()" />
                        </div>
                        <div class="col-md-12">
                            <div class="buttons clearfix">
                                <button type="submit" class="btn btn-xlarge btn-clean-one">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
