<?php $languages = $this->settings_model->get_all_languages(); ?>
<?php foreach ($languages as $language): ?>
    <a 
        href="<?php echo route('language/active/' . $language); ?>" 
        class="dropdown-item notify-item"
    >
        <span class="<?php if(get_user_language() == $language): ?>badge badge-secondary-lighten<?php endif; ?>">
            <?php echo ucfirst($language); ?>
        </span>
    </a>
<?php endforeach; ?>