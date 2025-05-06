/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// %REMOVE_START%
	config.plugins =
		// 'about,' +
		'a11yhelp,' +
		'basicstyles,' +
		'bidi,' +
		'blockquote,' +
		'clipboard,' +
		'colorbutton,' +
		'colordialog,' +
		'copyformatting,' +
		'contextmenu,' +
		'dialogadvtab,' +
		// 'div,' +
		'elementspath,' +
		'enterkey,' +
		'entities,' +
		'filebrowser,' +
		// 'find,' +
		'floatingspace,' +
		'font,' +
		'format,' +
		// 'forms,' +
		'horizontalrule,' +
		'htmlwriter,' +
		'image,' +
		// 'iframe,' +
		'indentlist,' +
		'indentblock,' +
		'justify,' +
		// 'language,' +
		'link,' +
		'list,' +
		'liststyle,' +
		'magicline,' +
		'maximize,' +
		// 'newpage,' +
		'pagebreak,' +
		// 'pastefromgdocs,' +
		// 'pastefromlibreoffice,' +
		// 'pastefromword,' +
		'pastetext,' +
		'editorplaceholder,' +
		// 'preview,' +
		// 'print,' +
		'removeformat,' +
		'resize,' +
		// 'save,' +
		'selectall,' +
		'showblocks,' +
		'showborders,' +
		'smiley,' +
		'sourcearea,' +
		'specialchar,' +
		'stylescombo,' +
		'tab,' +
		'table,' +
		'tableselection,' +
		'tabletools,' +
		// 'templates,' +
		'toolbar,' +
		'undo,' +
		'uploadimage,' +
		'wysiwygarea';
	// %REMOVE_END%

    // 使用繁體中文語系（你可根據需要切換 zh-cn 或 en）
    config.language = 'zh';

    // Toolbar 設定（可依需求調整）
    config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'styles' },
        { name: 'tools' },
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        '/',
        { name: 'colors' },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'links' },
        { name: 'insert' }
    ];

    // 移除進階 tab
    config.removeDialogTabs = 'image:advanced;link:advanced';

    // ✅ 僅允許安全標籤（白名單）
    config.allowedContent = {
        $1: {
            // Elements allowed
            elements: 'p h1 h2 h3 h4 h5 h6 blockquote strong em u s sub sup ul ol li br span div img a table thead tbody tr th td hr',
            // Attributes allowed
            attributes: {
                a: 'href,target,name',
                img: 'src,alt,width,height',
                '*': 'style,class,align'
            },
            // Styles allowed
            styles: {
                '*': 'color,font-size,text-align,float,margin-left,margin-right,width,height'
            },
            // Classes allowed (can be empty)
            classes: true
        }
    };

    // 若你想允許所有標籤，可以改成 true（不建議）
    // config.allowedContent = true;
};


// ✅ 在貼上/拖曳時阻擋以下危險元素（防止 XSS、廣告、非法嵌入）
CKEDITOR.on('instanceReady', function(evt) {
    var editor = evt.editor;
    editor.dataProcessor.htmlFilter.addRules({
        elements: {
            form: function () { return false; },
            input: function () { return false; },
            select: function () { return false; },
            textarea: function () { return false; },
            button: function () { return false; },
            script: function () { return false; },
            style: function () { return false; },
            iframe: function () { return false; },
            object: function () { return false; },
            embed: function () { return false; },
            applet: function () { return false; },
            base: function () { return false; },
            frame: function () { return false; },
            frameset: function () { return false; },
            link: function () { return false; },
            meta: function () { return false; }
        }
    });
});