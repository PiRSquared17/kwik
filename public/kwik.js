$(document).ready(function() {

    //capture common keyboard shortcuts
    //http://www.scottklarr.com/topic/126/how-to-create-ctrl-key-shortcuts-in-javascript/
    var isCtrl = false;
    $(document).keyup(function(e) {
        isCtrl = false;
    }).keydown(function(e) {
        if (e.which == 17) isCtrl = true;
        if (e.which == 83 && isCtrl == true) { //Control+S
            $('button[name=save]').click();
            return false;
        }
        if (e.which == 75 && isCtrl == true) { //Control+K
            $('input[name=terms]').focus();
            return false;
        }
    });

    //confirmation before delete
    $('button[name=delete]').click(function(){
       if (confirm('This will delete the current page. Are you sure?')) {
           $('input[name=method]').val('delete');
           return true;
       } else {
           return false;
       }
    });

    //create button
    $('button[name=new]').click(function(){
        window.location = '/' + $('input[name=terms]').val() + '/edit';
        stopPropagation();
    });
    
    $('#panel').css('position', 'fixed');
    $('#jumpers').css('overflow-y', 'auto');
    $('#jumpers').height($(window).height() - $('#jumpers').position().top - 106);
    $('.span9').addClass('pull-right');

});
