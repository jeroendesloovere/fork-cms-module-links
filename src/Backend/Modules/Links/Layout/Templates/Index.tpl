{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="pageTitle">
	<h2>{$lblLinks|ucfirst}: {$lblLinks}</h2>
	{option:showLinksAdd}
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">
			<span>{$lblAdd|ucfirst}</span>
		</a>
	</div>
	{/option:showLinksAdd}
</div>
<div id="dataGridModelsHolder">
	{option:dataGrids}
		{iteration:dataGrids}
			<div class="dataGridHolder" id="{$dataGrids.id}">
			<h3>{$dataGrids.catname|ucfirst}</h3>	
				{option:dataGrids.content}
					{$dataGrids.content}
				{/option:dataGrids.content}
				{option:!dataGrids.content}
					{$emptyDatagrid}
				{/option:!dataGrids.content}
			</div>
		{/iteration:dataGrids}
	{/option:dataGrids}
</div>
{option:!dataGrids}
	<p>{$msgNoLinks|ucfirst}</p>
{/option:!dataGrids}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}