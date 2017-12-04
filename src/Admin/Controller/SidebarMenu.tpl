
<!-- sidebar menu start-->
<ul class="sidebar-menu" id="nav-accordion">

  <p class="centered"><a href="profile.html"><img src="/assets/img/ui-sam.jpg" class="img-circle" width="60"></a></p>
  <h5 class="centered">Marcel Newman</h5>

  <p class="mt"></p>

  {% for menu in menus %}
    {% if menu.childs %}
      <li class="sub-menu">
          <a href="javascript:;" {% if menu.active %}class="active"{% endif %} >
              <i class="fa {{menu.icons}}"></i>
              <span>{{menu.name}}</span>
          </a>
          <ul class="sub">
          {% for sub in menu.childs %}
              <li {% if sub.active %}class="active"{% endif %} >
                  <a  href="{{sub.href}}">{{sub.name}}</a>
              </li>
          {% endfor %}
          </ul>
      </li>
    {% else %}
      <li>
        <a href="{{menu.href}}" {% if menu.active %}class="active"{% endif %} >
            <i class="fa {{menu.icons}}"></i>
            <span>{{menu.name}}</span>
        </a>
      </li>
    {% endif %}
  {% endfor %}

</ul>
<!-- sidebar menu end-->
