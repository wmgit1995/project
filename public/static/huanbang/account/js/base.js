
function ShowLift(obj){
    var selectClick = $(obj).parent().find('ul:first');
    var selectClickAll = $(obj).parent().find('ul');
    var display = selectClick.css('display');
    $(".two-closed").css('display','none');
    $(".lift-closed a").removeClass('left-tree-click');
    $('.lift-closed').find('span i').removeClass('icon-jiantouarrow483');
    $('.lift-closed').find('span i').addClass('icon-jiantouarrow487');


    if(display == 'none'){
        $(obj).find('span i').removeClass('icon-jiantouarrow487');
        $(obj).find('span i').addClass('icon-jiantouarrow483');
        $(obj).addClass('left-tree-click');
        selectClick.css('display','block');
    }else {
        $(obj).removeClass('left-tree-click');
        $(obj).find('span i').removeClass('icon-jiantouarrow483');
        $(obj).find('span i').addClass('icon-jiantouarrow487');
        selectClickAll.css('display','none');
        selectClickAll.find('span').removeClass('left-tree-click');
        selectClickAll.find('span i').removeClass('icon-jiantouarrow483');
        selectClickAll.find('span i').addClass('icon-jiantouarrow487');
    }
}
function ifClose() {
    var display = $('#ifClose').css('display');
    if(display == 'none'){
        $('#ifClose').css('display','block');
        $('.gz-lef-box').addClass('animated slideInLeft')
    }else{
        $('#ifClose').css('display','none');
    }
}
