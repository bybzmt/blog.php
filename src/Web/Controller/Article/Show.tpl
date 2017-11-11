{% extends "base.tpl" %}

{% block title %}Index{% endblock %}

{% block head %}
    {{ parent() }}
{% endblock %}

{% block content %}
   <div class="container">
        <div class="row">

            <div class="col-md-8 ">
                <h2>{{ article.title }}</h2>
                <h4>
                    <a href="{{ article.author_url }}">{{ article.author_nickname }}</a>
                    发表于 {{ article.addtime | date('Y-m-d') }}
                    {% if article.addtime != article.edittime %}
                    最后修改于 {{ article.edittime | date('Y-m-d') }}
                    {% endif %}
                </h4>
                <div>
                {{ article.content }}
                </div>

            </div>

            <div class="col-md-1"></div>

            <div class="col-md-3" style="padding-top: 30px;">
                {% include "taglist.tpl" %}
            </div>

        </div>
    </div>
{% endblock %}
