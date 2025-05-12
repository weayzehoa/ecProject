// ✅ 靜音 CKEditor 的 clipboard 圖片處理 warning（選項 2）
(function () {
    const originalWarn = console.warn;
    const originalLog = console.log;

    console.warn = function (...args) {
        if (
            typeof args[0] === 'string' &&
            (
                args[0].includes('[CKEDITOR] Error code: clipboard-image-handling-disabled') ||
                args[0].includes('https://ckeditor.com/docs/ckeditor4/latest/guide/dev_errors.html#clipboard-image-handling-disabled')
            )
        ) {
            return;
        }

        originalWarn.apply(console, args);
    };

    console.log = function (...args) {
        if (
            typeof args[0] === 'string' &&
            args[0].includes('https://ckeditor.com/docs/ckeditor4/latest/guide/dev_errors.html#clipboard-image-handling-disabled')
        ) {
            return;
        }

        originalLog.apply(console, args);
    };
})();
(function () {
    // ✅ 攔截 CKEditor 上傳成功提示
    (function overrideUploadNotificationAggregator() {
        const timer = setInterval(() => {
            const Aggregator = window.CKEDITOR?.plugins?.notificationAggregator;
            if (Aggregator?.prototype) {
                clearInterval(timer);
                const origOnce = Aggregator.prototype.once;
                Aggregator.prototype.once = function (event, callback) {
                    if (event === 'finished') {
                        const self = this;
                        return origOnce.call(this, event, function () {
                            self.notification?.hide();
                        });
                    }
                    return origOnce.call(this, event, callback);
                };
            }
        }, 100);
    })();

    /**
     * 初始化 CKEditor，自動依參數判斷是否啟用上傳與刪除功能
     * @param {string} textareaId
     * @param {string} csrfToken
     * @param {string|null} [uploadUrl] - 選填：啟用圖片上傳
     * @param {string|null} [deleteUrl] - 選填：啟用圖片刪除
     */
    window.initCKEditor = function (textareaId, csrfToken, uploadUrl = null, deleteUrl = null) {
        const enableImageUpload = !!(uploadUrl && deleteUrl);

        const config = {
            height: 300,
            language: 'zh',
            removeDialogTabs: 'link:upload;image:Upload',
        };

        if (enableImageUpload) {
            config.filebrowserUploadUrl = uploadUrl + '?_token=' + csrfToken;
            config.filebrowserImageUploadUrl = uploadUrl + '?_token=' + csrfToken;
            config.filebrowserUploadMethod = 'form';
            config.extraPlugins = 'uploadimage,uploadfile,clipboard,filetools';
        } else {
            // config.removePlugins = 'uploadimage,uploadfile,filetools,pastefromword';
            config.removePlugins = 'uploadimage,uploadfile,uploadwidget,filebrowser,filetools';
        }

        const editor = CKEDITOR.replace(textareaId, config);

        // ✅ 只有當「未啟用圖片上傳」時，封鎖 base64 圖片貼上
        if (!enableImageUpload) {
            editor.on('paste', function (evt) {
                const data = evt.data;
                if (data?.dataValue?.includes?.('src="data:image/')) {
                    alert('禁止貼上圖片。');
                    evt.cancel();
                }
            });
        }

        CKEDITOR.on('instanceReady', function (evt) {
            const editor = evt.editor;

            if (enableImageUpload) {
                editor.addMenuGroup('customGroup');
                editor.addMenuItem('deleteImageItem', {
                    label: '刪除圖片',
                    icon: CKEDITOR.basePath + 'plugins/delete/icons/delete.png',
                    command: 'deleteImage',
                    group: 'customGroup',
                });

                editor.contextMenu.addListener(function (element) {
                    if (
                        element.getName() === 'img' &&
                        element.getAttribute('src').includes('/storage/upload/ckeditor/')
                    ) {
                        return { deleteImageItem: CKEDITOR.TRISTATE_OFF };
                    }
                });

                editor.addCommand('deleteImage', {
                    exec: function (editor) {
                        const selection = editor.getSelection();
                        const element = selection.getStartElement();
                        if (element?.getName() === 'img') {
                            const imgUrl = element.getAttribute('src');
                            confirmAndDeleteImage(imgUrl, deleteUrl, csrfToken, () => element.remove());
                        }
                    },
                });

                editor.on('contentDom', function () {
                    const editable = editor.editable();
                    editable.attachListener(editable, 'keydown', function (e) {
                        if (e.data.$.key === 'Delete') {
                            const selection = editor.getSelection();
                            const element = selection.getStartElement();
                            if (element?.getName() === 'img') {
                                const imgUrl = element.getAttribute('src');
                                confirmAndDeleteImage(imgUrl, deleteUrl, csrfToken, () => element.remove());
                            }
                        }
                    });
                });
            }

            // 禁止貼上圖片（僅當不啟用圖片上傳時）
            if (!enableImageUpload) {
                editor.on('paste', function (evt) {
                    const items = (evt.data.dataTransfer || evt.data.clipboardData)?.files;
                    if (items && items.length > 0) {
                        alert('此處禁止貼上圖片。');
                        evt.cancel();
                    }
                });
            }

            // change 寫回 textarea
            CKEDITOR.instances[textareaId].on('change', function () {
                const textarea = document.getElementById(textareaId);
                if (textarea) {
                    textarea.value = CKEDITOR.instances[textareaId].getData();
                }
            });
        });
    };

    function confirmAndDeleteImage(imgUrl, deleteUrl, csrfToken, onSuccess) {
        if (!imgUrl.includes('/storage/upload/ckeditor/')) return;
        if (!confirm('確定要刪除此圖片嗎？這將會永久刪除伺服器上的圖片檔案。')) return;

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ url: imgUrl }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.deleted) {
                    if (typeof onSuccess === 'function') onSuccess();
                } else {
                    alert('圖片刪除失敗，請稍後再試。');
                }
            })
            .catch(() => {
                alert('圖片刪除過程中發生錯誤。');
            });
    }
})();
