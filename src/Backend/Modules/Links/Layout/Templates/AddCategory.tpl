{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}

<div class="pageTitle">
	<h2>{$lblLinks|ucfirst}: {$lblAddCategory}</h2>
</div>
{form:add_category}
		<div class="ui-tabs">
			<div class="ui-tabs-panel">
				<div class="options">
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td id="leftColumn">
								<div class="box">
									<div class="heading">
										<h3>
											<label for="text">{$lblImage|ucfirst}</label>
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
			{option:showLinksAddCategory}
			<div class="buttonHolderRight">
				<input id="addButton" class="inputButton button mainButton" type="submit" name="add" value="{$lblSave|ucfirst}" />
			</div>
			{/option:showLinksAddCategory}
		</div>
{/form:add_category}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}