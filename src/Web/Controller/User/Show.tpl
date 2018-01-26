{% extends "base.tpl" %}

{% block title %}Contact Me{% endblock %}

{% block breadcrumb %}
    <a href="{{ mkUrl("User.Show") }}">User</a>

    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="{{ mkUrl("User.Logout") }}">Logout</a>
{% endblock %}

{% block content %}
<div class="widewrapper main">
    <div class="container user">

        {% for record in records %}
        <article>
        {% if record.type == 1 %}
            <a href="{{ record.link }}">
                <p>{{ record.content }}</p>
                <blockquote>{{ record.article_intro }}</blockquote>
            </a>
        {% elseif record.type == 2 %}
            <a href="{{ record.link }}">
                <p>{{ record.content }}</p>
                <blockquote>{{ record.comment_content }}</blockquote>
            </a>
        {% endif %}
        </article>
        <hr/>
        {% endfor %}

        {% include "pagination.tpl" %}
    </div>
</div>
{% endblock %}
