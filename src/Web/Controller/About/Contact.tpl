{% extends "base.tpl" %}

{% block title %}Contact Me{% endblock %}

{% block breadcrumb %}
    <a href="{{ mkUrl("About.Contact") }}">Contact Me</a>
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 clean-superblock" id="contact">
                    <h2>Contact</h2>

                    <form action="#" method="get" accept-charset="utf-8" class="contact-form">
                        <input type="text" name="name" id="contact-name" placeholder="Name" class="form-control input-lg">
                        <input type="email" name="email" id="contact-email" placeholder="Email" class="form-control input-lg">
                        <textarea rows="10" name="message" id="contact-body" placeholder="Your Message" class="form-control input-lg"></textarea>
                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-xlarge btn-clean-one">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{% endblock %}
