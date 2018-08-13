$(document).ready(function()
{
    $("#tablet").jqScribble();
    $('#saveimg').on('click', function(){
        $("#tablet").data("jqScribble").save(function(imageData)
        {
            if(confirm("Сохранить данные?"))
            {
                var url = window.location.href;

                var data = {
                    canvasFile:imageData,
                    passw : $("#passw").val()
                };

                $.post(url, data, function(response)
                {
                    if(response.ELEMENT_ID)  {
                        document.location.href =  '/test/editor/' + response.ELEMENT_ID + '/';
					} else {
						alert(response.MESSAGE);
					}
                });
            }
        });
    });

    if(src !== '')$("#tablet").data("jqScribble").update({backgroundImage: src});

});