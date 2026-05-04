<?php 
use pluginbrandslug\framework\monolog\d1g1MonologFunction;

?>
<style>
.box-button {
    position:absolute;
    right:30px;
    top:99px;
    margin:10px;
}




</style>
<div class="wrap">
    <div class="col-12">
        <div class="box-info">
            <div class="box-header">
                <h3 class="box-title"><?php _e( 'Záznamy logu', 'plan' ); ?></h3>
                <?php 
                $settings = new d1g1MonologFunction;
                $directory = $settings->d1g1_get_log_folder();
                $file = $directory . '/' . D1G1_BRANDING . '.json';
                if(file_exists($file)){ ?>
                    <div class="box-button"><a href="<?php echo '/wp-content/uploads/'. D1G1_LFILEDIR_BRAND .'/'. D1G1_BRANDING .'.json'; ?>" class="button button-primary d1g1-plugins-button" download>Stahnout LOG</a></div>
                <?php } ?>

            </div>
            <div class="box-body">
                <?php 
        
                echo $settings->d1g1_get_log_file();
              
                ?>
            </div>
        </div>                 
    </div>
</div>
<div style="clear:both;"></div>  