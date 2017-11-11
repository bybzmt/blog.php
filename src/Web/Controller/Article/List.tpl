{% extends "base.tpl" %}

{% block title %}Index{% endblock %}

{% block head %}
    {{ parent() }}
{% endblock %}

{% block content %}
   <div class="container">
        <div class="row">

            <div class="col-md-8 ">
            {% for article in article_list %}
                <div class="blog-post">
                    <h2><a href="{{ article.url }}">{{ article.title }}</a></h2>
                    <h4>Posted on {{ article.addtime | date('Y-m-d') }} by <a href="{{ article.author_url }}">{{ article.author_nickname }}</a> </h4>
                    <p>
                        {{ article.intro }}
                    </p>
                    <a href="{{ article.url }}" class="btn btn-default btn-lg ">继续阅读</a>
                    &nbsp;&nbsp;
                    <span>
                        {% if article.tags %}
                            &nbsp;|&nbsp;
                            标签有:
                            {% for tag in article.tags %}
                                <a href="{{ tag.url }}">{{ tag.name }}</a>
                                {% if not loop.last %} 、{% endif %}
                            {% endfor %}
                            &nbsp;|&nbsp;
                        {% else %}
                            &nbsp;|&nbsp;
                        {% endif %}

                        <a href="{{ article.url }}#comments">{{ article.comments_num }}&nbsp;条回复</a>
                    </span>
                </div>
            {% endfor %}

                {% include "pagination.tpl" %}

            </div>

            <div class="col-md-1"></div>

            <div class="col-md-3" style="padding-top: 30px;">
                {% include "taglist.tpl" %}
            </div>

        </div>
    </div>
{% endblock %}
