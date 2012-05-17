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
			<h3>{$categories.title|ucfirst}</h3>
				{option:categories.links}
					<ul>
						{iteration:categories.links}
							<li>
								<a href="{$categories.links.protocol}{$categories.links.url}" title="{$categories.links.description|ucfirst}"target="_blank">{$categories.links.title|ucfirst}</a>
							</li>
						{/iteration:categories.links}
					</ul>
				{/option:categories.links}
				{option:!categories.links}
					<p>{$msgLinksNoLinksInCategory|ucfirst}</p>
				{/option:!categories.links}
		{/iteration:categories}
	</div>	
{/option:categories}