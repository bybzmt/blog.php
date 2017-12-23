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
        <h4 class="navbar-text"><i class="fa fa-angle-right"></i> 文章管理</h4>
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
            </select>
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="search" value="{{ search_keyword }}" placeholder="keyword">
        </div>

        <div class="form-group">
            <label>状态:</label>
            <select class="form-control" name="status">
                <option {% if search_status == 0 %}selected="selected"{% endif %} value="1">不限</option>
                <option {% if search_status == 1 %}selected="selected"{% endif %} value="1">草稿</option>
                <option {% if search_status == 2 %}selected="selected"{% endif %} value="2">待审核</option>
                <option {% if search_status == 3 %}selected="selected"{% endif %} value="3">正式</option>
                <option {% if search_status == 4 %}selected="selected"{% endif %} value="4">下线</option>
            </select>
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
                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 作者</th>
                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> 标题</th>
                  <th><i class="fa fa-bookmark"></i> 发布时间</th>
                  <th><i class="fa fa-edit"></i> Status</th>
                  <th></th>
              </tr>
              </thead>
              <tbody>
              {% for article in articles %}
              <tr>
                  <td>{{article.id}}</td>
                  <td class="hidden-phone">{{article.author.nickname}}</td>
                  <td class="hidden-phone">{{article.title}}</td>
                  <td>{{ mymacro.date(article.addtime) }}</td>
                  <td>
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
                  </td>
                  <td>

<a href="{{ mkUrl("Blog.ArticleEdit", {id:article.id}) }}">
    <button title="审核" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
</a>
                  </td>
              </tr>
              {% endfor %}
              </tbody>
          </table>
      </div><!-- /content-panel -->
  </div><!-- /col-md-12 -->
</div><!-- /row -->

{% include "pagination.tpl" %}

{% endblock %}

{% block script %}
<script type="text/javascript">

</script>

{% endblock %}

