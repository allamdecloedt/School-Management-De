<div class="col-lg-3 mt-5 order-md-2 course_col hidden text-center" id="lesson_list_loader">
  <img src="<?php echo base_url('assets/backend/images/loader.gif'); ?>" alt="" height="50" width="50">
</div>
<div class="col-lg-3  order-md-2 course_col" id = "lesson_list_area">
  <div class="text-center margin-ms">
    <h5 class=" text-uppercase"><?php echo get_phrase('course_content'); ?></h5>
  </div>
  <div class="row m-10-1">
    <div class="col-12">
      <ul class="nav nav-tabs" id="lessonTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link " id="section_and_lessons-tab" data-bs-toggle="tab" href="#section_and_lessons" role="tab" aria-controls="section_and_lessons" aria-selected="true"><?php echo get_phrase('Lessons') ?></a>
        </li>
      </ul>
      <div class="tab-content" id="lessonTabContent">
        <div class="tab-pane fade show active" id="section_and_lessons" role="tabpanel" aria-labelledby="section_and_lessons-tab">
          <!-- Lesson Content starts from here -->
          <div class="accordion custom-accordion" id="custom-accordion-one">
            <?php
            foreach ($sections as $key => $section):
              $lessons = $this->lms_model->get_lessons('section', $section['id'])->result_array();?>
              <div class="card m-0">
                <div class="card-header ps-0" id="<?php echo 'heading-'.$section['id']; ?>">

                  <h5 class="mb-0">
                    <a class="custom-accordion-title d-block py-1 button-stk " type="button" data-bs-toggle="collapse" data-bs-target="<?php echo '#collapse-'.$section['id']; ?>" <?php if($opened_section_id == $section['id']): ?> aria-expanded="true" <?php else: ?> aria-expanded="false" <?php endif; ?> aria-controls="<?php echo 'collapse-'.$section['id']; ?>" onclick = "toggleAccordionIcon(this, '<?php echo $section['id']; ?>')">
                      <span class="badge  "><?php echo $key++; ?></span>
                      <?php echo $section['title']; ?>
                    </a>
                  </h5>
                </div>
                <div id="<?php echo 'collapse-'.$section['id']; ?>" class="collapse <?php if($section_id == $section['id']) echo 'show'; ?>" aria-labelledby="<?php echo 'heading-'.$section['id']; ?>" data-bs-parent="#custom-accordion-one">
                  <div class="card-body p-0">
                    <table class="w-100">
                      <?php foreach ($lessons as $key => $lesson): ?>

                        <tr class="course-sidebar-td" style="background-color: <?php if ($lesson_id == $lesson['id'])echo '#EAEAEA'; else echo '#fff';?>;">
                          <td class="course-sidebar-td px-2 py-1">
                            <?php
                            $lesson_progress = lesson_progress($lesson['id']);
                            ?>
                            <div class="form-group">
                              <input type="checkbox" id="<?php echo $lesson['id']; ?>" <?php if($lesson['lesson_type'] == 'quiz'):?> disabled <?php endif; ?> onchange="markThisLessonAsCompleted(this.id)" <?php if($lesson_progress == 1):?> checked  <?php endif; ?>>
                              <label for="<?php echo $lesson['id']; ?>"></label>
                            </div>

                            <a href="<?php echo site_url('addons/lessons/play/'.slugify($course_details['title']).'/'.$course_id.'/'.$lesson['id']); ?>" id = "<?php echo $lesson['id']; ?>" class="lst">
                              <?php echo $key+1; ?>:
                              <?php if ($lesson['lesson_type'] != 'other'):?>
                                <?php echo $lesson['title']; ?>
                              <?php else: ?>
                                <?php echo $lesson['title']; ?>
                                <i class="fa fa-paperclip"></i>
                              <?php endif; ?>
                            </a>

                            <div class="lesson_duration">
                              <?php if ($lesson['lesson_type'] == 'video' || $lesson['lesson_type'] == '' || $lesson['lesson_type'] == NULL): ?>
                                <i class="fa fa-play-circle"></i>
                                <?php echo readable_time_for_humans($lesson['duration']); ?>
                              <?php elseif($lesson['lesson_type'] == 'quiz'): ?>
                                <i class="fa fa-question-circle"></i> <?php echo get_phrase('quiz'); ?>
                              <?php else:
                                $tmp           = explode('.', $lesson['attachment']);
                                $fileExtension = strtolower(end($tmp));?>

                                <?php if ($fileExtension == 'jpg' || $fileExtension == 'jpeg' || $fileExtension == 'png' || $fileExtension == 'bmp' || $fileExtension == 'svg'): ?>
                                  <i class="fa fa-camera-retro"></i>  <?php echo get_phrase('attachment'); ?>
                                <?php elseif($fileExtension == 'pdf'): ?>
                                  <i class="fa fa-file-pdf"></i>  <?php echo get_phrase('attachment'); ?>
                                <?php elseif($fileExtension == 'doc' || $fileExtension == 'docx'): ?>
                                  <i class="fa fa-file-word"></i>  <?php echo get_phrase('attachment'); ?>
                                <?php elseif($fileExtension == 'txt'): ?>
                                  <i class="fa fa-file-alt"></i>  <?php echo get_phrase('attachment'); ?>
                                <?php else: ?>
                                  <i class="fa fa-file"></i>  <?php echo get_phrase('attachment'); ?>
                                <?php endif; ?>

                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </table>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <!-- Lesson Content ends from here -->
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .nav-link {
    color: #000 !important;
    font-size: 1.0rem;
    border: 1px solid #D9D9D9 !important;
    border-bottom: 1px solid transparent !important;
    border-radius: 15px 15px 0px 0px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background-color: #fff !important;
  }


  .card {
    border-radius: 0px 0px 15px 15px !important;
    border: 1px solid transparent !important;
  }

  .card-header {
  background-color: #fff !important;
  color: #000 !important;
  }

  .custom-accordion-title {
  color: #000 !important;
  padding-left: 20px !important;
  }

  .badge {
    display: inline-block;
    padding: .25em .4em;
    font-size: .75em;
    font-weight: 700;
    line-height: 1;
    color: #000 !important;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
  }

  .custom-accordion {
    color: #000 !important;
    font-size: 1.0rem;
    border: 1px solid #D9D9D9 !important;
    border-top: 1px solid transparent !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
  }

  .nav-tabs {
    border-bottom: 1px solid transparent !important;
  }

  .lesson_duration {
    color: #000 !important;
  }

  .lst {
    color: #000 !important;
  }

  .card-body {
    border: 1px solid transparent !important;
  }

input[type="checkbox"] + label:before {
    background-color: #fff; /* Unchecked background color */
    border: 2px solid #BFC2C9; /* Unchecked border color */
    border-radius: 4px; /* Optional: rounded corners */
  }

.form-group input:checked + label:after {
    content: "";
    display: block;
    position: absolute;
    top: 6px;
    left: 6px;
    width: 5px;
    height: 11px;
    border: solid #22A622;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
</style>