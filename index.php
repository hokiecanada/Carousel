<?php 
    require_once("config.php");

    if(isset($_FILES['files'])) {
        $response = array();
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $count = count($_FILES['files']['name']);
        $date = date("Y-m-d");

        for ($i=0; $i<$count; $i++) {
            $pathinfo = pathinfo($_FILES["files"]["name"][$i]);
            $fileType = (isset($pathinfo['extension'])) ? strtolower($pathinfo['extension']) : '';
            $originalFileName = $_FILES["files"]["name"][$i];
            $fileName = $pathinfo['filename'];
            $fileTmpName = $_FILES['files']['tmp_name'][$i];
            $fileSize = $_FILES['files']['size'][$i];

            if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif"
                && $fileType != "avif" && $fileType != "heic" && $fileType != "webp" && $fileType != "avi"
                && $fileType != "mp4" && $fileType != "mov") {
                $response["files"][] = array("file" => $originalFileName, "status" => "error", "message" => "Invalid file type. Allowed file types: PNG, JPEG, GIF, HEIC, WEBP, AVI, MP4, MOV.");
            } elseif ($fileSize > 26214400) {
                $response["files"][] = array("file" => $originalFileName, "status" => "error", "message" => "File is too large. Maxiumum file size is 25MB.");
            } else {
                $targetFileName = $date . '-' . $fileName . "." . $fileType;
                $targetFile = './uploads/' . $targetFileName;
                $extra = 1;
                while(file_exists($targetFile)) {
                    $targetFileName = $date . '-' . $fileName . "-" . $extra . "." . $fileType;
                    $targetFile = './uploads/' . $targetFileName;
                    $extra++;
                }
                if (move_uploaded_file($fileTmpName, $targetFile)) {
                    $q = sprintf("INSERT INTO mav_gallery (file, category, description, datetime) VALUES ('%s', '%s', '%s', '%s')",
                            $db->real_escape_string($targetFileName), $db->real_escape_string($category), $db->real_escape_string($description), date("Y-m-d H:i:s"));
                    $result = $db->query($q);

                    if ($result === TRUE) {
                        $response["files"][] = array("file" => $originalFileName, "status" => "success", "message" => "File successfully uploaded.");
                    } else {
                        $response["files"][] = array("file" => $originalFileName, "status" => "error", "message" => $db->error);
                    }
                } else {
                    $response["files"][] = array("file" => $originalFileName, "status" => "error", "message" => "There was an error saving the file.");
                }
            }
        }

        header('HTTP/1.1 200 OK');
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }
?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./gallery.css?v=1.6">
    </head>
    <body>
        <h1>Maverick Volleyball<br><span style="color: red; padding-left: 50px;">Gallery</span></h1>
        <form class="upload_form is-browse" method="post" action="." enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <div class="outer_box">
                <label for="fake_files" class="drag_drop_box">
                    <div class="ajax_success">Your files have been uploaded successfully!</div>
                    <div class="ajax_partial"></div>
                    <div class="ajax_error">There was a problem uploading your files. Please try again.</div>
                    <input type="file" id="fake_files" class="box__file" name="fake_files[]" accept="image/*, video/*" multiple />
                    <div class="notice">
                        <div class="browse">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                            <div style="margin-top:15px;">
                                <strong>Choose files</strong><span class="desktop_only"> or drag here.</span><br><span class="small">(25MB limit)</span>
                            </div>
                            <div class="error">Please add at least 1 file.</div>
                        </div>
                        <div class="selected">
                            <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                            <div style="margin-top:15px;">
                                <div class="selected_files"></div>
                                <div class="clear_files">
                                    <span>Clear files</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="uploading">
                            <img class="upload_image" src="uploading.gif" />
                            <div><strong>Uploading…</strong></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input_field">
                <div class="error">Please select a category.</div>
                <select id="category" name="category">
                    <option disabled selected value> -- Select a category -- </option>
                    <option value="Learn-to-Play">Learn-to-Play</option>
                    <option value="Teen Recreational">Teen Recreational</option>
                    <option value="CTC">CTC</option>
                    <option value="Competitive">Competitive</option>
                    <option value="High Performance">High Performance</option>
                    <option value="Camps">Camps</option>
                </select>
            </div>
            <div class="input_field">
                <textarea id="description" name="description" rows="2" placeholder="Please describe where/when pictures were taken!"></textarea>
            </div>
            <div class="input_field">
                <div class="error">Please accept the waiver.</div>
                <label class="checkbox" for="waiver">
                    <input type="checkbox" id="waiver" name="waiver" />
                    <span class="fake_checkbox_wrapper">
                        <span class="fake_checkbox"></span>
                    </span>
                    <span class="red" style="margin-right: 5px">*</span><span class="small">I understand these images are going to be considered for use on the Maverick Volleyball web site.</span>
                </label>
            </div>
            <button type="submit" class="box__button">UPLOAD</button>
        </form>
    </body>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./gallery.js?v=1.6"></script>
</html>