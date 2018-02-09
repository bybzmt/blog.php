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
                            <li><a href="{{ mkUrl("User.Show") }}">发表文章</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="col-md-10 blog-main">
                {% for article in articles %}
                    <article class="blog-list">
                        <header>
                            <h3><a href="{{ article.link }}">{{ article.title }}</a></h3>

                            <div class="meta">
                                <i class="fa fa-user"></i> {{ article.author.nickname }}
                                <i class="fa fa-calendar"></i> {{ article.addtime | date('Y-m-d') }}
                                <i class="fa fa-comments"></i>
                                <span class="data"><a href="#comments"> {{ article.commentsNum }} Comments</a></span>
                            </div>
                        </header>

                        <div class="body">
                            {{ article.intro }}
                        </div>
                        <hr>
                    </article>
                {% endfor %}

                {% include "pagination.tpl" %}

            </div>

        </div>
    </div>
</div>
{% endblock %}
