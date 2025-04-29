// Parsley繁體中文語系翻譯
Parsley.addMessages('zh_tw', {
    defaultMessage: "這個值似乎是無效的。",
    type: {
        email:        "請輸入正確格式的電子郵件。",
        url:          "請輸入正確格式的網址。",
        number:       "請輸入正確的數字。",
        integer:      "請輸入正確的整數。",
        digits:       "請輸入數字。",
        alphanum:     "請輸入英文字母或是數字。"
    },
    notblank:       "這個欄位不能為空。",
    required:       "這個欄位不能為空。",
    pattern:        "這個值似乎是無效的。",
    min:            "請輸入不小於 %s 的數值。",
    max:            "請輸入不大於 %s 的數值。",
    range:          "請輸入介於 %s 到 %s 之間的數值。",
    minlength:      "請輸入最少 %s 個字元。",
    maxlength:      "請輸入最多 %s 個字元。",
    length:         "請輸入 %s 到 %s 個字元。",
    mincheck:       "請至少選擇 %s 個選項。",
    maxcheck:       "請至多選擇 %s 個選項。",
    check:          "請選擇 %s 到 %s 個選項。",
    equalto:        "輸入值不同。"
});

Parsley.setLocale('zh_tw');
