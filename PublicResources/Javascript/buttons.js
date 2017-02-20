$(document).ready(function() {
        $("button.reply").each(function() {
        var button = this;
            $(button).click(function() {
            var form = $(button).parent().parent().children('form.reply');
            var textarea = $(form).children("textarea");
            $(form).show();
            $(button).css("visibility", "hidden");
            $(textarea).focus();
            });
        });
    
    $("button.edit").each(function() {
        var button = this;
            $(button).click(function() {
            var form = $(button).parent().parent().children('form.edit');
            var textarea = $(form).children("textarea");
            $(form).show();
            $(button).css("visibility", "hidden");
            $(textarea).focus();
            });
        });
    
        $("button.cancel").each(function() {
           var button = this;
            $(button).click(function() {
                var form = $(button).parent().parent();
                var replybutton = form.parent().children(
                    ".content_footer").children("button.reply");
                var textarea = $(form).children("textarea");
                $(form).hide();
                $(replybutton).css("visibility", "visible");
                $(textarea).val("");
            });
        });
    
     $("button.cancel_edit").each(function() {
           var button = this;
            $(button).click(function() {
                var form = $(button).parent().parent();
                var editbutton = form.parent().children(
                    ".content_footer").children("button.edit");
                $(form).hide();
                $(editbutton).css("visibility", "visible");
            });
        });
    
    $("button.edit_subject").click(function() {
            var button = this;
            var form = $(button).parent().parent().children('form.edit_subject');
            var textarea = $(form).children("textarea");
            $(form).show();
            $(button).css("visibility", "hidden");
            $(textarea).focus();
        });
    
    $("button.cancel_edit_subject").click(function() {
            var button = this;
            var form = $(button).parent().parent();
            var editbutton = form.parent().children(
                ".subject_footer").children("button.edit_subject");
            $(form).hide();
            $(editbutton).css("visibility", "visible");
        });
    
    
    function scroll_to_anchor(anchor_id) {
        $('html,body').animate({scrollTo: anchor_id.offset().top},'slow');
    }


    });