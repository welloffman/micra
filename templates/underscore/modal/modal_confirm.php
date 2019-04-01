<div class="modal-dialog modal-dialog-centered <%- sizeClass %>" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel"><%- title %></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<% if(type != 'custom') { %>
			<div class="modal-body">
				<p><%- text %></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-yellow js-apply"><%- applyButtonText %></button>
				<% if(type != 'info') { %>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				<% } %>
			</div>
		<% } %>

	</div>
</div>