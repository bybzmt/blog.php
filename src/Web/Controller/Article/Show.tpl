{% extends "base.tpl" %}

{% block breadcrumb %}
    <a href="/">Blog</a>
    <span class="separator">&#x2F;</span>
    <span>Article</span>
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
                        <article class="comment" id="comment-{{ comment.id }}">
                            <header class="clearfix">
                                <div class="meta">
                                    <h3><a href="#">{{ comment.user.nickname }}</a></h3>
                                    <span class="date">
                                        {{ comment.addtime | date('Y-m-d H:i') }}
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                    <a onclick="reply({{ comment.id|json_encode }}, {{ comment.user.nickname|json_encode}} )" href="#create-comment" class="reply-link">Reply</a>
                                </div>
                            </header>
                             <div class="body">
                             {{ comment.content }}
                            </div>
                        </article>

                        {% if comment.replys %}
                        <aside class="comments" id="replys-{{ comment.id }}">
                            {% for reply in comment.replys %}
                            <article class="comment reply" id="reply-{{ reply.id }}">
                                <header class="clearfix">
                                    <div class="meta">
                                        <h3><a href="#">{{ reply.user.nickname }}</a></h3>
                                        <span class="date">
                                            {{ reply.addtime | date('Y-m-d H:i') }}
                                        </span>
                                        <span class="separator">
                                            -
                                        </span>

                                        <a onclick="reply({{ reply.id|json_encode }}, {{ reply.user.nickname|json_encode }})" href="#create-comment" class="reply-link">Reply</a>
                                    </div>
                                </header>
                                <div class="body">
                                {{ reply.content }}
                                </div>
                            </article>
                            {% endfor %}

                            {% if comment.replysMore %}
                            <div class="replysMore">
                                <a href="javascript:void()" onclick="replyPage({{comment.id}}, 2)">下一页</a>
                            </div>
                            {% endif %}
                        </aside>
                        {% endif %}

                        {% endfor %}
                    </aside>

                    {% include "pagination.tpl" %}

                    <aside class="create-comment" id="create-comment">
                        <hr>

                        <h2><i class="fa fa-pencil"></i> Add Comment</h2>

                        <form id="hid_form" onsubmit="dosubmit();return false;" action="{{ mkUrl("Article.Comment") }}" method="post">
                            <input type="hidden" name="id" value="{{ article.id }}" />
                            <input id="hid_reply" type="hidden" name="reply" value="0" />

                            <textarea id="hid_content" onchange="comment()" {% if not uid %}disabled="disabled"{% endif %} rows="10" name="content" id="comment-body" placeholder="Your Message" class="form-control input-lg"></textarea>

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
                                    <li><a href="{{ mkUrl('Article.Lists', {tag:tag.id}) }}">{{ tag.name }}</a></li>
                                {% endfor %}
                            </ul>
                        </div>
                    </div>
                    {% endif %}
                </aside>
            </div>
        </div>
    </div>

<div id="ResultModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">操作结果</h4>
      </div>
      <div class="modal-body"> 处理中... </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
{% endblock %}


{% block script %}
<script>
function replyPage(comment_id, page, fn)
{
    var url = "{{ mkUrl("Article.Replys", {article:article.id}) }}";
    $("#replys-"+comment_id).load(url + "&comment=" + comment_id + "&page=" + page);

    window.setTimeout(function(){
        if (fn) {
            fn()
        } else {
            $("#replys-" + comment_id).get(0).scrollIntoView();
        }
    }, 20);
}

$('#ResultModal').on('hidden.bs.modal', function (e) {
    location.reload(true);
});

function reply(id, nickname)
{
    document.getElementById("hid_reply").value=id;

    document.getElementById("hid_content").value="@" + nickname + " ";
}

function comment()
{
    var content = document.getElementById("hid_content").value;
    if (content.lenght < 1 || content[0] != "@") {
        document.getElementById("hid_reply").value=0;
    }
}

function dosubmit()
{
    $('#ResultModal').modal('show');

    var data = $("#hid_form").serialize();
    var url = $("#hid_form").attr("action");

    $.post(url, data, function(json){
        if (json.ret > 0) {
            $("#ResultModal .modal-body").html("<p class='alert alert-warning'>" + json.data + "</p>");
        } else {
            $("#ResultModal .modal-body").html("<p class='alert alert-success'>" + json.data + "</p>");
        }
    }, 'json');

    return false;
}

$(function(){
    var hash = location.hash;
    if (hash) {
        var prefix = "#torid=";
        if (hash.indexOf(prefix)==0) {
            var tmp = hash.substr(prefix.length).split(":");
            var comment_id = tmp[0];
            var comment_page = tmp[1];
            var reply_id = tmp[2];

            if (comment_page == "1") {
                $("#replys-" + reply_id).get(0).scrollIntoView();
            } else {
                //先要加载
                replyPage(comment_id, comment_page, function(){
                    $("#reply-" + reply_id).get(0).scrollIntoView();
                });
            }
        }
    }
});
</script>
{% endblock %}
