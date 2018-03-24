{% extends "base.tpl" %}

{% block head %}
    <title>暴雨不在明天的博客</title>
{% endblock %}

{% block breadcrumb %}
    <a href="/">Blog</a>
    {% if tag %}
    <span class="separator">&#x2F;</span>
    <a href="{{ mkUrl("Article.Lists", {tag:tag.id}) }}">{{ tag.name }}</a>
    {% endif %}
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-main">

                {% for article in articles %}
                    <div class="col-md-12 col-sm-12">
                        <article class="blog-list">
                            <header>
                                <h3><a href="{{ article.link }}">{{ article.title }}</a></h3>

                                <div class="meta">
                                    <i class="fa fa-user"></i> {{ article.author.nickname }}
                                    <i class="fa fa-calendar"></i> {{ article.addtime | date('Y-m-d') }}
                                    <i class="fa fa-comments"></i>
                                    <span class="data"><a href="{{ article.link }}#comments"> {{ article.commentsNum }} Comments</a></span>
                                </div>
                            </header>

                            <div class="body">
                                {{ article.intro }}
                            </div>
                            <div class="clearfix">
                                {% for tag in article.tags %}
                                    <a href="{{ mkUrl('Article.Lists', {tag:tag.id}) }}">{{ tag.name }}</a>
                                {% endfor %}

                                <a href="{{ article.link }}" class="btn btn-clean-one">Read more</a>
                            </div>
                                <hr>
                        </article>
                    </div>
                {% endfor %}

                    {% include "pagination.tpl" %}
                </div>
                <aside class="col-md-4 blog-aside">

                    <div class="aside-widget">
                        <header>
                            <h3>Featured Post</h3>
                        </header>
                        <div class="body">
                            <ul class="clean-list">
                                <li><a href="">Clean - Responsive HTML5 Template</a></li>
                                <li><a href="">Responsive Pricing Table</a></li>
                                <li><a href="">Yellow HTML5 Template</a></li>
                                <li><a href="">Blackor Responsive Theme</a></li>
                                <li><a href="">Portfolio Bootstrap Template</a></li>
                                <li><a href="">Clean Slider Template</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="aside-widget">
                        <header>
                            <h3>Tags</h3>
                        </header>
                        <div class="body clearfix">
                            <ul class="tags">
                            {% for tag in taglist %}
                                <li><a href="{{ mkUrl('Article.Lists', {tag:tag.id}) }}">{{ tag.name }}</a></li>
                            {% endfor %}
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
{% endblock %}
