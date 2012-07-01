<div id="error-wrapper" align="center">
	
    <div id="error-cont">
    
    	<img src="<?=BASE_URL;?>/images/error-128.png" width="" height="" border="0" />
    	<pre>
		<?php //print_r($viewParams);?>
        </pre>
        <div id="error-message" align="left">
        	<div class="title">Page Not Found</div>
            <div class="details">
            <span>&nbsp; <?=BASE_URL;?></span><br>
            &nbsp; &nbsp;<?='./' . $_REQUEST['url'];?>
            
            </div>
            
            <div class="debug">
       		<span>debugging info</span><br>
            <?php 
			if ( is_file($viewParams['controller_file']) )
			{
			?>
				<?=str_replace('_', '.', $viewParams['controller']) . '::' . $viewParams['method'];?><br />
                <?=str_replace(BASE_DIR, '', $viewParams['controller_file']);?>
            <?php
			}
			else
			{
			?>
            File Not Found:  <?=str_replace(BASE_DIR, '', $viewParams['controller_file']);?>
            <?php
			}
			?>
            
            </div>
        </div>
        <div id="error-footer">
        	<div class="left">&copy; <?=date('Y');?> <?=$this->core->config->copyright;?></div>
            <div class="right">built on ducksoup <?=Version::getVersion();?></div>
        </div>
    </div>
</div>

