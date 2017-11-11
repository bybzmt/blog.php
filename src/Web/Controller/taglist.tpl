<div class="row">
    <ul class="list-group">
        <li class="list-group-item"><strong>标签</strong></li>
        {% for tag in taglist %}
            <li class="list-group-item"><a href="{{ tag.url }}">{{ tag.name }}</a></li>
        {% endfor %}
    </ul>
</div>
