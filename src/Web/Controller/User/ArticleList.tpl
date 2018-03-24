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
                {% for article in articles %}
                    <article class="blog-list">
                        <header>
                            <h3>{{ article.title }}</h3>

                            <div class="meta">
                                <i class="fa fa-user"></i> {{ article.author.nickname }}
                                <i class="fa fa-calendar"></i> {{ article.addtime | date('Y-m-d') }}
                                <i class="fa fa-comments"></i>
                                <span class="data"><a href="#comments"> {{ article.commentsNum }} Comments</a></span>

                                &nbsp;&nbsp;

                                <span>状态:</span>
                                {% if article.status == 1 %}
                                <span class="text-primary">草稿</span>
                                {% elseif article.status == 2 %}
                                <span class="text-info">审核</span>
                                {% elseif article.status == 3 %}
                                <span class="text-success">正式</span>
                                {% elseif article.status == 4 %}
                                <span class="text-muted">下线</span>
                                {% endif %}
                            </div>
                        </header>

                        <div class="body">
                            {{ article.intro }}
                        </div>
                        <div class="clearfix">
                        {% if article.tags %}
                            <span>标签:</span>
                            {% for tag in article.tags %}
                                <span>{{ tag.name }}</span>
                            {% endfor %}
                        {% endif %}

                            <a href="{{ article.link }}" class="btn btn-clean-one">预览</a>
                            <a href="{{ article.link }}" class="btn btn-clean-one">编辑</a>
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
