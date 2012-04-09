{*
	variables that are available:
	$links - array containing all the data about the links in the db.
		* $links holds the categories and $links.catlinks holds the links inside each category.
		
*}

{option:!links}
<div class="bd content">
	<p>{$msgLinksNoItems}</p>
</div>
{/option:!links}


{option:links}	
<div class="bd content">
	{iteration:links}
	
		<h3>{$links.title|ucfirst}</h3>
		{option:links.catlinks}
		
			<ul>
				{iteration:links.catlinks}
					<li><a href="{$links.catlinks.adress}" title="{$links.catlinks.description}" target="_blank">{$links.catlinks.title|ucfirst}</a></li>
				{/iteration:links.catlinks}
			</ul>
			
		{/option:links.catlinks}
		
		{option:!links.catlinks}
		
		<p>{$msgLinksNoLinksInCategory}</p>
			
		{/option:!links.catlinks}
		
	{/iteration:links}
</div>	
{/option:links}
					
					