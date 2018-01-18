{% extends "base.tpl" %}

{% block title %}Contact Me{% endblock %}

{% block breadcrumb %}
    <a href="{{ mkUrl("User.Show") }}">User</a>
{% endblock %}

{% block content %}
<div class="widewrapper main">
    <div class="container user">

        <article>
            <a href="{{ mkUrl("Article.Show", {id:1}) }}">
                <p>这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。</p>
                <blockquote>这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。</blockquote>
            </a>
        </article>
        <hr/>

        <article>
            <p>这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。</p>
            <blockquote>这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。</blockquote>
        </article>
        <hr/>

        <article>
            <p>这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。这里是我回复的内容。</p>
            <blockquote>这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。这里是被回复的博客或评论。</blockquote>
        </article>
        <hr/>

    </div>
</div>
{% endblock %}
