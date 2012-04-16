{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblLinks|ucfirst}: {$item.title}</h2>
</div>

{form:edit}
	{option:categories}
	<div class="ui-tabs">
		<div class="ui-tabs-panel">
			<div class="options">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td id="leftColumn">
							<p>       
                            {$lblTitle|ucfirst}<br/>
							{$txtTitle} {$txtTitleError}
							</p>
							
							<p>       
                            {$lblUrl|ucfirst}<br/>
							{$txtUrl} {$txtUrlError}
							<p> {$lblUrl|ucfirst}<br />
								{$ddmProtocol}{$txtUrl}
								{$ddmProtocolError}{$txtUrlError}
							</p>
							<p>
								{$lblDescription|ucfirst}<br />
								{$txtDescription} {$txtDescriptionError}
							</p>
						</td>

						<td id="sidebar">

							<div id="linksCategory" class="box">
								<div class="heading">
									<h3>{$lblCategory|ucfirst}</h3>
								</div>

								<div class="options">
									<p>
										{$ddmCategories} {$ddmCategoriesError}
									</p>
								</div>
							</div>

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

		<div class="fullwidthOptions">
		{option:showLinksDelete}
			<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
				<span>{$lblDelete|ucfirst}</span>
			</a>
			
			<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
				<p>
					{$msgConfirmDeleteLink|sprintf:{$item.title}}
				</p>
			</div>
		{/option:showLinksDelete}
			<div class="buttonHolderRight">
				<input id="editButton" class="inputButton button mainButton" type="submit" name="edit" value="{$lblPublish|ucfirst}" />
			</div>
		</div>
		
		
		
	{/option:categories}

{/form:edit}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}