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
	                  	  	  <h4><i class="fa fa-angle-right"></i> 角色管理</h4>
	                  	  	  <hr>
                              <thead>
                              <tr>
                                  <th><i class="fa fa-bullhorn"></i> ID</th>
                                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 名称</th>
                                  <th><i class="fa fa-bookmark"></i> 添加时间</th>
                                  <th><i class=" fa fa-edit"></i> Status</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                              {% for role in roles %}
                              <tr>
                                  <td>{{role.id}}</td>
                                  <td class="hidden-phone">{{role.name}}</td>
                                  <td>{{role.addtime|date('Y-m-d H:i:s')}}</td>
                                  <td>{{mymacro.status(role.status)}}</td>
                                  <td>
                                    {{ mymacro.editButton(mkUrl("Admin.RoleEdit", {id:role.id})) }}
                                    {{ mymacro.delButton(mkUrl("Admin.RoleDel", {id:role.id})) }}
                                  </td>
                              </tr>
                              {% endfor %}
                              </tbody>
                          </table>
                      </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              </div><!-- /row -->

{% endblock %}

{% block script %}

{% endblock %}

