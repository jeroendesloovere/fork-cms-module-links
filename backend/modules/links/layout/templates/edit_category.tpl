{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:edit_category}
	<div class="pageTitle">
		<h2>{$lblLinks|ucfirst}: {$lblEditCategory|sprintf:{$title}}</h2>
	</div>

	<div class="box horizontal">
		<div class="heading">
			<h3>{$lblLinks|ucfirst}: {$lblEditCategory|sprintf:{$title}}</h3>
		</div>
		<div class="options">
			<p>
				<label for="name">{$lblCategory|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtTitle} {$txtTitleError}
			</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		{option:showDelete}
			<a href="{$var|geturl:'delete_category'}&amp;id={$id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
			<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
				<p>
					{$msgConfirmDeleteCategory|sprintf:{$title}}
				</p>
			</div>
		{/option:showDelete}
		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:edit_category}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}