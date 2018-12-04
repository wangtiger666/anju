var page = 0;
function view_newsal(u){
    $.ajax({
        url:'http://news.expoon.com/Index201512/viewAddNum',
        type:'post',
        data:{n_id:u},
        dataType:"json",
        success:function(data){
                
        }//--success
    });//--ajax
}
$(function() {
    var newsContTop = $('div.newsContTop');
    var articleList = $('div.articleList');
    if(articleList.length > 0)
        articleList.remove();

    if(newsContTop.length > 0) {
        $.ajax({
            url: '/c/companion',
            type: 'post',
            dataType: "html",
            success: function(response) {
                newsContTop.after(response);
				
            }
        });
    }

    var reLink = $('div.reLink');
    var newsContent = $('div.newsContent');
    if(reLink.length > 0)
        reLink.remove();

    if(newsContent.length > 0) {
        $.ajax({
            url: '/Index201512/companion',
            type: 'post',
            dataType: "html",
            success: function(response) {
                $(".shareBdTwo").before(response);
            }
        });
    }
});