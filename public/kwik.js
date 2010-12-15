$(document).ready(function() {

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
