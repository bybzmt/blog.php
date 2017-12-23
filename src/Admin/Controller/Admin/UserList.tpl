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
        <h4 class="navbar-text"><i class="fa fa-angle-right"></i> 管理员管理</h4>
    </div>
    <div class="col-md-10">
    <form class="navbar-form navbar-right" role="search">
        <div class="form-group">
            <select class="form-control" name="type">
                <option {% if search_type == 1 %}selected="selected"{% endif %} value="1">ID</option>
                <option {% if search_type == 2 %}selected="selected"{% endif %} value="2">用户名</option>
                <option {% if search_type == 3 %}selected="selected"{% endif %} value="3">呢称</option>
            </select>
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="search" value="{{ search_keyword }}" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>

        <a target="_blank" href="{{ mkUrl("Admin.Register") }}">
            <button type="button" class="btn btn-default">添加</button>
        </a>
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
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 用户名</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 呢称</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 归属角色</th>
                                  <th><i class="fa fa-clock-o"></i> 添加时间</th>
                                  <th><i class="fa fa-edit"></i> Status</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              {% for user in users %}
                              <tr>
                                  <td>{{user.id}}</td>
                                  <td class="hidden-phone">{{user.user}}</td>
                                  <td class="hidden-phone">{{user.nickname}}</td>
                                  <td>
                                  {% if user.isroot %}
                                      <span>系统</span>
                                  {% else %}
                                      {% for role in user.getRoles %}
                                        <span>{{ role.name }}</span>
                                      {% endfor %}
                                  {% endif %}
                                  </td>
                                  <td>{{ mymacro.date(user.addtime) }}</td>
                                  <td>
                                        {% if user.status == 1 %}
                                            <span class="label label-default label-mini">待审核</span>
                                        {% elseif user.status == 2 %}
                                            <span class="label label-success label-mini">正常</span>
                                        {% else %}
                                            <span class="label label-danger label-mini">异常</span>
                                        {% endif %}
                                  </td>
                                  <td>
                                    {{ mymacro.editButton(mkUrl("Admin.UserEdit", {id:user.id})) }}

                                        {% if user.status == 1 %}

        <button title="审核" onclick="auditPanel({{ user.id|json_encode }}, {{ user.nickname|json_encode }})" class="btn btn-danger btn-xs"><i class="fa fa-wrench"></i></button>
                                        {% else %}

        <button title="删除" onclick="delPanel({{ user.id|json_encode }}, {{ user.nickname|json_encode }})" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                                        {% endif %}

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
        <button type="button" onclick="doAudit(1)" class="btn btn-danger" data-dismiss="modal">审核通过</button>
        <button type="button" onclick="doAudit(0)" class="btn btn-danger" data-dismiss="modal">拒绝并删除帐户</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">取消</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="UserDelModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">请确认！</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" onclick="doUserDel()" class="btn btn-danger" data-dismiss="modal">删除</button>
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
function auditPanel(id, name)
{
    audit_id = id;

    $("#AuditModal .modal-body").html('<p class="alert alert-danger">您确认管理员:"'+name+'"是否通过审核！</p>');

    $('#AuditModal').modal('show');
}

function doAudit(flag)
{
    $('#AuditModal').modal('hide');

    $('#ResultModal').modal('show');

    var url = "{{ mkUrl("Admin.UserAuditExec") }}";
    var data = {id:audit_id, flag:flag};

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

var del_id = 0;
function delPanel(id, nickname)
{
    del_id = id;

    $("#UserDelModal .modal-body").html('<p class="alert alert-danger">您确定要删除管理员:"'+nickname+'"吗！</p>');

    $('#UserDelModal').modal('show');
}

function doUserDel()
{
    $('#UserDelModal').modal('hide');

    $('#ResultModal').modal('show');

    var url = "{{ mkUrl("Admin.UserDelExec") }}";
    var data = {id:del_id};

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

