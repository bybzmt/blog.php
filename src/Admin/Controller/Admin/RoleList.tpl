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
    <div class="col-md-12">
        <div class="content-panel">
            <table class="table table-striped table-advance table-hover">
                <div class="row">
                    <div class="col-md-10">
                        <h4><i class="fa fa-angle-right"></i> 角色管理</h4>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ mkUrl("Admin.RoleAdd") }}">
                            <button type="button" class="btn btn-info">添加</button>
                        </a>
                    </div>
                </div>
                <hr>
                <thead>
                    <tr>
                        <th><i class="fa fa-bullhorn"></i> ID</th>
                        <th class="hidden-phone"><i class="fa fa-question-circle"></i> 名称</th>
                        <th><i class="fa fa-bookmark"></i> 添加时间</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {% for role in roles %}
                    <tr>
                      <td>{{role.id}}</td>
                      <td class="hidden-phone">{{role.name}}</td>
                      <td>{{ mymacro.date(role.addtime) }}</td>
                      <td>
                        {{ mymacro.editButton(mkUrl("Admin.RoleEdit", {id:role.id})) }}
        <button title="删除" onclick="delPanel({{ role.id|json_encode }}, {{ role.name|json_encode }})" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div><!-- /content-panel -->
    </div><!-- /col-md-12 -->
</div><!-- /row -->

<div id="RoleDelModal" class="modal fade" tabindex="-1" role="dialog">
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

var del_id = 0;
function delPanel(id, name)
{
    del_id = id;

    $("#RoleDelModal .modal-body").html('<p class="alert alert-danger">您确定要删除角色:"'+name+'"吗！</p>');

    $('#RoleDelModal').modal('show');
}

function doUserDel()
{
    $('#RoleDelModal').modal('hide');

    $('#ResultModal').modal('show');

    var url = "{{ mkUrl("Admin.RoleDelExec") }}";
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

