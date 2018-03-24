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

        <div class="row">
            <aside class="col-md-2 blog-aside">
                <div class="aside-widget">
                    <div class="body">
                        <ul class="clean-list">
                            <li><a href="{{ mkUrl("User.Show") }}">评论记录</a></li>
                            <li><a href="{{ mkUrl("User.ArticleList") }}">文章列表</a></li>
                            <li><a href="{{ mkUrl("User.ArticleAdd") }}">发表文章</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="col-md-10 blog-main">
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
    </div>
</div>
{% endblock %}
