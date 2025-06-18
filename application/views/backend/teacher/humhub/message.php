
<style>
.iframe-container {
    flex: 1;
    height: calc(100vh - 120px); /* Ajustez selon votre header/footer */
    /* Largeur du menu */
}

iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: white;
}
</style>
<div class="row ">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body py-2">
        <h4 class="page-title"> <i class="dripicons-message title_icon"></i> <?php echo get_phrase('chat'); ?> </h4>
      </div> 
    </div> 
  </div>
</div>

<div class="iframe-container">
    <iframe src="https://humhub.wayo.site/mail/mail/index"></iframe>
</div>
