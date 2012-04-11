{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:add_category}
	<div class="pageTitle">
		<h2>{$lblLinks|ucfirst}: {$lblAddCategory}</h2>
	</div>

	<div class="box horizontal">
		<div class="heading">
			<h3>{$lblLinks|ucfirst}: {$lblAddCategory}</h3>
		</div>
		<div class="options">
			<p>
				<label for="title">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtTitle} {$txtTitleError}
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		{option:showLinksAddCategory}
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddCategory|ucfirst}" />
		</div>
		{/option:showLinksAddCategory}
	</div>
{/form:add_category}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}