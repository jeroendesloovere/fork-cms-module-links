{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}
<div class="pageTitle">
	<h2>{$lblLinks|ucfirst}: {$lblEditCategory}</h2>
</div>
{form:edit_category}
<div class="ui-tabs">
	<div class="ui-tabs-panel">
		<div class="options">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td id="leftColumn">
						<div class="box">
							<div class="heading">
								<h3>
									<label for="text">{$lblCategory|ucfirst}</label>
								</h3>
							</div>
							<div class="options clearfix">
								<p>
									<label for="title">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
									{$txtTitle} {$txtTitleError}
								</p>
							</div>
						</div>
						{option:isGod}
						<div class="box">
							<div class="heading">
								<h3>
									<label for="text">{$lblImage|ucfirst}</label>
								</h3>
							</div>
							<div class="options clearfix">
								<p class="imageHolder">
									{option:category.logo}
										<img src="{$FRONTEND_FILES_URL}/links/images/128x128/{$category.logo}" />
										<label for="deleteImage">{$chkDeleteLogo} {$lblDelete|ucfirst}</label>
									{$chkDeleteLogoError}
									{/option:category.logo}
								</p>
								<p>
									<label for="logo">{$lblLogo|ucfirst}</label>
									{$fileLogo}{$fileLogoError}
								</p>
							</div>	
						</div>
						{/option:isGod}
					</td>
					<td id="sidebar">
						<div id="publishOptions" class="box">
							<div class="heading">
								<h3>{$lblStatus|ucfirst}</h3>
							</div>
							<div class="options">
								<ul class="inputList">
									{iteration:hidden}
										<li>
											{$hidden.rbtHidden}
											<label for="{$hidden.id}">{$hidden.label}</label>
										</li>
									{/iteration:hidden}
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="fullwidthOptions">
		{option:showLinksDeleteCategory}
			<a href="{$var|geturl:'delete_category'}&amp;id={$category.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
			<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
				<p>
					{$msgConfirmDeleteCategory|sprintf:{$category.title}|ucfirst}
				</p>
			</div>
		{/option:showLinksDeleteCategory}
		
		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:edit_category}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}