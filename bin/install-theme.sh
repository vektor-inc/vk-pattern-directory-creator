#!/usr/bin/env bash

set -ex

# -f HTTP エラー時に失敗として終了する / Fail with a non-zero exit code on HTTP errors.
# -L 302 などのリダイレクトを追う（付けないと 0 バイトの zip になる） / Follow redirects (without it the zip is 0 bytes).
# -s 進捗を非表示、-S エラーは表示 / Hide progress but still show errors.
# -o 実行結果をファイルへ出力 / Write the result to a file.
rm -rf temp
# --proto-redir =https リダイレクト先を https に限る / Allow only https as the redirect target.
curl -fsSL --proto-redir =https https://downloads.wordpress.org/theme/lightning.zip -o theme.zip
# -d 指定したディレクトリに展開 / Extract into the given directory.
mkdir ./temp/
unzip theme.zip -d ./temp/themes
# 展開したので元の zip ファイルを削除 / Remove the zip after extracting.
rm -f theme.zip
