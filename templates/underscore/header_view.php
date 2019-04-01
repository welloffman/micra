<ul class="menu">
	<li class="logo"><a href="/">Logo</a></li>

	<% _.each(getMenu(), function(item) { %>
		<li><a class="js-route <%- isActive(item) %>" href="<%- item.route %>"><%- item.title %></a></li>
	<% }) %>

</ul>