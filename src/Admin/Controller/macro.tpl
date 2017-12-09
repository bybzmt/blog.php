
{# 显示时间 #}
{% macro date(time) %}
    <span title="{{ time | date("Y-m-d H:i:s") }}">{{ time | date("Y-m-d") }}
{% endmacro %}

{# 编辑按钮 #}
{% macro editButton(url) %}
    <a href="{{ url }}" title="编辑">
        <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
    </a>
{% endmacro %}

{# 删除按钮 #}
{% macro delButton(url) %}
    <a href="{{ url }}" title="删除">
        <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></button>
    </a>
{% endmacro %}
