/**
 * Created by Spencer on 1/13/14.
 */

$(document).ready(function(){
    $(".registerlb").colorbox({width:"600px", height:"auto", inline:true, href:"#registerlbcontent"});
    $(".loginlb").colorbox({width:"600px", height:"auto", inline:true, href:"#loginlbcontent"});
    $(".updatelb").colorbox({width:"600px", height:"auto", inline:true, href:"#updatelbcontent"});
    $(".changepasswordlb").colorbox({width:"600px", height:"auto", inline:true, href:"#changepasswordlbcontent"});

    // SET CLOSE ONCLICK ON '.colorboxclose'
    $('.colorboxclose').click(function() {
        $.colorbox.close();
    });

    // RETURN FALSE ON CLOLORBOX CLOSE TO PREVENT SCREEN JUMP
    $(document).bind('cbox_closed', function(){
        return false;
    });
});