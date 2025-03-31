{*
*	The MIT License (MIT)
*
*	Copyright (c) 2015-2023 Emmanuel MARICHAL
*
*	Permission is hereby granted, free of charge, to any person obtaining a copy
*	of this software and associated documentation files (the "Software"), to deal
*	in the Software without restriction, including without limitation the rights
*	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*	copies of the Software, and to permit persons to whom the Software is
*	furnished to do so, subject to the following conditions:
*
*	The above copyright notice and this permission notice shall be included in
*	all copies or substantial portions of the Software.
*
*	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
*	THE SOFTWARE.
*}

{if !isset($tags) || !is_array($tags)}
	{assign var='tags' value=array('tabs', 'panel', 'form', 'alert', 'table')}
{/if}

<script type="text/javascript">
	var color_picker = false;
</script>

{assign var="ps_version" value=$smarty.const._PS_VERSION_|string_format:"%.1f"}

{foreach from=$tags item=tag}
	{include file="./ps-$tag.tpl"}
{/foreach}

<script type="riot/tag">
	<raw>
		<span></span>

		// For some reason 'mount' is triggered multiple times, so use "before-mount' instead
		this.on('before-mount', function() {
			// We have to include the scripts at the end of the document, riot doesn't read them
			var content = $(opts.content)
			var inlineScript = content.filter("script[type='text/javascript']")
			content = content.not("script[type='text/javascript']")
			$(this.root).html(content)
			$(document.body).append(inlineScript);
		});
	</raw>
</script>

<script type="text/javascript">
	riot.mount('*');
	const mountPsTagsEvent = new Event('mountPsTags');
	document.dispatchEvent(mountPsTagsEvent);
</script>
