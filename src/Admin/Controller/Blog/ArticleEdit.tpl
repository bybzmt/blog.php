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

<h4 class="mt">
    <ol class="breadcrumb">
      <li><i class="fa fa-angle-right"></i> <a href="{{ mkUrl("Blog.ArticleList") }}">文章管理</a></li>
      <li class="active">文章详情</li>
    </ol>
</h4>


<div class="row mt">
    <div class="col-md-12">
      <div class="form-panel">
          <form id="form" class="form-horizontal style-form" action="{{ mkUrl("Blog.ArticleAuditExec") }}">

            <input type="hidden" name="id" value="{{ article.id }}" />

              <div class="form-group">
                  <label class="col-md-1 control-label">标题</label>
                  <div class="col-md-11">
                    {{ article.title }}
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-md-1 control-label">作者</label>
                  <div class="col-md-3">
                    {{ article.author.nickname }}
                  </div>

                  <label class="col-md-1 control-label">状态</label>
                  <div class="col-md-3">
                    {% if article.status == 1 %}
                        <span class="label label-default label-mini">草稿</span>
                    {% elseif article.status == 2 %}
                        <span class="label label-warning label-mini">审核</span>
                    {% elseif article.status == 3 %}
                        <span class="label label-success label-mini">正式</span>
                    {% elseif article.status == 4 %}
                        <span class="label label-default label-mini">下线</span>
                    {% else %}
                        <span class="label label-danger label-mini">异常</span>
                    {% endif %}

                    {% if article.locked %}
                        <span class="label label-warning label-mini">锁定</span>
                    {% endif %}
                  </div>

                  <label class="col-md-1 control-label">发布时间</label>
                  <div class="col-md-3">
                    {{ article.addtime|date('Y-m-d H:i:s') }}
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-md-1 control-label">简介</label>
                  <div class="col-md-11">
                      {{ article.intro }}
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-md-1 control-label">内容</label>
                  <div class="col-md-11">
                      {{ article.content }}
                  </div>
              </div>

              <div class="form-group">
                  <div class="col-md-12 text-center">
                      {% if article.status != 3 %}
                          <button type="button" class="btn btn-warning" onclick="doSubmit(1)">通过审核</button>
                      {% else %}
                          <button type="button" class="btn btn-warning" onclick="doSubmit(2)">下线处理</button>
                      {% endif %}

                      {% if article.locked %}
                          <button type="button" class="btn btn-warning" onclick="doSubmit(3)">解除锁定</button>
                      {% else %}
                          <button type="button" class="btn btn-warning" onclick="doSubmit(4)">锁定文章</button>
                      {% endif %}

                      <button type="button" class="btn btn-danger" onclick="doSubmit(5)">删除</button>
                      <button type="button" class="btn btn-primary" onclick="history.back()">返回</button>
                  </div>
              </div>
          </form>
      </div>
    </div><!-- col-lg-12-->
</div><!-- /row -->

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
    history.back();
});

function doSubmit(type)
{
    $('#ResultModal').modal('show');

    var data = $("#form").serialize();
    data += "&type="+type;

    var url = $("#form").attr("action");

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

