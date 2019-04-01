<ul class="menu">
	<li class="logo"><a href="https://nafi.ru/"><img src="/img/logo_nafi.svg" height="100" /></a></li>

	<% _.each(getMenu(), function(item) { %>
		<li><a class="js-route <%- isActive(item) %>" href="<%- item.route %>"><%- item.title %></a></li>
	<% }) %>

	<% if(user.isGuest()) { %>
		<!-- <li class="right-item"><a class="js-route" href="/login"><i class="fal fa-sign-in"></i> Личный кабинет</a></li> -->
	<% } else { %>
		<li class="right-item">
			<div class="logout">
				<a class="js-logout" href="#"><i class="fal fa-sign-out"></i> Выход</a>
			</div>
			<a class="js-route" href="<%- profileLink %>">
				<i class="fas fa-user"></i> <span class="user-name"><%- name %></span>
			</a>
			<% if(rating !== undefined) { %>
				<div class="rating">
					Ваш рейтинг: <%- rating %>
				</div>
			<% } %>
		</li>
	<% } %>
</ul>