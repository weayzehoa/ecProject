// ✅ 靜音 clipboard 錯誤訊息
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
        ) return;
        originalWarn.apply(console, args);
    };

    console.log = function (...args) {
        if (
            typeof args[0] === 'string' &&
            args[0].includes('https://ckeditor.com/docs/ckeditor4/latest/guide/dev_errors.html#clipboard-image-handling-disabled')
        ) return;
        originalLog.apply(console, args);
    };
})();
(function () {
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

    window.initCKEditor = function (csrfToken, uploadUrl = null, deleteUrl = null) {
        const enableImageUpload = !!(uploadUrl && deleteUrl);

        document.querySelectorAll('textarea.ckeditor').forEach(function (el) {
            if (el.getAttribute('data-ckeditor-initialized')) return;

            el.setAttribute('data-ckeditor-initialized', 'true');
            el.classList.remove('ckeditor');
            el.classList.add('myCkeditor');

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
                config.removePlugins = 'uploadimage,uploadfile,uploadwidget,filebrowser,filetools';
            }

            const editor = CKEDITOR.replace(el, config);

            editor.on('change', function () {
                el.value = editor.getData();
            });

            if (!enableImageUpload) {
                editor.on('paste', function (evt) {
                    const data = evt.data;
                    if (data?.dataValue?.includes?.('src="data:image/')) {
                        alert('禁止貼上圖片。');
                        evt.cancel();
                    }
                });
            }

            editor.on('instanceReady', function () {
                if (!enableImageUpload) return;

                editor.addMenuGroup('customGroup');
                editor.addMenuItem('deleteImageItem', {
                    label: '刪除圖片',
                    icon: CKEDITOR.basePath + 'plugins/delete/icons/delete.png',
                    command: 'deleteImage',
                    group: 'customGroup',
                });

                editor.contextMenu.addListener(function (element) {
                    if (element.getName() === 'img' && element.getAttribute('src')?.includes('/storage/upload/ckeditor/')) {
                        return { deleteImageItem: CKEDITOR.TRISTATE_OFF };
                    }
                });

                editor.addCommand('deleteImage', {
                    exec: function () {
                        const selection = editor.getSelection();
                        const element = selection?.getSelectedElement?.();
                        if (element?.getName() === 'img') {
                            const imgUrl = element.getAttribute('src');
                            confirmAndDeleteImage(imgUrl, deleteUrl, csrfToken, () => element.remove());
                        }
                    }
                });

                // ✅ Polling 補償：只有 focus 的 editor 會啟用
                let currentImages = new Set();
                let isFocused = false;

                editor.on('focus', function () {
                    isFocused = true;
                });
                editor.on('blur', function () {
                    isFocused = false;
                });

                function getCurrentImageSrcs() {
                    return new Set(
                        Array.from(editor.document.getBody().$?.querySelectorAll('img') || [])
                            .filter(img => img.src.includes('/storage/upload/ckeditor/'))
                            .map(img => img.src)
                    );
                }

                function checkDeletedImages() {
                    if (!isFocused) return;
                    const newSet = getCurrentImageSrcs();
                    for (const src of currentImages) {
                        if (!newSet.has(src)) {
                            console.log('🛉 polling 偵測刪除圖片：', src);
                            confirmAndDeleteImage(src, deleteUrl, csrfToken);
                        }
                    }
                    currentImages = newSet;
                }

                currentImages = getCurrentImageSrcs();
                setInterval(checkDeletedImages, 1000);
            });
        });
    };

    function confirmAndDeleteImage(imgUrl, deleteUrl, csrfToken, onSuccess) {
        const url = new URL(imgUrl, window.location.origin);
        const relativePath = url.pathname;

        if (!relativePath.includes('/storage/upload/ckeditor/')) return;

        console.log('🔁 DELETE API 呼叫：', relativePath);

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ url: relativePath }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.deleted && typeof onSuccess === 'function') {
                    onSuccess();
                }
            })
            .catch(() => {
                alert('圖片刪除過程中發生錯誤。');
            });
    }
})();
