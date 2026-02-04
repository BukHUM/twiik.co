/**
 * TinyMCE: "Insert Code" button + language select dialog (Classic Editor)
 * Supports TinyMCE 4 (WordPress Classic Editor)
 *
 * @package Chrysoberyl
 * @since 1.0.0
 */
(function() {
    'use strict';

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/"/g, '&quot;');
    }

    function openCodeDialog(editor) {
        var win = editor.windowManager.open({
            title: 'Insert Code',
            width: 500,
            height: 400,
            body: [
                {
                    type: 'listbox',
                    name: 'lang',
                    label: 'Language',
                    value: 'html',
                    values: [
                        { text: 'HTML', value: 'html' },
                        { text: 'JavaScript', value: 'javascript' },
                        { text: 'CSS', value: 'css' },
                        { text: 'PHP', value: 'php' },
                        { text: 'Python', value: 'python' },
                        { text: 'Bash/Shell', value: 'bash' },
                        { text: 'SQL', value: 'sql' },
                        { text: 'JSON', value: 'json' }
                    ]
                },
                {
                    type: 'textbox',
                    name: 'code',
                    label: 'Code',
                    multiline: true,
                    minHeight: 280
                }
            ],
            buttons: [
                { text: 'Cancel', onclick: function() { win.close(); } },
                {
                    text: 'Insert',
                    subtype: 'primary',
                    onclick: function() {
                        var langCtrl = win.find('#lang')[0] || win.find('[name="lang"]')[0];
                        var codeCtrl = win.find('#code')[0] || win.find('[name="code"]')[0];
                        var lang = 'html';
                        var code = '';
                        if (langCtrl && typeof langCtrl.value === 'function') lang = langCtrl.value();
                        if (codeCtrl && typeof codeCtrl.value === 'function') code = (codeCtrl.value() || '').trim();
                        if (!code) return;
                        var escaped = escapeHtml(code);
                        var html = '<pre class="language-' + lang + '"><code class="language-' + lang + '">' + escaped + '</code></pre>';
                        editor.insertContent(html);
                        win.close();
                    }
                }
            ]
        });
    }

    if (typeof tinymce !== 'undefined' && tinymce.PluginManager) {
        tinymce.PluginManager.add('chrysoberyl_code', function(editor, url) {
            if (editor.addButton) {
                editor.addButton('chrysoberyl_code', {
                    title: 'Insert Code',
                    icon: 'code',
                    cmd: 'chrysoberyl_code_insert'
                });
                editor.addCommand('chrysoberyl_code_insert', function() {
                    openCodeDialog(editor);
                });
            } else if (editor.ui && editor.ui.registry) {
                editor.ui.registry.addButton('chrysoberyl_code', {
                    text: 'Insert Code',
                    tooltip: 'Insert code block with syntax highlighting',
                    onAction: function() {
                        openCodeDialog(editor);
                    }
                });
            }
        });
    }
})();
