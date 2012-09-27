<?php
/* @var $this PublicationController */
/* @var $data Publication */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo date('m.d.Y h:i a', $data->create_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_time')); ?>:</b>
	<?php echo date('m.d.Y h:i a', $data->update_time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('atcontent_publication_id')); ?>:</b>
	<?php echo CHtml::encode($data->atcontent_publication_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('author_id')); ?>:</b>
	<?php echo CHtml::encode($data->author_id); ?>
	<br />

	*/ ?>

</div>