import Swal from "sweetalert2";

if (window.location.pathname.split('/')[2] === "events") {
        function deleteEvent(e) {
            if (e.target.id === 'deleteEvent') {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure you want to delete this event?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        window.onbeforeunload = false;
                        window.location.replace(e.target.href);
                    }
                })
            }
        }
        document.addEventListener('click', deleteEvent);

    }
    if (window.location.pathname.split('/')[2] === "event") {
        var imgids = [];
        $('#file-input').on('change', function(event){ //on file input change
            if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
            {
                 
                var data = $(this)[0].files; //this file data
                 
                document.getElementById("thumb-output").innerHTML = ''


                $.each(data, function(index, file){ //loop though each file
                    if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){ //check supported file type
                        var fRead = new FileReader(); //new filereader
                        fRead.onload = (function(file){ //trigger function on successful read
                        return function(e) {
                            var img = $('<img/>').addClass('img-thumbnail').attr('src', e.target.result).attr('id', "img"+index).attr('name', "images").css("max-width", "340px").css("max-height", "340px"); //create image element 
                            $('#thumb-output').append(img); //append image to output element

                            var btn = $('<i/>').addClass('fa fa-window-close').css('cursor', 'pointer').css('padding', '15px').attr('id', "btn"+index); //create image element 
                            $('#thumb-output').append(btn);

                            $( "#btn"+index ).on( "click", function(e) {
                                $('#img'+e.target.id.slice(-1)).remove();
                                $('#btn'+e.target.id.slice(-1)).remove();

                                imgids.push(index);
                                
                                document.getElementById('imagesToRemove').value = imgids;

                            });

                        };
                        })(file);
                        fRead.readAsDataURL(file); //URL representing the file's data.
                    }
                });
                 
            }else{
                alert("Your browser doesn't support File API!"); //if File API is absent
            }
        });
        
        function removeUploadedImg(e) {
            let uploadedImg = document.getElementById("uploaded-img");
            let uploadField = document.getElementById("upload-img");
        
        
            if (e.target.id === 'remove-image') {
                uploadedImg.remove();
                uploadField.setAttribute("style", 'display:block');
            }
        
        }
        
        document.addEventListener('click', removeUploadedImg);
    }
    