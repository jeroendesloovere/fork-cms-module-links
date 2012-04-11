{*
	variables that are available:
	- {$category}	: contains an array with the values of the selected category.
	- {$links}		: contains an array with all the links of the selected category.
*}

{option:!category}
<div class="bd content">
	<p>{$msgLinksNoItems}</p>
</div>
{/option:!category}

{option:category}
	<div class="bd content">
		{option:!links}
			<p>{$msgLinksNoItems}</p>
		{/option:!links}
		{option:links}
			<h3>{$category.title|ucfirst}</h3>
				<ul>
					{iteration:links}
						<li><a href="{$links.url}" title="{$links.description}" target="_blank">{$links.title|ucfirst}</a></li>
					{/iteration:links}
				</ul>
		{/option:links}
	</div>	
{/option:category}