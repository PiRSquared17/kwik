$(document).ready(function() {

    //capture common keyboard shortcuts
    //http://www.scottklarr.com/topic/126/how-to-create-ctrl-key-shortcuts-in-javascript/
    var isCtrl = false;
    $(document).keyup(function(e) {
        if (e.which == 17) isCtrl = false;
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

    //association table between buttons and their ID
    var btnCounter = 0;
    var buttons = new Object;

    //with JS, buttons will hide and will appear an anchor besides
    //this anchor clicks the button
    //so the user without JS will see system buttons, and users with JS will see buttons styled like anchors
    //this trick is necessary because it is not possible to style a button exactly like an anchor
    $('#menu button').each(function() {
        $(this).hide();
        btnCounter++;

        var id = $(this).attr('id');
        if (id == '') { //generates an ID for those buttons that haven't one
            id = 'btnX' + btnCounter;
            $(this).attr('id', id);
        }
        
        buttons['btn' + btnCounter] = '#' + id;

        $(this).after('<a href="#" class="btnClicker ' + $(this).attr('class') + '" id="btn' + btnCounter + '" title="' + $(this).attr('title') + '">' + $(this).html() + '</a>');
    });

    //these clicker anchors are commanded to click the original button
    $('a.btnClicker').click(function() {
        $(buttons[$(this).attr('id')]).click();
        return false;
    });

    //textarea resizer
    var add = 0;
    $('.resizer').mousedown(function(){
       if ($(this).attr('id') == 'rowa') add = 1;
       else add = -1;
       resizer();
    }).mouseup(function(){
       add = 0;
    });
    function resizer() {
       $('textarea').attr('rows',$('textarea').attr('rows') + add);
       if (add != 0) setTimeout(resizer, 30);
    }
    
    //confirmation before delete
    $('button[name=delete]').click(function(){
       return confirm('This will delete the current page. Are you sure?');
    });
    
});
