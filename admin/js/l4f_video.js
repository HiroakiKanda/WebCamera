//
// option {
//   maxrender: 8,
//   accesskeyid: "accesskey",
//   stageid: "rescanvas",
//   rec_bot_id: "rec_bot",
//   clear_bot_id: "clear_bot",
//   videoid: "video",
//   videocanvasid: "videocanvas",
//   fpsid: "fps",
//   ext_attrs: ["PASSWORD", "BIRTHDAY"]
// }
l4f_video = function(option) {
    var stage = new createjs.Stage(option.stageid);
    var stageBmp = new createjs.Bitmap();
    stage.addChild(stageBmp);
    faceDetectRectShape = new Array(option.maxrender);
    faceDetectFillShape = new Array(option.maxrender);
    faceLandmarkPane = new Array(option.maxrender);
    var initLandmarkShape = function(shapes, w, h) {
        $.each(shapes, function(i, v) {
            shapes[i] = new createjs.Container();
            shapes[i].setBounds(0 ,0, w, h);
            stage.addChild(shapes[i]);
        });
    };
    parseNum = function(v, def) {
        ret = parseInt(v);
        return ret | def;
    };
    faceDetectNameText = new Array(option.maxrender);
    var initDetectText = function(texts, fontsize) {
        $.each(texts, function(i, v) {
            text = new createjs.Text("NULL", fontsize + "px Arial", "#00ff00");
            text.x = stage.canvas.width;
            text.y = stage.canvas.height;
            text.textAlign = "left";
            text.textBaseline = "bottom";
            text.text = "";
            stage.addChild(text);
            texts[i] = text;
        });
    };
    $(window).on('resize', function(e){
        var top = parseNum($("#form").css("height"), 19);
        var width = $(e.target).width() - 15;
        width = width < 100 ? 100 : width;
        stage.canvas.width = width - 30;
        stage.canvas.height = $(e.target).height() - top - 60;
        $("#" + option.videoid).attr("width", stage.canvas.width).attr("height", stage.canvas.height);
        $.each(faceDetectRectShape, function(i, v) {
            g = new createjs.Graphics();
            faceDetectRectShape[i] = new createjs.Shape(g);
            faceDetectRectShape[i].setBounds(0,0,100,100);
            faceDetectRectShape[i].visible = false;
            stage.addChild(faceDetectRectShape[i]);
        });
        $.each(faceDetectFillShape, function(i, v) {
            g = new createjs.Graphics();
            faceDetectFillShape[i] = new createjs.Shape(g);
            faceDetectFillShape[i].setBounds(0,0,100,100);
            faceDetectFillShape[i].visible = false;
            stage.addChild(faceDetectFillShape[i]);
        });
        initLandmarkShape(faceLandmarkPane, stage.canvas.width, stage.canvas.height);
        initDetectText(faceDetectNameText, 24);
    });
    $(window).trigger('resize');
    rectScale = 1;
    stageUpdate = function(authData) {
        if (!stageBmp.image) return;
        hScale = stage.canvas.height / stageBmp.image.height;
        wScale = stage.canvas.width / stageBmp.image.width;
        rectScale = hScale > wScale ? wScale : hScale;
        stageBmp.scaleX = stageBmp.scaleY = rectScale;
        offsetX = Math.floor((stage.canvas.width - stageBmp.image.width * rectScale) / 2);
        offsetY = Math.floor((stage.canvas.height - stageBmp.image.height * rectScale) / 2);
        offsetX = offsetX < 0 ? 0 : offsetX;
        offsetY = offsetY < 0 ? 0 : offsetY;
        stageBmp.x = offsetX;
        stageBmp.y = offsetY;
        for (i=0; i<option.maxrender ; i++) {
            if (faceDetectRectShape[i]) faceDetectRectShape[i].visible = false;
            if (faceDetectFillShape[i]) faceDetectFillShape[i].visible = false;
            if (faceDetectNameText[i]) {
                faceDetectNameText[i].text = "";
                faceDetectNameText[i].alpha = 0;
            }
            faceLandmarkPane[i].removeAllChildren();
        }
        if (!authData || !authData.RESULT || !authData.RESULT.FACE) return;
        var face = authData.RESULT.FACE;
        $.each(faceDetectRectShape, function(j, elem){
            if (!face[j]) {
                return;
            }
            x = Math.floor(face[j].RECT_LEFT * rectScale) + offsetX;
            y = Math.floor(face[j].RECT_TOP * rectScale) + offsetY;
            w = Math.floor((face[j].RECT_RIGHT - face[j].RECT_LEFT) * rectScale);
            h = Math.floor((face[j].RECT_BOTTOM - face[j].RECT_TOP) * rectScale);
            r = 10;

            faceDetectNameTextShape = faceDetectNameText[j];
            faceLandmarkShape = faceLandmarkPane[j];
            shapeColor = "#00ff00";
            if (face[j].RECOGNITION_CHECK_RESULT != "1") {
                shapeColor = "#ff0000";
            }
            faceDetectNameTextShape.color = shapeColor;
            faceDetectNameTextShape.x = x;
            faceDetectNameTextShape.y = y;

            g = new createjs.Graphics();
            g.beginStroke(shapeColor);
            g.setStrokeStyle(5);
            g.rect(x, y, w, h);
            faceDetectRectShape[j].graphics = g;
            faceDetectRectShape[j].setBounds(0, 0, stage.canvas.width, stage.canvas.height);
            faceDetectRectShape[j].visible = true;

            g = new createjs.Graphics();
            g.beginFill(shapeColor);
            g.rect(x, y, w, h);
            faceDetectFillShape[j].graphics = g;
            faceDetectFillShape[j].setBounds(0, 0, stage.canvas.width, stage.canvas.height);
            faceDetectFillShape[j].visible = true;
            faceDetectFillShape[j].alpha = 0.3;
            faceDetectFillShape[j].removeAllEventListeners("click");
            clickFace = face[j];
            faceDetectFillShape[j].addEventListener("click", function(evt){
                $("#regist").remove();
                div = $("<div id=\"regist\" class=\"regist\" style=\"position:absolute;z-index:20;width:400px;display:none;background-color:#ffffff;border: 4px double #dddddd;\"/>");
                form = $("<form id=\"registForm\" style=\"text-align:right;\">").submit(function(){$('#regist_bot').click();return false;});
                form.append("PERSON_CODE:<input id=\"PERSON_CODE\" type=\"text\" size=\"20\"/><br/>");
                form.append("PERSON_NAME:<input id=\"PERSON_NAME\" type=\"text\" size=\"20\"/><br/>");
                form.append("FACE_IMG_ID:<input id=\"FACE_IMG_ID\" type=\"text\" size=\"20\" disabled=\"disabled\"/><br/>");
                form.append("NEAR_FACE_IMG_ID:<input id=\"NEAR_FACE_IMG_ID\" type=\"text\" size=\"20\" disabled=\"disabled\"/><br/>");
                $.each(option.ext_attrs, function(rf, efElem){
                    form.append(efElem + ":<input id=\"" + efElem + "\" type=\"text\" size=\"20\"/><br/>");
                });
                form.append("<input id=\"regist_cancel_bot\" type=\"button\" value=\"cancel\"/>");
                form.append("<input id=\"regist_bot\" type=\"button\" value=\"regist\"/>");
                div.append(form);
                $("body").append(div);
                $("#regist_cancel_bot").click(function(){$("#regist").remove();});
                $("#regist_bot").click(function(){
                    accessKeyValue = $("#" + option.accesskeyid).val();
                    if (!accessKeyValue || accessKeyValue == "") {
                        return;
                    }
                    now = new Date();
                    now_str = pad(now.getFullYear())
                                + pad(now.getMonth()+1)
                                + pad(now.getDate())
                                + "_" + pad(now.getHours())
                                + pad(now.getMinutes())
                                + pad(now.getSeconds())
                                + ("00" + now.getMilliseconds()).slice(-3);
                    msg = {
                        "CMD": "learner_learning",
                        "ACCESS_KEY": accessKeyValue,
                        "MSG": {
                            "FRAME_JPG_B64": video_snapshot.imgurl.substr("data:image/jpeg;base64,".length),
                            "PERSON_CODE": $("#PERSON_CODE").val(),
                            "PERSON_NAME": $("#PERSON_NAME").val()
                        }
                    };
                    $.each(option.ext_attrs, function(rf, efElem){
                        msg["MSG"][efElem] = $("#" + efElem).val();
                    });
                    $.ajax({
                        type: "post",
                        url: "/l4f/api.php",
                        data: JSON.stringify(msg),
                        dataType: "json",
                        scriptCharset:"utf-8"
                    }).done(function(result){
                        console.log(result);
                        $("#regist").hide();
                        $("#"+option.clear_bot_id).click();
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                        $("#regist").hide();
                        $("#"+option.clear_bot_id).click();
                    });

                });
                $("#PERSON_CODE").val(clickFace.PERSON_CODE?clickFace.PERSON_CODE:"");
                $("#PERSON_NAME").val(clickFace.PERSON_NAME?clickFace.PERSON_NAME:"");
                $("#FACE_IMG_ID").val(clickFace.FACE_IMG_ID?clickFace.FACE_IMG_ID:"");
                $("#NEAR_FACE_IMG_ID").val(clickFace.NEAR_FACE_IMG_ID?clickFace.NEAR_FACE_IMG_ID:"");
                $.each(option.ext_attrs, function(rf, efElem){
                    $("#" + efElem).val(clickFace[efElem]?clickFace[efElem]:"");
                });
                $("#regist").css("left", evt.stageX + "px").css("top", evt.stageY + "px");
                $("#regist").show();
            });
            faceDetectNameTextShape.text = face[j].PERSON_NAME + "(" + face[j].PERSON_CODE + ")";
            faceDetectNameTextShape.alpha = 1;
            $.each(face[j].LANDMARKS, function(index, elem){
                var g = new createjs.Graphics();
                g.beginStroke(shapeColor);
                g.beginFill(shapeColor);
                lx = Math.floor(elem[0] * rectScale) + offsetX;
                ly = Math.floor(elem[1] * rectScale) + offsetY;
                g.drawCircle(lx, ly, 2);
                faceLandmarkShape.addChild(new createjs.Shape(g));
            });
        });
    };
    var video_snapshot = null;
    var initVideo = function() {
        var localMediaStream = null;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || window.navigator.mozGetUserMedia;
        var video = $("#" + option.videoid).get()[0];
        var videocanvas = $("#" + option.videocanvasid).get()[0];
        var ctx = videocanvas.getContext('2d');
        if(!navigator.getUserMedia) {
            alert("���Ή��u���E�U�ł��B");
            return;
        } else {
            window.URL = window.URL || window.webkitURL;
            navigator.getUserMedia({video: true}, function(stream) {
                localMediaStream = stream;
                video.src = window.URL.createObjectURL(stream);
            }, function(e){console.log('error!', e);});
        }
        video_snapshot = function() {
            if (localMediaStream) {
                orgWidth = videocanvas.width;
                orgHeight = videocanvas.height;
                videocanvas.width = video.videoWidth;
                videocanvas.height = video.videoHeight;
                ctx.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
                imgurl = videocanvas.toDataURL('image/jpeg');
                video_snapshot.imgurl = imgurl;
                videocanvas.width = orgWidth;
                videocanvas.height = orgHeight;
                var img = new Image();
                img.onload = function() {
                    accessKeyValue = $("#" + option.accesskeyid).val();
                    if (!accessKeyValue || accessKeyValue == "") {
                        return;
                    }
                    pad = function(val) {
                        return ("0" + val).slice(-2);
                    };
                    now = new Date();
                    now_str = pad(now.getFullYear())
                                + pad(now.getMonth()+1)
                                + pad(now.getDate())
                                + "_" + pad(now.getHours())
                                + pad(now.getMinutes())
                                + pad(now.getSeconds())
                                + ("00" + now.getMilliseconds()).slice(-3);
                    msg = {
                        "CMD": "analyzer_recognition_sync",
                        "ACCESS_KEY": accessKeyValue,
                        "MSG": {
                            "FRAME_JPG_B64": imgurl.substr("data:image/jpeg;base64,".length),
                            "FRAME_KEY": now_str
                        }
                    };
                    $.ajax({
                        type: "post",
                        url: "/l4f/api.php",
                        data: JSON.stringify(msg),
                        dataType: "json",
                        scriptCharset:"utf-8"
                    }).done(function(result){
                        console.log(result);
                        $("#" + option.stageid).show();
                        stageBmp.image = img;
                        stageUpdate(result);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    });
                }
                img.src = imgurl;
            }
        };
    };
    fps = $("#" + option.fpsid).val();
    createjs.Ticker.setFPS(fps<=0?1:fps);
    createjs.Ticker.addEventListener("tick", function(){
        if ($("#" + option.fpsid).val() > 0 && video_snapshot) {
            video_snapshot();
        }
        stage.update();
    });
    initVideo();
    $("#"+option.rec_bot_id).click(function(){
        if (video_snapshot) {
            video_snapshot();
        }
    });
    $("#"+option.clear_bot_id).click(function(){
        $("#" + option.stageid).hide();
    });
    $("#" + option.fpsid).change(function(){
        fps = $("#" + option.fpsid).val();
        createjs.Ticker.setFPS(fps<=0?1:fps);
    });
    $("#" + option.accesskeyid).change(function(){
        if(!localStorage) return;
        localStorage.setItem(option.accesskeyid, $(this).val());
    });
    if(localStorage) {
        $("#" + option.accesskeyid).val(localStorage.getItem(option.accesskeyid));
    }

};