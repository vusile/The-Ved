<div>
Are you sure you want to delete <span class = 'bold'><?php echo $title; ?> </span> from the <span class = 'bold'>Rates</span> database?

<?php echo anchor ('bureau/action/delete/' . $table . '/' . $id, 'Yes') ?> - 
<?php echo anchor ('bureau/ved/index/' . $table , 'No') ?>
</div>