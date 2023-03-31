jQuery(document).ready(function($) {
    'use strict';

    $(".upload_form").each(function() {
        let $form = $(this);
        let $fileInput = $("#fake_files");
        let $category = $("#category");
        let $waiver = $("#waiver");
        let $success = $form.find(".ajax_success");
        let $partial = $form.find(".ajax_partial");
        let $error = $form.find(".ajax_error");
        let droppedFiles = [];
        let totalSize = 0;
        const SIZE_LIMIT = 26214400; // 25MB
        const ALLOWED_EXTENSIONS = /(\.jpg|\.jpeg|\.png|\.heic|\.avif|\.gif|\.webp|\.avi|\.mov|\.mp4)$/i;

        $form.on("drag dragstart dragend dragover dragenter dragleave drop", function(e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $form.on("dragover dragenter", function() {
            $form.addClass("is-dragover");
        });

        $form.on("dragleave dragend drop", function() {
            $form.removeClass("is-dragover");
        })

		$form.on("drop", function(e) {
            $(".browse").removeClass("error");
			var newFiles = e.originalEvent.dataTransfer.files;
            for (var i = 0; i < newFiles.length; i++) {
                if (!ALLOWED_EXTENSIONS.exec(newFiles[i].name)) {
                    alert("Invalid file type. Only JPG/PNG/HEIC/AVIF/GIF/WEBP/AVI/MOV/MP4 file types allowed.");
                } else if (totalSize + newFiles[i].size <= SIZE_LIMIT) {
                    droppedFiles.push(newFiles[i]);
                    totalSize += newFiles[i].size;
                } else {
                    alert("The maxiumum upload limit has been reached.");
                    break;
                }
            }
            showFiles();
        });

        $fileInput.change(function(e) {
            $(".browse").removeClass("error");
            let newFiles = e.target.files;
            for (var i = 0; i < newFiles.length; i++) {
                if (!ALLOWED_EXTENSIONS.exec(newFiles[i].name)) {
                    alert("Invalid file type. Only JPG/PNG/HEIC/AVIF/GIF/WEBP/AVI/MOV/MP4 file types allowed.");
                } else if (totalSize + newFiles[i].size <= SIZE_LIMIT) {
                    droppedFiles.push(newFiles[i]);
                    totalSize += newFiles[i].size;
                } else {
                    alert("The maxiumum upload limit has been reached.");
                    break;
                }
            }
            showFiles();
        });

        $(".clear_files").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            droppedFiles = [];
            totalSize = 0;
            $form.removeClass("is-selected").addClass("is-browse");
        });

        $category.change(function() {
            $(this).closest(".input_field").removeClass("error");
        });

        $waiver.change(function() {
            $(this).closest(".input_field").removeClass("error");
        });

        $form.submit(function(e) {
			if ($form.hasClass('is-uploading')) return false;

            if (!validate()) return false;

            $form.addClass('is-uploading').removeClass('is-selected');
			e.preventDefault();

            // gathering the form data
            let postData = new FormData(this);
            postData.delete("fake_files[]");

            if (droppedFiles) {
                $.each(droppedFiles, function(i, file) {
                    postData.append("files[]", file);
                });
            }

            setTimeout(() => {
                // add slight delay so upload is always visible
                $.ajax( {
                    url: '.',
                    type: 'POST',
                    data: postData,
                    processData: false,
                    contentType: false
                })
                .always(function(response) {
                    $form.removeClass("is-uploading");
                    $form.addClass("is-browse");
                })
                .done(function(response) {
                    let uploaded = 0;
                    let failed = 0;
                    let total = response.files.length;
                    for (let i=0; i<response.files.length; i++) {
                        if (response.files[i].status == "success") uploaded++;
                        else failed++;
                    }
                    if (failed == 0) {
                        $success.show();
                        setTimeout(() => {
                            $success.fadeOut(500);
                        }, 5000);
                    } else if (uploaded > 0) {
                        $partial.text(uploaded + " out of " + total + " files uploaded successfully.").show();
                        setTimeout(() => {
                            $partial.fadeOut(500);
                        }, 5000);
                    } else {
                        $error.show();
                        setTimeout(() => {
                            $error.fadeOut(500);
                        }, 5000);
                    }
                    reset();
                })
                .fail(function() {
                    $error.show();
                    setTimeout(() => {
                        $error.fadeOut(500);
                    }, 5000);
                    reset();
                });
            }, 200);
        });

        function showFiles() {
            $(".selected_files").text(droppedFiles.length > 1 ? droppedFiles.length + " files selected" : droppedFiles[0].name);
            $form.removeClass("is-browse").addClass("is-selected");
        }

        function validate() {
            var valid = true;

            if (droppedFiles.length == 0) {
                $(".browse").addClass("error");
                valid = false;
            }

            if ($category.val() == null) {
                $category.closest(".input_field").addClass("error");
                valid = false;
            }

            if (!$waiver.is(":checked")) {
                $waiver.closest(".input_field").addClass("error");
                valid = false;
            }         

            return valid;
        }

        function reset() {
            $form[0].reset();
            droppedFiles = [];
            totalSize = 0;
        }
    });
});