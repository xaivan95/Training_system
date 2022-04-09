function SetTOCVisibility(visible) {
    var toc = document.getElementById('toc');
    var content = document.getElementById('content');
    var showhidetoc = document.getElementById('showhidetoc');
    if (visible == 'true') {        
        toc.classList.remove("d-none");
        toc.classList.add("col-3");
        content.classList.remove("col-12");
        content.classList.add("col-9");
        content.style.margin = '5 5 5 250';
        shtoc.title="Скрыть содержание";
        showhidetoc.innerHTML = '<path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5zM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5z"/>';        
    }
    else {       
        toc.classList.remove("col-3");
        toc.classList.add("d-none");
        content.classList.remove("col-9");
        content.classList.add("col-12");        
        content.style.margin = '5 5 5 5';
        shtoc.title="Показать содержание";
        showhidetoc.innerHTML = ' <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>';
    }
    let date = new Date(Date.now() + 86400e3);
    date = date.toUTCString();
    document.cookie = "tocvisible=" + visible + "; expires=" + date;
}

function HideShowTOC() {
    var toc = document.getElementById('toc');
    if (toc.classList.contains('d-none')) {
        SetTOCVisibility('true');
    }
    else {
        SetTOCVisibility('false');
    }
}

function SetInitialTOCVisibility() {
    var tocvisible = document.cookie.replace(/(?:(?:^|.*;\s*)tocvisible\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    if (tocvisible == 'false') tocvisible = 'false'; else tocvisible = 'true';
    SetTOCVisibility(tocvisible);
}