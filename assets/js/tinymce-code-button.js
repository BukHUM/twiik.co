/**
 * TinyMCE: ปุ่ม "แทรกโค้ด" + dialog เลือกภาษา (Classic Editor)
 * รองรับ TinyMCE 4 (WordPress Classic Editor)
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
            title: 'แทรกโค้ด',
            width: 720,
            height: 520,
            body: [
                {
                    type: 'listbox',
                    name: 'lang',
                    label: 'ภาษา',
                    value: 'html',
                    values: [
                        { text: 'HTML', value: 'html' },
                        { text: 'JavaScript', value: 'javascript' },
                        { text: 'CSS', value: 'css' },
                        { text: 'PHP', value: 'php' }
                    ]
                },
                {
                    type: 'textbox',
                    name: 'code',
                    label: 'วางโค้ดด้านล่าง',
                    multiline: true,
                    minWidth: 680,
                    minHeight: 380
                }
            ],
            buttons: [
                { text: 'ยกเลิก', onclick: function() { win.close(); } },
                {
                    text: 'แทรก',
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
                    title: 'แทรกโค้ด',
                    icon: 'code',
                    cmd: 'chrysoberyl_code_insert'
                });
                editor.addCommand('chrysoberyl_code_insert', function() {
                    openCodeDialog(editor);
                });
            } else if (editor.ui && editor.ui.registry) {
                editor.ui.registry.addButton('chrysoberyl_code', {
                    text: 'แทรกโค้ด',
                    tooltip: 'แทรกโค้ด (เลือกภาษาได้)',
                    onAction: function() {
                        openCodeDialog(editor);
                    }
                });
            }
        });
    }
})();
