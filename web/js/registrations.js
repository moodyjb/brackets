
 // Registrations Contacts Help "?"
 $(document).on('click', '#modalContactsHelp', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=registrations/help-contacts");
    $('#modal').modal({show:true, backdrop:'static'});
 });
 // Registrations Directive Help "?"
 $(document).on('click', '#modalDirectiveHelp', function(){
    console.log($(this).attr('value'));
    $('#modal').find('.modal-dialog').css("width", "60%");
    $('#modal').find('.modal-body').load("index.php?r=registrations/help-directive");
    $('#modal').modal({show:true, backdrop:'static'});
 });

