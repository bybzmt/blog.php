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
                    <h2>{{ article.title }} </h2>
                    <h4>Posted on {{ article.addtime }} by <a href="{{ article.author_id }}">{{ article.author_name }}</a> </h4>
                    <p>
                        {{ article.intro }}
                    </p>
                    <a href="{{ article.id }}" class="btn btn-default btn-lg ">继续阅读</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span>
                        发表在 <a href="{{ article.classify_id }}">漫画</a>
                        {% if article.tags %}
                            &nbsp;|&nbsp;
                            标签有:
                            {% for tag in article.tags %}
                                <a href="{{ tag.id }}">{{ tag.name }}</a>
                                {% if not loop.last %} 、{% endif %}
                            {% endfor %}
                            &nbsp;|&nbsp;
                        {% else %}
                            &nbsp;|&nbsp;
                        {% endif %}

                        <a href="#">{{ article.comments_num }}&nbsp;条回复</a>
                    </span>
                </div>
            {% endfor %}

                <br />
                <nav>
                   <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                            <a href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3" style="padding-top: 30px;">
				<div class="row">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>分类</strong></li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                        <li class="list-group-item">Morbi leo risus</li>
                        <li class="list-group-item">Porta ac consectetur ac</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                        <li class="list-group-item">Dapibus ac facilisis in</li>
                        <li class="list-group-item">Morbi leo risus</li>
                        <li class="list-group-item">Porta ac consectetur ac</li>
                        <li class="list-group-item">Vestibulum at eros</li>
                    </ul>
				</div>
				<div class="row">
                    <h3>Advertising</h3>
				</div>
            </div>

        </div>


    </div>
{% endblock %}
