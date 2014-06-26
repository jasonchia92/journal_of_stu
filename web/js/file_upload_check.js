$('document').ready(function(){
    // enable form
    $('.file_upload_block .enable_file_upload').click(function(){
        var block_main = $(this).parent().siblings('.block_main');
        if ( $(this).attr('checked') ){
            block_main.removeClass('func_disabled').children().siblings().removeAttr('disabled').siblings('.tip').css('color', 'blue');
        } else {
            block_main.addClass('func_disabled').children().siblings().attr('disabled', 1).siblings('.tip').css('color', '#888');
            // 清空選取檔案
            block_main.children('.select_upload_file').val('');
        }
    });

    $('.file_upload_block .file_description').focus(function(){
        if ( $(this).val().search('(在此寫下檔案備註)') != -1 )
            $(this).val('');

        $(this).blur(function(){
            if ( $(this).val().length == 0 )
                $(this).val('(在此寫下檔案備註)');
        });
    });

    $('form').submit(function(){
        var verify_result = true;
        $('.file_upload_block').each(function(){
            var dom_main_address = $(this);
            if ( ( $(this).find('.enable_file_upload').attr('checked') ? true : false ) ) {
                var file_name  = dom_main_address.find('.select_upload_file').attr('name');
                var file_value = dom_main_address.find('.select_upload_file').val();
                if( file_value.length == 0 ) {
                    alert('請選擇上傳檔案');
                    verify_result = false;
                    return false;
                } else {
                    var paper_file_verify = ( ( file_name == 'new_upload_file') && (!file_value.match(/.*\.(rar|zip)/)) );
                    var craa_file_verify  = ( ( file_name == 'craa') && (!file_value.match(/.*\.(pdf|doc|docx)/)) );
                    
                    if ( paper_file_verify == true )
                        alert('論文壓縮檔格式必須為rar或zip。');
                    if ( craa_file_verify == true )
                        alert('著作權讓與同意書格式必須為pdf、doc或docx。');
                    
                    if ( paper_file_verify || craa_file_verify ) {
                        verify_result = false;
                        return false;
                    }
                }
            }
        });

        if ( verify_result == false )
            return false;

        // 清除提示訊息
        var file_description = $('.file_upload_block .file_description');
        if ( file_description.val().search('(在此寫下檔案備註)') != -1 )
            file_description.val('');
    });

});