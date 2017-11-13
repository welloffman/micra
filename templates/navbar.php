<div class="row">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="/">База клиентов</a>
			</div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li <? if($active == 'index') { ?>class="active"<? } ?>><a href="/">Общая статистика</a></li>
					<li <? if($active == 'company-list') { ?>class="active"<? } ?>><a href="/company-list">Компании</a></li>
					<li <? if($active == 'client-list') { ?>class="active"<? } ?>><a href="/client-list">Клиенты</a></li>
				</ul>
			      
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/logout">Выход</a></li>
				</ul>
		    </div>
		</div>
	</nav>
</div>