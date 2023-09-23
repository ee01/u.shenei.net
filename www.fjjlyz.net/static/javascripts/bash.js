
$(function(e){

    $('.home-ding>.tit>span').on('click',function(){
        var key=$(this).data('key');
        $(this).parent().find('span').removeClass('cur');
        $(this).addClass('cur');
        $('.home-ding').find('.box').removeClass('show').addClass('hide');
        $('#ding-'+key).removeClass('hide').addClass('show');
    });

    $('.home-box').find('.tab>span').on('click',function(){
        var key=$(this).data('key');
        $(this).parent().find('span').removeClass('cur');
        $(this).addClass('cur');
        $(this).parent().parent().find('.box').removeClass('cur');
        $(this).parent().parent().find('.box_'+key).addClass('cur');
    });

    $('.home-open').find('.tab>span').on('click',function(){
        var key=$(this).data('key');
        $(this).parent().find('span').removeClass('cur');
        $(this).addClass('cur');
        $(this).parent().parent().find('.box').removeClass('cur');
        $(this).parent().parent().find('.box_'+key).addClass('cur');
    });

    $('#go-back').on('click',function(){
        window.history.back(-1);
    });

    $('.info-content').find('.content-img').on('click',function(){
        let src=$(this).attr('src');
        let htm='<div class="imgbox"><img src="'+src+'"></div>';
        $('body').append(htm);
    });

    $('body').on('click','.imgbox',function(){
        $(this).remove();
    })
})




