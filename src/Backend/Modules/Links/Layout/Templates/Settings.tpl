{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblModuleSettings|ucfirst}: {$lblLinks}</h2>
</div>

{form:settings}
<div class="box">
	<div class="heading">
		<h3>{$lblGeneral|ucfirst}</h3>
	</div>
	<div class="options">
			<ul class="inputList">
			<li><label for="autodelete">{$chkAutodelete}{$lblAutodelete|ucfirst}</label></li>
		</ul>
	</div>
</div>
<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}