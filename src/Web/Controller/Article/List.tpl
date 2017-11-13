{% extends "base.tpl" %}

{% block head %}
    <title>暴雨不在明天的博客</title>
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-main">

                {% for article in articles %}
                    {% if loop.index0 % 2 == 0 %}
                    <div class="row">
                    {% endif %}
                        <div class="col-md-6 col-sm-6">
                            <article class=" blog-teaser">
                                <header>
                                    <img src="/img/1.jpg" alt="{{ article.title }}">
                                    <h3><a href="{{ article.url }}">{{ article.title }}</a></h3>
                                    <span class="meta">{{ article.author_nickname }} {{ article.addtime | date('Y-m-d') }}</span>
                                    <hr>
                                </header>
                                <div class="body">
                                    {{ article.intro }}
                                </div>
                                <div class="clearfix">
                                    <a href="{{ article.url }}" class="btn btn-clean-one">Read more</a>
                                </div>
                            </article>
                        </div>
                    {% if loop.last or loop.index0 % 2 == 1 %}
                    </div>
                    {% endif %}
                {% endfor %}

                    <div class="paging">
                        <a href="#" class="older">Older Post</i></a>
                    </div>
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
                            <h3>Related Post</h3>
                        </header>
                        <div class="body">
                            <ul class="clean-list">
                                <li><a href="">Blackor Responsive Theme</a></li>
                                <li><a href="">Portfolio Bootstrap Template</a></li>
                                <li><a href="">Clean Slider Template</a></li>
                                <li><a href="">Clean - Responsive HTML5 Template</a></li>
                                <li><a href="">Responsive Pricing Table</a></li>
                                <li><a href="">Yellow HTML5 Template</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="aside-widget">
                        <header>
                            <h3>Tags</h3>
                        </header>
                        <div class="body clearfix">
                            <ul class="tags">
                                <li><a href="#">HTML5</a></li>
                                <li><a href="#">CSS3</a></li>
                                <li><a href="#">COMPONENTS</a></li>
                                <li><a href="#">TEMPLATE</a></li>
                                <li><a href="#">PLUGIN</a></li>
                                <li><a href="#">BOOTSTRAP</a></li>
                                <li><a href="#">TUTORIAL</a></li>
                                <li><a href="#">UI/UX</a></li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
{% endblock %}
