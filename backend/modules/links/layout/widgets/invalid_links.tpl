<div class="box" id="widgetLinksInvalidLinks">
	<div class="heading">
		<h3>{$lblLinks|ucfirst}: {$lblInactiveLinks|ucfirst}</a></h3>
	</div>

	{option:autodelete}
	<div class="options content">
		<p>{$msgAutodeleteIsActive|ucfirst}</p>
	</div>
	{/option:autodelete}

	{option:!autodelete}
		{option:invalidLinks}
		<div class="dataGridHolder">
			<table class="dataGrid">
				<tbody>
					{iteration:invalidLinks}
					<tr class="{cycle:'odd':'even'}">
						<td><a href="{$var|geturl:'edit':'links':'&id={$invalidLinks.id}'}">{$invalidLinks.title}</a></td>
						<td class="name">{$invalidLinks.protocol}{$invalidLinks.url}</td>
					</tr>
					{/iteration:invalidLinks}
				</tbody>
			</table>
		</div>
		{/option:invalidLinks}
		
		{option:!invalidLinks}
		<div class="options content">
			<p>{$msgNoInvalidLinks|ucfirst}</p>
		</div>
		{/option:!invalidLinks}
	{/option:!autodelete}
	
	<div class="footer">
		<div class="buttonHolderRight">
			<a href="{$var|geturl:'settings':'links'}" class="button"><span>{$lblSettings|ucfirst}</span></a>
		</div>
	</div>
</div>