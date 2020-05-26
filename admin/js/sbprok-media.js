jQuery(document).ready( function( $ ) {
    console.log("ready");

    $('#sbprok-img-upload').click(function(e) {
        e.preventDefault();
        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
        window.send_to_editor = function(html) {
           imgurl = $(html).attr('src');
           $('#sbprok-img-id').val(imgurl);
           $('#sbprok-img-preview').attr("src", imgurl);
           tb_remove();
        }
        return false;
    });

});