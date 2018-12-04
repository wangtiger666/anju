function mycenter(){
    window.open("/Account/user")
  }
function changeable(control, input) {
    control.on('click', function () {
        input.removeAttr('disabled');
        input.focus();
        control.hide();
        control.next().show();
    });
}

function unchangeable(control, input) {
    control.on('click', function () {
        input.attr('disabled', 'disabled');
        input.blur();
        control.hide();
        control.prev().show();
    });
}

function showPage(num) {
    $('.user-right').hide();
    $('.user-right').eq(num).show();
    switch (num) {
        case 0:
            page0Init();
            break;
        case 1:
            page1Init();
            break;
        case 2:
            page2Init();
            break;
        case 3:
            page3Init();
            break;
        case 4:
            page4Init();
            break;
        case 5:
            page5Init();
            break;
    }
    var location = '';
    switch (num) {
        case 0:
            location = '您的位置 : 首页 >> 个人中心 >> 个人信息';
            break;
        case 1:
            location = '您的位置 : 首页 >> 个人中心 >> 我的关注';
            break;
        case 2:
            location = '您的位置 : 首页 >> 个人中心 >> 浏览记录';
            break;
        case 3:
            location = '您的位置 : 首页 >> 个人中心 >> 我的消息';
            break;
        case 4:
            location = '您的位置 : 首页 >> 个人中心 >> 我的预约';
            break;
        case 5:
            location = '您的位置 : 首页 >> 个人中心 >> 我的租房';
            break;
        case 6:
            location = '您的位置 : 首页 >> 个人中心 >> 在线客服';
            break;
    }
    $('#location').html(location);
}

function navInit() {
    var actTab = 5;

    function moveTabBg(index) {
        var val = parseInt(index) * 48 + 'px'
        $('#tab-act').css('margin-top', val);
        $('.user-tab').removeClass('tab-active');
        $('.user-tab').eq(index).addClass('tab-active');
    };

    for (let i = 0; i < 8; i++) {
        $('.user-tab').eq(i).hover(function () {
            moveTabBg(i);
        }, function () {
            moveTabBg(actTab);
        });
        $('.user-tab').eq(i).on('click', function () {
            actTab = i;
            moveTabBg(actTab);
            showPage(i);
        });
    }

    $('.user-tab').eq(actTab).trigger('click');

}

function showAlert(alert,input_1,input_2,input_3) {
    $('#mask').show();
    $(alert).show();
    $(input_1).val('');
    $(input_2).val('');
    $(input_3).val('');
}

$('.alert-close').on('click', function () {
        $(this).parent('.alert').hide();
        $('#mask').hide()
    });

function page0Init() {
    
    sorterInit('#user-page-0');
    // changeable($('#changeName'), $('#name'));
    changeable($('#changeTel'), $('#tel'));
    changeable($('#changeQQ'), $('#qq'));
    changeable($('#changeWx'), $('#wx'));

    // unchangeable($('#saveName'), $('#name'));
    unchangeable($('#saveTel'), $('#tel'));
    unchangeable($('#saveQQ'), $('#qq'));
    unchangeable($('#saveWx'), $('#wx'));

    $('#verify-old').on('input propertychange', function () {
        var reg = /^\d{4}$/
        var result = reg.test(this.value);
        if (result) {
            $('#next').removeAttr('disabled');
            $('#next').addClass('active');
        } else {
            $('#next').attr('disabled', 'disabled');
            $('#next').removeClass('active');
        }
    });

    $('#next').on('click', function () {
        $('#alert-changePhone-1').hide();
        $("#alert-changePhone-2").show();
        // showAlert('#alert-changePhone-2');
    })

}

function page1Init() {
    sorterInit('#user-page-1');
};

function page2Init() {
    sorterInit('#user-page-2');
};

function page3Init() {
    sorterInit('#user-page-3');
};

function page4Init() {
    sorterInit('#user-page-4');
};

function page5Init() {
    sorterInit('#user-page-5');
};

function sorterInit(div){
    var pagesLength = $(div).find('.sorter button').length - 2;
    var currentPage = 1;

    $(div).find('.sorter button').off();

    $(div).find('#page-prev').on('click', function () {
        (currentPage == 1) ? currentPage: currentPage--;
        changePage(currentPage);
    });

    $(div).find('#page-next').on('click', function () {
        (currentPage == pagesLength) ? currentPage: currentPage++;
        changePage(currentPage);
    });

    for(let i=1;i<=pagesLength;i++){
        $(div).find('.sorter button').eq(i).on('click',function(){
            currentPage = i;
            changePage(i);
        })
    }

    function changePage(page) {
        $(div).find('.sorter button').removeClass('page-current');
        $(div).find('.sorter button').eq(page).addClass('page-current');
    }
}


function init() {
    navInit();
};

init();

$("#btn-changeBindPhone").on("click",function(){
    $('#mask').show();
    $("#alert-changePhone-1").show();

})

/**
 * [F_Open_dialog 弹出选择图片框]
 */
function F_Open_dialog() { 
    document.getElementById("sendhead_img").click(); 

}

/**
 * [设置昵称属性]
 */
$("#changeName").on("click",function(){
    $(this).hide();
    $("#name").attr('disabled',false);
    $("#saveName").show();
})

$('#saveName').on("click",function(){
    alert('请点击保存按钮')
})
