#!/bin/bash

# 指定 CKEditor 語言檔案目錄
CK_LANG_DIR="public/vendor/ckeditor4/lang"

# 保留語言清單（使用小寫 dash）
KEEP_LANGS=("en" "zh-cn" "zh" "zh-tw")

# 切換到 lang 目錄
cd "$CK_LANG_DIR" || {
  echo "❌ 無法找到語言目錄: $CK_LANG_DIR"
  exit 1
}

echo "🔍 清理 CKEditor 語言包中..."

# 刪除未在保留清單的語言檔
for file in *.js; do
  lang="${file%.js}"
  if [[ ! " ${KEEP_LANGS[@]} " =~ " ${lang} " ]]; then
    echo "🗑️ 刪除: $file"
    rm -f "$file"
  else
    echo "✅ 保留: $file"
  fi
done

echo "✅ 清理完成，只保留以下語言：${KEEP_LANGS[*]}"
