{% extends "base.tpl" %}

{% block title %}About Me{% endblock %}

{% block breadcrumb %}
    <a href="/about">About Me</a>
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container about">
            <h1>Hello, My name is <span class="about-bold">  John Doe</span></h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus inventore magni ratione perferendis ex molestiae cum reiciendis perspiciatis consequuntur, nihil ducimus corrupti! Ipsum nesciunt ipsa nobis obcaecati labore, rem recusandae?</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Qui quo sint asperiores, ut doloremque eum commodi, odit nisi sed repellendus earum recusandae pariatur consectetur minus rerum, magni dolores officiis magnam.</p>

            <div class="about-button">
                <a class="btn btn-xlarge btn-clean-one" href="/contact">Contact Me</a>
            </div>
            <hr>
        </div>
    </div>
{% endblock %}
