// alert("sss");
function upload_fr(elem) {
    var id = $(elem).attr("id"),
        Url_Imag = $("#" + id + "").attr("direction"),
        Object = $("#" + id + "").attr("Object"),
        nameField = $("#" + id + "").attr("name"),
        Id_Object_Detail = $("#" + id + "").attr("Id_Object_Detail"),
        inputFileImage = document.getElementById(id),
        file = inputFileImage.files[0],
        data = new FormData;
    data.append("archivo", file);
    var protocol = window.location.protocol,
        hostname = window.location.hostname;
    $.ajax({
        url: "" + protocol + "//" + hostname + "/sadministrator/upload_arc/Id_Object_Detail/" + Id_Object_Detail + "",
        type: "POST",
        contentType: !1,
        data: data,
        processData: !1,
        cache: !1,
        success: function(data) {
            var l_a_json = eval("(" + data + ")"),
                Path = l_a_json.Path,
                Name_File = l_a_json.Name_File,
                Bolean = l_a_json.Bolean,
                Mensaje = l_a_json.Mensaje;
            console.log(Name_File), console.log("daniel"), $("#" + id + "").attr("style", "display:none;"), $("#" + id + "_div_in_server").attr("style", "display:block;"), $("#" + id + "_div_in_server").html("<div class='Upload_Panel_Edit' ><img class='Upload_Img_Edit' src='" + Path + "/" + Name_File + "' width='40px'><div id='Upload_Btm_Edita' class='Upload_Btn_Edit' panel='" + id + "_div' onclick='Upload_Edita(this);' panel_this='" + id + "_div_in_server' >Cambiar de Archivo</div></div>")
        },
        xhr: function() {
            var a = $.ajaxSettings.xhr();
            return a.upload.onprogress = function(b) {
                var c = 100 * (b.loaded / b.total);
                c = Math.round(c), console.log("progress", 100 * (b.loaded / b.total)), $("#" + id + "background_lp").attr("style", "display:block;"), $("#" + id + "linea_pregress").attr("style", "width:" + c + "%;background-color:#087ce2;color: #fff;text-align: center;"), $("#" + id + "linea_pregress").html(c + "%")
            }, a.onloadstart = function() {
                console.log("start")
            }, a.upload.onload = function(b) {
                console.log(b), console.log(a), $("#img_upload").attr({
                    src: ""
                })
            }, a
        }
    })
}


function DCUpload(elem) {
	// alert("dddd");
    var id = $(elem).attr("id"),
        Url_Imag = $("#" + id + "").attr("direction"),
        Object = $("#" + id + "").attr("Object"),
        nameField = $("#" + id + "").attr("name"),
        Id_Object_Detail = $("#" + id + "").attr("Id_Object_Detail"),
        inputFileImage = document.getElementById(id),
        file = inputFileImage.files[0],
        data = new FormData;
    data.append("archivo", file);
    var protocol = window.location.protocol,
        hostname = window.location.hostname;
    $.ajax({
		url: "" + protocol + "//" + hostname + "/sadministrator/upload_arc/Interface/upload_generic/Id_Object_Detail/"+Id_Object_Detail,
        type: "POST",
        contentType: !1,
        data: data,
        processData: !1,
        cache: !1,
        success: function(data) {
            var l_a_json = eval("(" + data + ")"),
                Path = l_a_json.Path,
                Name_File = l_a_json.Name_File,
                Bolean = l_a_json.Bolean,
                Mensaje = l_a_json.Mensaje;
            console.log(Name_File), console.log("daniel"), $("#" + id + "").attr("style", "display:none;"), $("#" + id + "_div_in_server").attr("style", "display:block;"), $("#" + id + "_div_in_server").html("<div class='Upload_Panel_Edit' ><img class='Upload_Img_Edit' src='" + Path + "/" + Name_File + "' width='40px'><div id='Upload_Btm_Edita' class='Upload_Btn_Edit' panel='" + id + "_div' onclick='Upload_Edita(this);' panel_this='" + id + "_div_in_server' >Cambiar de Archivo</div></div>")
        },
        xhr: function() {
            var a = $.ajaxSettings.xhr();
            return a.upload.onprogress = function(b) {
                var c = 100 * (b.loaded / b.total);
                c = Math.round(c), console.log("progress", 100 * (b.loaded / b.total)), $("#" + id + "background_lp").attr("style", "display:block;"), $("#" + id + "linea_pregress").attr("style", "width:" + c + "%;background-color:#087ce2;color: #fff;text-align: center;"), $("#" + id + "linea_pregress").html(c + "%")
            }, a.onloadstart = function() {
                console.log("start")
            }, a.upload.onload = function(b) {
                console.log(b), console.log(a), $("#img_upload").attr({
                    src: ""
                })
            }, a
        }
    })
}

function Upload_Edita(a) {
    var b = $(a).attr("id"),
        c = $(a).attr("panel"),
        d = $(a).attr("panel_this");
    $("#" + c).attr("style", "display:block;"), $("#" + d).attr("style", "display:none;")
}