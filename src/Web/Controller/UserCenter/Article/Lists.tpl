{% extends "base.tpl" %}

{% block title %}Contact Me{% endblock %}

{% block breadcrumb %}
    <a href="{{ mkUrl("UserCenter.Records") }}">User</a>

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
                            <li><a href="{{ mkUrl("UserCenter.Records") }}">评论记录</a></li>
                            <li><a href="{{ mkUrl("UserCenter.Article.Lists") }}">文章列表</a></li>
                            <li><a href="{{ mkUrl("UserCenter.Article.Add") }}">发表文章</a></li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="col-md-10 blog-main">
                {% for article in articles %}
                    <article class="blog-list">
                        <header>
                            <h3><a href="{{ mkUrl("Article.Show", {id:article.id}) }}">{{ article.title }}</a></h3>

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

                                {% if article.locked %}
                                    <span class="text-success">(锁定)</span>
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

                            <a href="{{ mkUrl("UserCenter.Article.Preview", {id:article.id}) }}" class="btn btn-clean-one">预览</a>

                            {% if article.locked == 0 %}
                                <a href="{{ mkUrl("UserCenter.Article.Edit", {id:article.id}) }}" class="btn btn-clean-one">编辑</a>

                                {% if article.status == 1 %}
                                <a onclick="docmd('publish', {{article.id}})" class="btn btn-clean-one">发布</a>
                                <a onclick="docmd('delete', {{article.id}})" class="btn btn-clean-one">删除</a>
                                {% elseif article.status == 2 %}
                                <a onclick="docmd('cancel', {{article.id}})" class="btn btn-clean-one">撤回</a>
                                {% elseif article.status == 3 %}
                                <a onclick="docmd('cancel', {{article.id}})" class="btn btn-clean-one">撤回</a>
                                {% elseif article.status == 4 %}
                                <a onclick="docmd('publish', {{article.id}})" class="btn btn-clean-one">发布</a>
                                <a onclick="docmd('delete', {{article.id}})" class="btn btn-clean-one">删除</a>
                                {% endif %}
                            {% endif %}
                        </div>
                        <hr>
                    </article>
                {% endfor %}

                {% include "pagination.tpl" %}

            </div>

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
$('#ResultModal').on('hidden.bs.modal', function (e) {
    location.reload(true);
});
function docmd(cmd, id)
{
    $('#ResultModal').modal('show');

    var data = {cmd:cmd, id:id}
    var url = "{{ mkUrl("UserCenter.Article.Action") }}";

    $.post(url, data, function(json){
        if (json.ret > 0) {
            $("#ResultModal .modal-body").html("<p class='alert alert-warning'>" + json.data + "</p>");
        } else {
            $("#ResultModal .modal-body").html("<p class='alert alert-success'>" + json.data + "</p>");
        }
    }, 'json');

    return false;
}
</script>
{% endblock %}

