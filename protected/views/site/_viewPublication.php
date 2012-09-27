<div class="view">
    <?php echo $data->atcontent_embed_code; ?>
    <br />
    <a href="<?php echo Yii::app()->createUrl('site/viewPublication', $params = array('id'=>$data->id)) ?>">View publication</a>
</div>