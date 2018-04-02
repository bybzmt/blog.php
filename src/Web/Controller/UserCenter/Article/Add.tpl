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
            <div class="col-md-10">
                <form id="hid_form" action="{{ mkUrl("UserCenter.Article.AddExec") }}" method="post" class="contact-form" onsubmit="return dosubmit()" />
                    <div class="col-md-12 form-group">
                        <label for="ipt-title">标题:</label>
                        <input type="text" id="ipt-title" name="title" placeholder="Title" class="form-control" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="ipt-tags">标签:</label>
                        <span class="text-muted">(TagA, TagB, ... TagN)</span>
                        <input type="text" id="ipt-tags" name="tags" class="form-control" />
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="ipt-intro">简介:</label>
                        <span class="text-muted">(无格式)</span>
                        <textarea id="ipt-intro" name="intro" class="form-control"></textarea>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="ipt-content">正文:</label>
                        <span class="text-muted">(Markdown格式)</span>
                        <textarea id="ipt-content" name="content" class="form-control" rows="20"></textarea>
                    </div>
                    <div class="col-md-12 form-group">
                        <button class="btn btn-default" type="submit">保存草稿</button>
                    </div>
                </form>
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
</script>
{% endblock %}
