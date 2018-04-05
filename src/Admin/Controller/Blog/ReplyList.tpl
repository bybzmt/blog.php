{% extends "base.tpl" %}

{% import "macro.tpl" as mymacro %}

{% block head %}
    {{ parent() }}

    <link rel="stylesheet" type="text/css" href="/assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="/assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="/assets/lineicons/style.css">

    <script src="/assets/js/chart-master/Chart.js"></script>
{% endblock %}

{% block content %}

<div class="row mt">
    <div class="col-md-2">
        <h4 class="navbar-text"><i class="fa fa-angle-right"></i> 评论管理</h4>
    </div>
    <div class="col-md-10">
    <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
            <select class="form-control" name="type">
                <option {% if search_type == 1 %}selected="selected"{% endif %} value="1">用户ID</option>
                <option {% if search_type == 2 %}selected="selected"{% endif %} value="2">用户名</option>
                <option {% if search_type == 3 %}selected="selected"{% endif %} value="3">用户呢称</option>
                <option {% if search_type == 4 %}selected="selected"{% endif %} value="4">文章ID</option>
                <option {% if search_type == 5 %}selected="selected"{% endif %} value="5">文章标题</option>
                <option {% if search_type == 6 %}selected="selected"{% endif %} value="6">评论内容</option>
            </select>
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="search" value="{{ search_keyword }}" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    </div>
</div>



<div class="row">
  <div class="col-md-12">
      <div class="content-panel">
          <table class="table table-striped table-advance table-hover">
              <thead>
              <tr>
                  <th><i class="fa fa-bullhorn"></i> ID</th>
                  <th><i class="fa fa-question-circle"></i> 文章</th>
                  <th><i class="fa fa-question-circle"></i> 用户</th>
                  <th><i class="fa fa-question-circle"></i> 评论内容</th>
                  <th><i class="fa fa-clock-o"></i> 发表时间</th>
                  <th></th>
              </tr>
              </thead>
              <tbody>
              {% for comment in comments %}
              <tr>
                  <td>{{comment.id}}</td>
                  <td class="hidden-phone">{{comment.article.title}}</td>
                  <td class="hidden-phone">{{comment.user.nickname}}</td>
                  <td>{{ comment.content }}</td>
                  <td>{{ mymacro.date(comment.addtime) }}</td>
                  <td>
<button title="删除" onclick="auditPanel({{ "#{comment.article_id}:#{comment.id}"|json_encode }})" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                  </td>
              </tr>
              {% endfor %}
              </tbody>
          </table>
      </div><!-- /content-panel -->
  </div><!-- /col-md-12 -->
</div><!-- /row -->

{% include "pagination.tpl" %}

<div id="AuditModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">请确认！</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="doAudit()" class="btn btn-danger" data-dismiss="modal">确定</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">取消</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
<script type="text/javascript">
$('#ResultModal').on('hidden.bs.modal', function (e) {
    location.reload(true);
});

var audit_id=0;
function auditPanel(id)
{
    audit_id = id;

    msg = "您确认删除评论 ID:" + id + " 吗？";

    $("#AuditModal .modal-body").html('<p class="alert alert-danger">'+msg+'</p>');

    $('#AuditModal').modal('show');
}

function doAudit()
{
    $('#AuditModal').modal('hide');

    $('#ResultModal').modal('show');

    var url = "{{ mkUrl("Blog.CommentAuditExec") }}";

    var data = {id:audit_id, flag:0};

    $.post(url, data, function(json){
        if (json.ret > 0) {
            if (json.ret == 3) {
                $("#ResultModal .modal-body").html("<pre class='alert alert-danger'>" + json.data + "</pre>");
            } else {
                $("#ResultModal .modal-body").html("<h2 class='alert alert-warning'>" + json.data + "</h2>");
            }
        } else {
            $("#ResultModal .modal-body").html("<h2 class='alert alert-success'>" + json.data + "</h2>");
        }
    }, 'json');
}

</script>

{% endblock %}

