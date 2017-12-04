
{# 状态样式1 #}
{% macro status(state) %}
    {% if state == 0 %}
        <span class="label label-default label-mini">禁用</span>
    {% elseif state == 1 %}
        <span class="label label-success label-mini">正常</span>
    {% else %}
        <span class="label label-warning label-mini">异常</span>
    {% endif %}
{% endmacro %}

{# 编辑按钮 #}
{% macro editButton(url) %}
    <a href="{{ url }}">
        <button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button>
    </a>
{% endmacro %}

{# 删除按钮 #}
{% macro delButton(url) %}
    <a href="{{ url }}">
        <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o "></i></button>
    </a>
{% endmacro %}
