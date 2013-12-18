/* 
 * 主要实行函数区域
 * Qoli.W
 * @2012
 */

$.easing.def = "easeInOutQuint";

i_sidebarState = 0; //初始化侧边栏状态计算器
viewWindowsWidth = 0;
viewPageHeight = 0;

viewWindowsWidth = getTotalWidth() //初始化窗口宽度
viewPageHeight = pageHeight(); //初始化页面高度

$(document).ready(function() {
    Main();
})

/**
 * Main()
 * 主要运作
 */

function Main() {

    preloader('Application/Extend/preloader.xml'); //图片预载入

    var imageID = new Image();
    imageID.src = "/Application/Extend/images/csg-4f5faa2749cd0-images.png";
    if (imageID.complete) {
        $('#loading-one').empty().append('').parent().fadeOut(300, 'easeOutQuart', MyNameCardEffect_Run(177, 1200, null, 'easeOutBack'));
    } else {
        imageID.onload = function() {
            $('#loading-one').empty().append('').parent().fadeOut(300, 'easeOutQuart', MyNameCardEffect_Run(177, 1200, null, 'easeOutBack'));
        };
    };

    ResizeRun(); //重置窗口时候,重新绑定动画.

    $('#SidebarButton').fadeTo(1000, 0.5, function() {
        $('#SidebarButton').fadeTo(500, 0.1);
    });

    //名片卡 - 打开侧边栏按钮 动画效果
    $('#SidebarButton').hover(function() {
        $(this).stop(true, true);
        $(this).fadeTo(300, 1);
    }, function() {
        $(this).fadeTo(800, 0.1);
    });

    pointEffect(); //描点动画效果
    MyNameCardEffect(); //名片卡动画效果
    NextViewEffect(); //名片卡 - 侧边栏
    NextViewEffect('.SingleWork', 'Sidebar_WorkView', 'CloseSidebar_WorkViews', chiFrame); //作品 - 侧边栏动画效果

    $(".tiptip").tipTip();

}

function ResizeRun() {
    $(window).resize(function() {
        viewWindowsWidth = getTotalWidth()
        viewPageHeight = pageHeight();
    });
}

/**
 * 名片卡动画
 */

function MyNameCardEffect() {
    $('#MainNameCard').click(function() {
        $('#MainNameCard').stop(true, true);
        MyNameCardEffect_Run(177, 80,
        MyNameCardEffect_Run(195, 150,
        MyNameCardEffect_Run(127, 250, null, 'easeOutQuad'), 'easeOutQuad'), 'easeOutQuad');
    })
}


/**
 * MyNameCardEffect_Run 名片卡点击动画
 * px 上升幅度
 * time 动画时间
 * callback 回调函数
 * ease 动画效果
 */

function MyNameCardEffect_Run(px, time, callback, ease) {
    px = px || 177;
    time = time || 1200;
    callback = callback || DefCallback;
    ease = ease || 'easeOutBack';
    $('#MainNameCard').animate({
        marginTop: px
    },
    time, ease, callback);
}

/**
 * pointEffect 描点动画函数
 */

function pointEffect() {
    jQuery(function($) {
        $.easing.elasout = function(x, t, b, c, d) {
            var s = 1.70158;
            var p = 0;
            var a = c;
            if (t == 0) return b;
            if ((t /= d) == 1) return b + c;
            if (!p) p = d * .3;
            if (a < Math.abs(c)) {
                a = c;
                var s = p / 4;
            } else var s = p / (2 * Math.PI) * Math.asin(c / a);
            return a * Math.pow(2, -10 * t) * Math.sin((t * d - s) * (2 * Math.PI) / p) + c + b;
        };
        $.scrollTo.defaults.axis = 'xy';
        $.scrollTo(0);
        $('.point').click(function() {

            $.scrollTo(this.hash, {
                duration: 1000
            });
            return false;
        });
    });
}

/**
 * NextViewEffect 打开侧边栏
 * ViewButton 激活侧边栏按钮
 * Sidebar 侧边栏名称
 * CloseSidebar 关闭按钮
 * callback 回调函数
 */

function NextViewEffect(ViewButton, Sidebar, CloseSidebar, callback) {
    ViewButton = ViewButton || '#SidebarButton';
    Sidebar = Sidebar || 'Sidebar_View';
    CloseSidebar = CloseSidebar || 'CloseSidebar_Views';
    callback = callback || DefCallback;

    $('#' + Sidebar).css("left", viewWindowsWidth + 500 + 'px');
    $('#' + Sidebar).css("height", $('#MainBox').height() + 50 + "px");
    $('.SidebarLeftLine').css("height", $('#MainBox').height() + 50 + "px");


    $(ViewButton).click(function() {

        if (i_sidebarState == 1) {
            Click_CloseSidebar_Views(ViewButton, Sidebar);
        } else {
            callback(this);

            $(this).addClass('tar180');
            $('#' + Sidebar).css("display", "block");
            $('#MainBox').animate({
                left: "-1000px"
            }, 800);
            $('#' + Sidebar).animate({
                left: viewWindowsWidth - 1017 + "px"
            }, 800, function() {
                i_sidebarState = 1;
            });
        }

    })

    $('#' + CloseSidebar).click(function() {
        Click_CloseSidebar_Views(ViewButton, Sidebar);
    })
}

function Click_CloseSidebar_Views(ViewButton, Sidebar) {
    ViewButton = ViewButton || '#SidebarButton';
    Sidebar = Sidebar || 'Sidebar_View';

    $(ViewButton).removeClass('tar180');
    $('#MainBox').animate({
        left: "0px"
    }, 800);
    $('#' + Sidebar).animate({
        left: viewWindowsWidth + 1017 + "px"
    }, 800, function() {
        $('#' + Sidebar).css("display", "none");
        $('.SidebarRightText').css('marginTop', 0); //重置位置
        i_sidebarState = 0;
    });
}

/**
 * 作品侧边栏的回调函数
 */

function chiFrame(objName) {
    UDID = $(objName).attr('UDID');
    $('#Sidebar_iFrame').attr('src', '/index.php/WorkViewBox?UDID=' + UDID);
    $('#Sidebar_iFrame').load(function() {
        var height = viewPageHeight - 80;
        $(this).height(height < 600 ? 600 : height);
        parent.window.heightSet();
    })
    $('.SidebarRightText').css('marginTop', 690);
    $('#WORK_Point').trigger('click');
}

/**
 * 空白Callback (用于匿名)
 */

function DefCallback() {}

/**
 * 設定框體高度
 */

function heightSet(thisFrame) {
    if ($.browser.mozilla || $.browser.msie) {
        bodyHeight = window.frames["thisFrameName"].document.body.scrollHeight;
    } else {
        bodyHeight = thisFrame.contentWindow.document.documentElement.scrollHeight;
        //这行可代替上一行，这样heightSet函数的参数可以省略了  
        //bodyHeight = document.getElementById("thisFrameId").contentWindow.document.documentElement.scrollHeight;  
    }
    document.getElementById("thisFrameId").height = bodyHeight;
}