<link rel="stylesheet" href="<?php echo base_url(); ?>assets/backend/css/editCourse.css">

<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body py-2">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('edit_course'); ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

            <div class="header-title-wrapper mb-4">
        <div class="header-title-content">
            <h4 class="header-title"><?= get_phrase('course_editing_form') ?></h4>
        </div>
        <div class="header-container d-flex justify-content-between align-items-center mb-2">
            <a href="<?= site_url('addons/lessons/play/' . slugify($course['title']) . '/' . $course['id'] . '/' . $first_lesson_id['id']) ?>" 
            class="btn btn-header btn-play" 
            target="_blank">
                <i class="mdi mdi-play-circle-outline"></i>
                <?= get_phrase('play_lesson') ?>
            </a>
            <a href="<?= site_url('addons/courses') ?>" 
            class="btn btn-header btn-back">
                <i class="mdi mdi-arrow-left-circle"></i>
                <?= get_phrase('back_to_course_list') ?>
            </a>
        </div>
    </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-wrapper">
                        <form class="required-form" action="<?php echo site_url('addons/courses/index/update/'.$course['id']); ?>" method="post" enctype="multipart/form-data">
                         <!-- Champ caché pour le jeton CSRF -->
                         <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" />
                            <div id="basicwizard">

                                  <!-- Top Navigation -->
                            <div class="course-steps-nav">
                                <ul class="nav nav-tabs nav-fill border-0">
                                  <li class="nav-item">
                                     <a href="#curriculum" data-bs-toggle="tab" class="nav-link py-3 rounded-0 active">
                                         <i class="mdi mdi-account-circle"></i>
                                         <span class="d-none d-sm-inline"><?php echo get_phrase('curriculum'); ?></span>
                                     </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#basic" data-bs-toggle="tab" class="nav-link py-3 rounded-0">
                                          <i class="mdi mdi-file-document-outline"></i>
                                          <span class="d-none d-sm-inline"><?php echo get_phrase('basic'); ?></span>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#academy" data-bs-toggle="tab" class="nav-link py-3 rounded-0">
                                          <i class=" mdi mdi-school-outline"></i>
                                          <span class="d-none d-sm-inline"><?php echo get_phrase('academic'); ?></span>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#outcomes" data-bs-toggle="tab" class="nav-link py-3 rounded-0">
                                          <i class="mdi mdi-camera-control"></i>
                                          <span class="d-none d-sm-inline"><?php echo get_phrase('outcomes'); ?></span>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#media" data-bs-toggle="tab" class="nav-link py-3 rounded-0">
                                      <i class="mdi mdi-video-outline"></i>
                                          <span class="d-none d-sm-inline"><?php echo get_phrase('media'); ?></span>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="#finish" data-bs-toggle="tab" class="nav-link py-3 rounded-0">
                                          <i class="mdi mdi-check-circle-outline"></i>
                                          <span class="d-none d-sm-inline"><?php echo get_phrase('finish'); ?></span>
                                      </a>
                                  </li>
                                </ul>
                            </div>
                                <div class="tab-content b-0 mb-0">
                                  <div class="tab-pane active show" id="curriculum">
                                      <?php include 'curriculum.php'; ?>
                                  </div>
                                  <div class="tab-pane" id="basic">
                                      <div class="p-4 p-lg-5">
                                          <h4 class="mb-4 text-slate-800 fw-normal"><?php echo get_phrase('Course details'); ?></h4>

                                          <div class="mb-4">
                                              <label class="form-label fw-medium" for="course_title">
                                                  <?php echo get_phrase('Course title'); ?> <span class="text-danger">*</span>
                                              </label>
                                              <input type="text" value="<?php echo $course['title']; ?>" class="form-control form-control-lg border-0 bg-light" id="course_title" name = "title" placeholder="<?php echo get_phrase('Enter an engaging course title'); ?>" required>
                                              <div class="form-text text-secondary small mt-2">
                                                  <?php echo get_phrase('A compelling title helps attract more students'); ?>
                                              </div>
                                          </div>

                                          <div class="mb-4">
                                              <label class="form-label fw-medium" for="basic_description">
                                                  <?php echo get_phrase('Description'); ?>
                                              </label>
                                              <div class="text-editor-container border rounded">
                                                  <textarea name="description" id="basic_description" class="form-control bg-white" rows="8"><?php echo $course['description']; ?></textarea>
                                              </div>

                                              <!-- Description after textarea -->
                                              <?php /*
                                                    <div class="form-text text-secondary small mt-2">
                                                        <?php echo get_phrase('Describe what students will learn in this course'); ?>
                                                    </div>
                                                    */ ?>
                                          </div>

                                          <!-- Navigation Buttons -->
                                          <div class="d-flex justify-content-end mt-5 custom-navigation-buttons">
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToNext()">
                                                  <?php echo get_phrase('Next'); ?> <i class="mdi mdi-arrow-right ms-1"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div> <!-- end tab pane -->

                                  <div class="tab-pane" id="academy">
                                      <div class="p-4 p-lg-5">
                                          <h4 class="mb-4 text-slate-800 fw-normal"><?php echo get_phrase('Academic information'); ?></h4>

                                          <div class="mb-4">
                                              <label class="form-label fw-medium" for="class_id">
                                                  <?php echo get_phrase('Class'); ?> <span class="text-danger">*</span>
                                              </label>
                                              <select class="form-select form-select-lg border-0 bg-light" name="class_id" id="class_id_add_cours" required>
                                                  <option value=""><?php echo get_phrase('Select a class'); ?></option>
                                                  <?php foreach ($classes->result_array() as $class): ?>
                                                      <option value="<?php echo $class['id']; ?>" <?php if($class['id'] == $course['class_id']) echo 'selected'; ?>><?php echo $class['name']; ?></option>
                                                  <?php endforeach; ?>
                                              </select>
                                          </div>

                                          <?php if($this->session->userdata('teacher_login') == 1): ?>
                                            <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
                                          <?php else: ?>
                                            <div class="mb-4">
                                                <label class="form-label fw-medium" for="user_id">
                                                    <?php echo get_phrase('Instructor'); ?> <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select form-select-lg border-0 bg-light" name="user_id" id="user_id" required>
                                                    <option value=""><?php echo get_phrase('Select a teacher'); ?></option>
                                                    <?php foreach ($all_teachers->result_array() as $teacher): ?>
                                                        <option value="<?php echo $teacher['id']; ?>" <?php if($teacher['id'] == $course['user_id']) echo 'selected'; ?>><?php echo $teacher['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                          <?php endif; ?>

                                          <!-- Navigation Buttons -->
                                          <div class="d-flex justify-content-between mt-5 custom-navigation-buttons">
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToPrevious()">
                                                  <i class="mdi mdi-arrow-left me-1"></i> <?php echo get_phrase('Previous'); ?>
                                              </button>
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToNext()">
                                                  <?php echo get_phrase('Next'); ?> <i class="mdi mdi-arrow-right ms-1"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="tab-pane" id="outcomes">
                                      <div class="p-4 p-lg-5">
                                          <h4 class="mb-4 text-slate-800 fw-normal"><?php echo get_phrase('Learning outcomes'); ?></h4>

                                          <div class="mb-4">
                                              <label class="form-label fw-medium" for="outcomes_desc">
                                                  <?php echo get_phrase('What will students achieve?'); ?>
                                              </label>
                                              <div class="text-editor-container border rounded">
                                                  <textarea name="outcomes" id="outcomes_desc" class="form-control bg-white" rows="8"><?php echo $course['outcomes']; ?></textarea>
                                              </div>

                                              <!-- Description after textarea -->
                                              <?php /*
                                              <div class="form-text text-secondary small mt-2">
                                                  <?php echo get_phrase('List specific skills and knowledge students will gain'); ?>
                                              </div>*/ ?>

                                          </div>

                                          <!-- Navigation Buttons -->
                                          <div class="d-flex justify-content-between mt-5 custom-navigation-buttons">
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToPrevious()">
                                                  <i class="mdi mdi-arrow-left me-1"></i> <?php echo get_phrase('Previous'); ?>
                                              </button>
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToNext()">
                                                  <?php echo get_phrase('Next'); ?> <i class="mdi mdi-arrow-right ms-1"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>


                                  <div class="tab-pane" id="media">
                                      <div class="p-4 p-lg-5">
                                          <h4 class="mb-4 text-slate-800 fw-normal"><?php echo get_phrase('Course media'); ?></h4>

                                          <div class="row">
                                              <div class="col-md-6 mb-4">
                                                  <label class="form-label fw-medium" for="course_overview_provider">
                                                      <?php echo get_phrase('Video provider'); ?> <span class="text-danger">*</span>
                                                  </label>
                                                  <select class="form-select border-0 bg-light" name="course_overview_provider" id="course_overview_provider">
                                                      <option value="youtube" <?php if($course['course_overview_provider'] == 'youtube') echo 'selected'; ?>><?php echo get_phrase('YouTube'); ?></option>
                                                      <option value="vimeo" <?php if($course['course_overview_provider'] == 'vimeo') echo 'selected'; ?>><?php echo get_phrase('Vimeo'); ?></option>
                                                      <option value="html5" <?php if($course['course_overview_provider'] == 'html5') echo 'selected'; ?>><?php echo get_phrase('HTML5'); ?></option>
                                                  </select>
                                              </div>

                                              <div class="col-md-6 mb-4">
                                                  <label class="form-label fw-medium" for="course_overview_url">
                                                      <?php echo get_phrase('Video URL'); ?> <span class="text-danger">*</span>
                                                  </label>
                                                  <input type="text" class="form-control border-0 bg-light" value="<?php echo $course['course_overview_url']; ?>" name="course_overview_url" id="course_overview_url" placeholder="<?php echo get_phrase('https://www.youtube.com/watch?v=example'); ?>" required>
                                              </div>
                                          </div>

                                          <div class="mb-4">
                                              <label class="form-label fw-medium mb-3" for="course_thumbnail">
                                                  <?php echo get_phrase('Course thumbnail'); ?>
                                              </label>

                                              <div class="thumbnail-upload bg-light border rounded-3 p-3 text-center">
                                                  <div class="mb-3">
                                                    <img src="<?php echo base_url('uploads/course_thumbnail/'.$course['thumbnail'] ? $course['thumbnail'] : 'placeholder.png'); ?>" id="thumbnail-preview" class="img-fluid rounded shadow-sm" style="max-height: 180px;">
                                                  </div>

                                                  <label for="course_thumbnail" class="btn btn-outline-primary mb-0">
                                                      <i class="mdi mdi-image me-1"></i> <?php echo get_phrase('Choose image'); ?>
                                                  </label>
                                                  <input id="course_thumbnail" type="file" class="d-none" name="course_thumbnail" accept="image/*">
                                                  <input type="hidden" name="current_thumbnail" value="<?php echo $course['thumbnail']; ?>">
                                                  <div class="form-text text-secondary small mt-2">
                                                      <?php echo get_phrase('Recommended size: 800 × 530 pixels'); ?>
                                                  </div>
                                              </div>
                                          </div>

                                          <!-- Navigation Buttons -->
                                          <div class="d-flex justify-content-between mt-5 custom-navigation-buttons">
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToPrevious()">
                                                  <i class="mdi mdi-arrow-left me-1"></i> <?php echo get_phrase('Previous'); ?>
                                              </button>
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToNext()">
                                                  <?php echo get_phrase('Next'); ?> <i class="mdi mdi-arrow-right ms-1"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="tab-pane" id="finish">
                                      <div class="p-4 p-lg-5 text-center">
                                          <div class="max-w-sm mx-auto py-4">
                                              <div class="completion-check bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                                  <i class="mdi mdi-check-bold text-success" style="font-size: 40px;"></i>
                                              </div>

                                              <h3 class="mb-3"><?php echo get_phrase('Ready to update course'); ?></h3>
                                              <p class="text-secondary mb-4">
                                                  <?php echo get_phrase('Please review all information before submitting. Your course details will be updated.'); ?>
                                              </p>

                                              <button type="button" class="btn btn-success px-5 py-2 fw-medium" onclick="checkRequiredFields()">
                                                  <?php echo get_phrase('Update course'); ?>
                                              </button>
                                          </div>

                                          <!-- Navigation Buttons -->
                                          <div class="d-flex justify-content-start mt-5 custom-navigation-buttons">
                                              <button type="button" class="btn btn-outline-primary px-4 py-2" onclick="goToPrevious()">
                                                  <i class="mdi mdi-arrow-left me-1"></i> <?php echo get_phrase('Previous'); ?>
                                              </button>
                                          </div>
                                      </div>
                                  </div>

                                </div> <!-- tab-content -->
                            </div> <!-- end #progressbarwizard-->
                        </form>
                        </div>
                    </div><!-- end row-->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>
</div>
<?php include 'common_scripts.php'; ?>
<style media="screen">
    body {
      overflow-x: hidden;
    }
</style>

<!--JoditEditor-->

<link href="<?php echo base_url(); ?>assets/backend/jodit-3.24.4/build/jodit.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>assets/backend/jodit-3.24.4/build/jodit.min.js"></script>


<script type="text/javascript">
/**
Navigation to the next form tab. **/
function goToNext() {
    const currentTabLink = document.querySelector('.nav-link.active');
    const nextTabListItem = currentTabLink.closest('li').nextElementSibling;
    if (nextTabListItem) {
        const nextTabLink = nextTabListItem.querySelector('.nav-link');
        const nextTab = new bootstrap.Tab(nextTabLink);
        nextTab.show();
    }
}

/**
 * Navigation to the previous form tab.
 */
function goToPrevious() {
    const currentTabLink = document.querySelector('.nav-link.active');
    const prevTabListItem = currentTabLink.closest('li').previousElementSibling;
    if (prevTabListItem) {
        const prevTabLink = prevTabListItem.querySelector('.nav-link');
        const prevTab = new bootstrap.Tab(prevTabLink);
        prevTab.show();
    }
}

// Update the tab icons based on active state
function updateIcons() {
    document.querySelectorAll('.course-steps-nav .nav-link').forEach(tab => {
        const icon = tab.querySelector('i');

        if (tab.classList.contains('active')) {
            icon.classList.remove('text-muted');
            icon.classList.add('text-primary');
        } else {
            icon.classList.remove('text-primary');
            icon.classList.add('text-muted');
        }
    });
}

$(document).ready(function() {
    initRichTextEditors(); // Initialize JoditEditor rich text editors
    initThumbnailPreview(); // Setup thumbnail image preview functionality
    initDefaultSelect2();         // Initialize Select2 for dropdowns if available
    updateTabIcons();      // Set initial tab icons
    $('.course-steps-nav .nav-link').on('click', function() {
        setTimeout(updateTabIcons, 50);
    });
    
});

/**
 * Initializes JoditEditor rich text editors for description and outcomes fields.
 */
function initRichTextEditors() {
    const commonConfig = {
        height: 300,
        enableDragAndDropFileToEditor: true,
        uploader: {
            insertImageAsBase64URI: true,
            url: '<?=site_url("addons/courses/upload_image")?>',
            headers: {
                'X-CSRF-TOKEN': '<?=$this->security->get_csrf_hash()?>'
            },
            defaultHandlerSuccess: function (data) {
                return data.url;
            }
        },
        video: {
            insertVideoAsBase64URI: true,
            url: '<?=site_url("addons/courses/upload_video")?>',
            headers: {
                'X-CSRF-TOKEN': '<?=$this->security->get_csrf_hash()?>'
            },
            defaultHandlerSuccess: function (data) {
                return data.url;
            }
        },
        toolbarAdaptive: false,
        toolbarSticky: false,
        toolbarStickyOffset: 0,
        askBeforePasteHTML: false,
        askBeforePasteFromWord: false,
        showXPathInStatusbar: false,
        showCharsCounter: false,
        showWordsCounter: false,
        showTooltip: false,
        showPlaceholder: true,
        useSearch: false,
        spellcheck: false,
        saveModeInCookie: false,
        saveModeInStorage: false,
        buttons: 'bold,italic,underline,strikethrough,|,align,undo,redo,|,ul,ol,|,outdent,indent,|,font,fontsize,brush,paragraph,|,image,video,link,|,hr,eraser,|,source,fullsize,preview,print'
    };

    const basicDescriptionEditor = new Jodit('#basic_description', $.extend({}, commonConfig, {
        placeholder: 'Describe what students will learn in this course'
    }));

    const outcomesDescEditor = new Jodit('#outcomes_desc', $.extend({}, commonConfig, {
        placeholder: '<?php echo get_phrase("List specific skills and knowledge students will gain"); ?>'
    }));
}


// Ajouter dans initThumbnailPreview
function initThumbnailPreview() {
    $('#course_thumbnail').change(function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#thumbnail-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
}

/**
 * Initializes Select2 for all form-select elements if the Select2 plugin is available.
 * Applies Bootstrap 5 theme and light styling.
 */
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
/**
 * Updates the icons of the navigation tabs based on their active state.
 * Active tabs get a primary color, while inactive ones are muted.
 */
function updateTabIcons() {
    document.querySelectorAll('.course-steps-nav .nav-link').forEach(tab => {
        const icon = tab.querySelector('i');
        if (tab.classList.contains('active')) {
            icon.classList.remove('text-muted');
            icon.classList.add('text-primary');
        } else {
            icon.classList.remove('text-primary');
            icon.classList.add('text-muted');
        }
    });
}
// Form validation function
function checkRequiredFields() {
    var isValid = true;
    $('form.required-form').find('input, select, textarea').each(function() {
        if ($(this).prop('required') && $(this).val() === '') {
            isValid = false;
            $(this).addClass('is-invalid');

            // Switch to the tab containing the first invalid field
            if (isValid === false) {
                var tabId = $(this).closest('.tab-pane').attr('id');
                $('.nav-link[href="#' + tabId + '"]').tab('show');
                return false;
            }
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (isValid) {
        $('form.required-form').submit();
    } else {
        toastr.error('<?php echo get_phrase("Please fill all required fields"); ?>');
    }
}
</script>