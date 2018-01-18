{% extends "base.tpl" %}

{% block breadcrumb %}
    <a href="/">Blog</a>
    <span class="separator">&#x2F;</span>
    <a href="#">Bootstrap</a>
    <span class="separator">&#x2F;</span>
    <a href="#">HTML Template</a>
{% endblock %}

{% block title %}
    {{ article.title}}
{% endblock %}

{% block content %}
    <div class="widewrapper main">
        <div class="container">
            <div class="row">
                <div class="col-md-8 blog-main">
                    <article class="blog-post">

                        <div class="body">
                            <h1>{{ article.title}}</h1>
                            <div class="meta">
                                <i class="fa fa-user"></i> {{ author.nickname }}
                                <i class="fa fa-calendar"></i> {{ article.addtime | date('Y-m-d') }}
                                <i class="fa fa-comments"></i>
                                <span class="data"><a href="#comments"> {{ commentsNum }} Comments</a></span>
                            </div>

                            {{ article.content }}

                        </div>
                    </article>

<!--
                    <aside class="social-icons clearfix">
                        <h3>Share on </h3>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-google"></i></a>
                    </aside>
-->

                    <aside class="comments" id="comments">
                        <hr>

                        <h2><i class="fa fa-comments"></i> {{ commentsNum }} Comments</h2>

                        {% for comment in comments %}
                        <article class="comment">
                            <header class="clearfix">
                                <div class="meta">
                                    <h3><a href="#">{{ comment.user.nickname }}</a></h3>
                                    <span class="date">
                                        {{ comment.addtime | date('Y-m-d H:i') }}
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                    <a href="#create-comment" class="reply-link">Reply</a>
                                </div>
                            </header>
                             <div class="body">
                             {{ comment.content }}
                            </div>
                        </article>

                            {% for reply in comment.replys %}
                            <article class="comment reply">
                                <header class="clearfix">
                                    <div class="meta">
                                        <h3><a href="#">{{ reply.user.nickname }}</a></h3>
                                        <span class="date">
                                            {{ reply.addtime | date('Y-m-d H:i') }}
                                        </span>
                                        <span class="separator">
                                            -
                                        </span>

                                        <a href="#create-comment" class="reply-link">Reply</a>
                                    </div>
                                </header>
                                <div class="body">
                                {{ reply.content }}
                                </div>
                            </article>
                            {% endfor %}

                        {% endfor %}
                    </aside>

                    <aside class="create-comment" id="create-comment">
                        <hr>

                        <h2><i class="fa fa-pencil"></i> Add Comment</h2>

                        <form action="{{ mkUrl("Article.Comment") }}" method="post">
                            <input type="hidden" name="id" value="{{ article.id }}" />

                            <textarea {% if not uid %}disabled="disabled"{% endif %} rows="10" name="content" id="comment-body" placeholder="Your Message" class="form-control input-lg"></textarea>

                            <div class="buttons clearfix">
                                <button {% if not uid %}disabled="disabled"{% endif %} type="submit" class="btn btn-xlarge btn-clean-one">Submit</button>
                            </div>
                        </form>
                    </aside>
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

                    {% if taglist %}
                    <div class="aside-widget">
                        <header>
                            <h3>Tags</h3>
                        </header>
                        <div class="body clearfix">
                            <ul class="tags">
                                {% for tag in taglist %}
                                    <li><a href="{{ tag.url }}">{{ tag.name }}</a></li>
                                {% endfor %}
                            </ul>
                        </div>
                    </div>
                    {% endif %}
                </aside>
            </div>
        </div>
    </div>
{% endblock %}
