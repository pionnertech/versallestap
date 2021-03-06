$(function(){

    var ul = $('#upload2 ul');
    $('#drop2 a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    $("#drop2 > a").css({ display: "block"});
    $("#drop2 > input[type=file]").css({ display : "none"});

    // Initialize the jQuery File Upload plugin
    $('#upload2').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop2'),
        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

if(data.files[0].size > 2097152){

   console.log("es mayor por que es de :" + data.files[0].size);

   bootbox.alert("Archivo debe ser menor a 2MB!");

} else {
    console.log("es menor por tener :" + data.files[0].size);

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span class="fa fa-times af" onclick="rewind(this)"></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');
                

               $("#D-drop").data("files", $("#D-drop").data("files")  + data.files[0].name + "|" );
               $("#D-drop").data("dfil", $("#D-drop").data("dfil")  + data.files[0].name + "|" );
            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){
                 tpl.find('p').text()
                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }
                tpl.fadeOut(function(){
                    tpl.remove();
                });
            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        }
        },

        progress: function(e, data){
               $(".af").removeClass("fa-times").addClass("fa-exclamation-triangle");
            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);
           console.info(data, e);
            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
            console.info(data, e);
        },        send : function(){
            $("#upgrade").attr("disabled", true);
        },
        always : function(){
            $("#upgrade").removeAttr("disabled");
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

});