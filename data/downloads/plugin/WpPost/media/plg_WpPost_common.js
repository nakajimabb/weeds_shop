// wppost_listブロックのファイル名は編集不可とする.
$(document).ready(function(){
    var obj = document.getElementById("form_bloc").filename;
    if (obj.value == "wppost_list") {
        document.getElementById("form_bloc").filename.readOnly = true;
    }
})
