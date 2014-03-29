{*
	variables that are available:
	- {$category}: contains an array with the values of the selected category.
	- {$links}: contains an array with all the links of the selected category.
*}
<section id="linksWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$category.title|ucfirst}</h3>
		</header>
		<div class="bd content">
			{option:category}
				{option:!links}			
					<p>{$msgLinksNoItems|ucfirst}</p>
				{/option:!links}
				{option:links}
					<ul>
						{iteration:links}
							<li><a href="{$links.url}" title="{$links.description|ucfirst}" onclick="_gaq.push(['_trackEvent', 'Outbound Links', 'Click', '{$links.title|ucfirst}']);">{$links.title|ucfirst}</a></li>
						{/iteration:links}
					</ul>
				{/option:links}		
			{/option:category}
			{option:!category}
				<p>{$msgLinksNoItems|ucfirst}</p>
			{/option:!category}
		</div>
	</div>
</section>
