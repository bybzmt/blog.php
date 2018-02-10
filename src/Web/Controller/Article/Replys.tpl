
{% for reply in replys %}
<article class="comment reply" id="reply-{{ reply.id }}">
    <header class="clearfix">
        <div class="meta">
            <h3><a href="#">{{ reply.user.nickname }}</a></h3>
            <span class="date">
                {{ reply.addtime | date('Y-m-d H:i') }}
            </span>
            <span class="separator">
                -
            </span>

            <a onclick="reply({{ reply.id|json_encode }}, {{ reply.user.nickname|json_encode }})" href="#create-comment" class="reply-link">Reply</a>
        </div>
    </header>
    <div class="body">
    {{ reply.content }}
    </div>
</article>
{% endfor %}

<div class="replysMore">
{% if pagePrev %}
    <a href="javascript:void()" onclick="replyPage({{comment_id}}, {{pagePrev}})">上一页</a>
{% endif %}
{% if pageNext %}
    <a href="javascript:void()" onclick="replyPage({{comment_id}}, {{pageNext}})">下一页</a>
{% endif %}
</div>

