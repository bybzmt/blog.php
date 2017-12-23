<nav aria-label="Page navigation">
   <ul class="pagination">
        {% if pagination.previous.disabled %}
            <li class="disabled">
                <a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
            </li>
        {% else %}
            <li>
                <a href="{{ pagination.previous.url }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        {% endif %}

        {% for page in pagination.pages %}
            {% if page.active %}
                <li class="active"><a>{{ page.page }}</a></li>
            {% elseif page.disabled %}
                <li class="disabled"><a>{{ page.page }}</a></li>
            {% else %}
                <li> <a  href="{{ page.url }}">{{ page.page }}</a></li>
            {% endif %}
        {% endfor %}

        {% if pagination.next.disabled %}
            <li class="disabled">
                <a aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
            </li>
        {% else %}
            <li>
                <a href="{{ pagination.next.url }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        {% endif %}
    </ul>
</nav>
