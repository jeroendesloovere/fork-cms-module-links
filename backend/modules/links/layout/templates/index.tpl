{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblLinks|ucfirst}: {$lblLinks}</h2>
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add'}" class="button icon iconAdd" title="{$lblAdd|ucfirst}">
			<span>{$lblAddLink|ucfirst}</span>
		</a>
	</div>
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
	<p>{$msgNoLinks}</p>
{/option:!dataGrids}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}