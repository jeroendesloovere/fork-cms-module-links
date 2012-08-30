{*
	variables that are available:
	-$categories - array containing all the data about the links in the db.
	-$links holds the categories and $categories.links holds the links inside each category.	
*}

{option:!categories}
	<div class="bd content">
		<p>{$msgLinksNoItems|ucfirst}</p>
	</div>
{/option:!categories}

{option:categories}	
	<div class="bd content">
		{iteration:categories}
		<div class="clearfix">
			<h3>{$categories.title|ucfirst}</h3>
			
			{option:categories.links}
			{option:categories.logo}
				<div class="logo">
					<img src="{$FRONTEND_FILES_URL}/links/images/128x128/{$categories.logo}" class="logo" alt="{$categories.title}"/>
				</div>
			{/option:categories.logo}
				<div class="links">
					<ul>
					{iteration:categories.links}
						<li>
							<a href="{$categories.links.url}" title="{$categories.links.description|ucfirst}" onclick="_gaq.push(['_trackEvent', 'Outbound Links', 'Click', '{$categories.links.title|ucfirst}']);">{$categories.links.title|ucfirst}</a>
						</li>
					{/iteration:categories.links}
					</ul>
				</div>
				{/option:categories.links}
		</div>
				{option:!categories.links}
					<p>{$msgLinksNoLinksInCategory|ucfirst}</p>
				{/option:!categories.links}
		
		{/iteration:categories}
	</div>
{/option:categories}