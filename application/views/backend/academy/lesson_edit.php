<?php
// $param1 = lesson id and $param2 = course id
$lesson_details = $this->lms_model->get_lessons('lesson', $param1)->row_array();
$sections = $this->lms_model->get_section('course', $param2)->result_array();
?>

<form  id="uploadForm" action="<?php echo site_url('addons/courses/lessons/'.$param2.'/edit'.'/'.$param1); ?>" method="post" enctype="multipart/form-data">

    <!-- Champ caché pour le jeton CSRF -->
    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />

    <div class="form-group mb-2">
        <label><?php echo get_phrase('title'); ?><span class="required"> * </span></label>
        <input type="text" name = "title" class="form-control" required value="<?php echo $lesson_details['title']; ?>">
    </div>

    <input type="hidden" name="course_id" value="<?php echo $param2; ?>">

    <div class="form-group mb-2">
        <label for="section_id"><?php echo get_phrase('section'); ?><span class="required"> * </span></label>
        <select class="form-control select2" data-toggle="select2" name="section_id" id="section_id" required>
            <?php foreach ($sections as $section): ?>
                <option value="<?php echo $section['id']; ?>" <?php if($lesson_details['section_id'] == $section['id']) echo 'selected'; ?>><?php echo $section['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-2">
        <label for="section_id"><?php echo get_phrase('lesson_type'); ?><span class="required"> * </span></label>
        <select class="form-control select2" data-toggle="select2" name="lesson_type" id="lesson_type" required onchange="show_lesson_type_form(this.value)">
            <option value=""><?php echo get_phrase('select_type_of_lesson'); ?></option>
            <option value="video-url" <?php if($lesson_details['attachment_type'] == 'url' || $lesson_details['attachment_type'] == '') echo 'selected'; ?>><?php echo get_phrase('video_url'); ?></option>
            <?php if (addon_status('amazon-s3')): ?>
                <option value="s3-video" <?php if($lesson_details['attachment_type'] == 'file' || $lesson_details['video_type'] == 'amazon') echo 'selected'; ?>><?php echo get_phrase('video_file'); ?></option>
            <?php endif;?>
            <option value="other-txt" <?php if($lesson_details['attachment_type'] == 'txt') echo 'selected'; ?>><?php echo get_phrase('text_file'); ?></option>
            <option value="other-pdf" <?php if($lesson_details['attachment_type'] == 'pdf') echo 'selected'; ?>><?php echo get_phrase('pdf_file'); ?></option>
            <option value="other-doc" <?php if($lesson_details['attachment_type'] == 'doc') echo 'selected'; ?>><?php echo get_phrase('document_file'); ?></option>
            <option value="other-img" <?php if($lesson_details['attachment_type'] == 'img') echo 'selected'; ?>><?php echo get_phrase('image_file'); ?></option>
        </select>
    </div>

    <div class="<?php if($lesson_details['lesson_type'] != 'video'):?> dv_none <?php endif; ?>" id="video">

        <div class="form-group mb-2">
            <label for="lesson_provider"><?php echo get_phrase('lesson_provider'); ?>( <?php echo get_phrase('for_web_application'); ?> ) <span class="required"> * </span></label>
            <select class="form-control select2" data-toggle="select2" name="lesson_provider" id="lesson_provider" onchange="check_video_provider(this.value)">
                <option value=""><?php echo get_phrase('select_lesson_provider'); ?></option>
                <option value="youtube" <?php if(strtolower($lesson_details['video_type']) == 'youtube') echo 'selected'; ?>><?php echo get_phrase('youtube'); ?></option>
                <option value="vimeo" <?php if(strtolower($lesson_details['video_type']) == 'vimeo') echo 'selected'; ?>><?php echo get_phrase('vimeo'); ?></option>
                <option value="html5" <?php if(strtolower($lesson_details['video_type']) == 'html5') echo 'selected'; ?>>HTML5</option>
                <option value="mydevice" <?php if(strtolower($lesson_details['video_type']) == 'mydevice') echo 'selected'; ?>>My Device </option>
            </select>
        </div>

        <div class="<?php if(strtolower($lesson_details['video_type']) == 'vimeo' || strtolower($lesson_details['video_type']) == 'youtube'):?>  <?php else: ?> dv_none <?php endif; ?>" id = "youtube_vimeo">

            <div class="form-group mb-2">
                <label><?php echo get_phrase('video_url'); ?>( <?php echo get_phrase('for_web_application'); ?> ) <span class="required"> * </span></label>
                <input type="text" id = "video_url" name = "video_url" class="form-control" onblur="ajax_get_video_details(this.value)"  value="<?php echo $lesson_details['video_url']; ?>" placeholder="<?php echo get_phrase('this_video_will_be_shown_on_web_application'); ?>">
                <label class="form-label" id = "perloader" style ="margin-top: 4px; display: none;"><i class="mdi mdi-spin mdi-loading">&nbsp;</i><?php echo get_phrase('analyzing_the_url'); ?></label>
                <label class="form-label" id = "invalid_url" style ="margin-top: 4px; color: red; display: none;"><?php echo get_phrase('invalid_url').'. '.get_phrase('your_video_source_has_to_be_either_youtube_or_vimeo'); ?></label>
            </div>
            

            <div class="form-group mb-2">
                <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_application'); ?> ) <span class="required"> * </span></label>
                <input type="text" name = "duration" id = "duration" class="form-control" value="<?php echo $lesson_details['duration']; ?>" autocomplete="off">
            </div>
        </div>
        <div class="<?php if(strtolower($lesson_details['video_type']) == 'mydevice' ):?>  <?php else: ?> dv_none <?php endif; ?>" id = "mydevice">

            <div class="form-group mb-2">
                <label><?php echo get_phrase('video_url'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <video width="320" height="240" controls>
                <?php if(strtolower($lesson_details['video_type']) == 'mydevice') { ?>
                    <source src="<?php echo base_url('uploads/videos/'.$lesson_details['video_uplaod']); ?>" type="video/mp4">
                    <?php } ?>
                </video>
                <input type="file" id="userfileMe" name="userfileMe" size="20" />
                <div id="error" style="color: red;"></div>
                 <div id="success" style="color: green;"></div>
            </div>

            
        </div>

        <div class="<?php if($lesson_details['lesson_type'] != 'other'):?> dv_none <?php endif; ?>" id = "html5">
            <div class="form-group mb-2">
                <label><?php echo get_phrase('video_url'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" id = "html5_video_url" name = "html5_video_url" class="form-control" value="<?php echo $lesson_details['video_url']; ?>" placeholder="<?php echo get_phrase('this_video_will_be_shown_on_web_application'); ?>">
            </div>

            <div class="form-group mb-2">
                <label><?php echo get_phrase('duration'); ?>( <?php echo get_phrase('for_web_application'); ?> )</label>
                <input type="text" class="form-control" data-toggle='timepicker' data-minute-step="5" name="html5_duration" id = "html5_duration" data-show-meridian="false" value="<?php echo $lesson_details['duration']; ?>">
            </div>

            <div class="form-group mb-2">
                <label><?php echo get_phrase('thumbnail'); ?> <small>(<?php echo get_phrase('the_image_size_should_be'); ?>: 979 x 551)</small> </label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" onchange="changeTitleOfImageUploader(this)">
                        <label class="custom-file-label" for="thumbnail"><?php echo get_phrase('thumbnail'); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="<?php if($lesson_details['lesson_type'] != 'other'):?> dv_none <?php endif; ?>" id = "other">
        <div class="form-group mb-2">
            <label><?php echo get_phrase('attachment'); ?></label>
            <div class="input-group">
                <div class="custom-file">
                <a href="<?php echo base_url().'uploads/lesson_files/'.$lesson_details['attachment']; ?>" class="btn btn-info text-white" download>
                    <i class="fa fa-download font-size-10"></i> <?php echo get_phrase('download').' '.$lesson_details['title']; ?>
                </a>
                    <input type="file" class="custom-file-input" id="attachment" name="attachment" onchange="changeTitleOfImageUploader(this)">

                    <!-- Removed attachment label per UX review  -->
                    <!-- <label class="custom-file-label" for="attachment"><?php echo get_phrase('attachment'); ?></label> -->
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mb-2">
        <label><?php echo get_phrase('summary'); ?></label>
        <textarea name="summary" class="form-control"><?php echo $lesson_details['summary']; ?></textarea>
    </div>

    <div class="text-center">
        <button class = "btn btn-success" type="submit" name="button"><?php echo get_phrase('update_lesson'); ?></button>
    </div>

</form>

<script type="text/javascript">

$(document).ready(function() {
    //$('.select2').select2({dropdownParent: $('#scrollable-modal')});
    initDefaultSelect2();
    initTimepicker();
    show_lesson_type_form($('#lesson_type').val());
});
function initDefaultSelect2() {
    if ($.fn.select2) {
        $('.form-select').select2({
            width: '100%'
        }).on('select2:open', function() {
            // Apply specific background color when dropdown opens
            $('.select2-dropdown').css('background-color', '#f5f5dc');
            $('.select2-search__field').css('background-color', '#f5f5dc');
            $('.select2-results').css('background-color', '#f5f5dc');
        });
        
        // Apply background color to the selection container
        $('.select2-selection').css('background-color', '#f5f5dc');
    }
}

    const lessonDetails = "<?php strtolower($lesson_details['video_type']) == 'mydevice'?>"
if (lessonDetails) { 
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const fileInput = document.getElementById('userfileMe');
                    const file = fileInput.files[0];
                    const maxSize = 500 * 1024 * 1024; // 500 MB
                    const maxWidth = 1920;
                    const maxHeight = 1080;
        
                    if (file) {
                        if (file.size > maxSize) {
                            document.getElementById('error').innerText = 'La taille du fichier ne doit pas dépasser 100 Mo.';
                            return;
                        }
        
                        const video = document.createElement('video');
                        video.preload = 'metadata';
        
                        video.onloadedmetadata = function() {
                            window.URL.revokeObjectURL(video.src);
                            if (video.videoWidth > maxWidth || video.videoHeight > maxHeight) {
                                document.getElementById('error').innerText = 'Les dimensions de la vidéo ne doivent pas dépasser 1920x1080 pixels.';
                            } else {
                                // Si tout est correct, soumettre le formulaire
                                document.getElementById('uploadForm').submit();
                                document.getElementById('success').innerText = 'Good video';
                                document.getElementById('error').innerText = ' ';
                            }
                        };
        
                        video.src = URL.createObjectURL(file);
                    }
                });
    }
</script>
