{% extends "base.tpl" %}

{% import "macro.tpl" as mymacro %}

{% block head %}
    {{ parent() }}
{% endblock %}

{% block content %}
<h4 class="mt">
    <ol class="breadcrumb">
      <li><i class="fa fa-angle-right"></i> <a href="{{ mkUrl("Admin.Role.Lists") }}">角色管理</a></li>
      <li class="active">角色详情</li>
    </ol>
</h4>

<!-- BASIC FORM ELELEMNTS -->
<div class="row mt">
    <div class="col-md-12">
      <div class="form-panel">
          <form id="form" class="form-horizontal style-form" action="{{ mkUrl("Admin.Role.EditExec") }}">

            <input type="hidden" name="id" value="{{ role.id }}" />
              <div class="form-group">
                  <label class="col-md-2 control-label">角色名</label>
                  <div class="col-md-6">
                      <input type="text" class="form-control" name="name" value="{{ role.name }}" />
                  </div>
              </div>

            {% for name, group in permissions %}
              <div class="form-group">
                  <label class="col-md-2 control-label">{{ name }}</label>
                  <div class="col-md-10">
                      <div class="row">
                    {% for permission in group %}
                    <div class="col-md-3">
                    <label class="checkbox-inline">
                      <input type="checkbox" name="permissions[]"
                      {% if permission.status %}checked="checked"{% endif %}
                      value="{{ permission.permission }}" /> {{ permission.about }}
                    </label>
                    </div>
                    {% endfor %}
                    </div>
                  </div>
              </div>
              {% endfor %}

              <div class="form-group">
                  <label class="col-md-2 control-label"></label>
                  <div class="col-md-10">
                      <button type="button" class="btn btn-primary" onclick="doSubmit()">保回</button>
                      <button type="button" class="btn btn-default" onclick="history.back()">返回</button>
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
<script>
$('#ResultModal').on('hidden.bs.modal', function (e) {
    history.back();
});

function doSubmit()
{
    $('#ResultModal').modal('show');

    var data = $("#form").serialize();
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

