/**
 * 画像ファイルを読み込んで base64 を返す共通関数
 * @param {File} file - input[type=file] で選択したファイル
 * @param {Function} callback - 読み込み完了時に実行される処理
 */
export function loadImageFile(file, callback) {
    if (!file.type.match("image.*")) {
        alert("画像ファイルを選択してください");
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        callback(e.target.result);
    };

    reader.readAsDataURL(file);
}
