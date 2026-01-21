
<?php $__env->startSection('title','Edit Employees'); ?>
<?php $__env->startSection('content'); ?>
<form method="post" action="index.php?action=update">
<input type="hidden" name="id" value="<?php echo e($employee['id']); ?>">
<input name="name" value="<?php echo e($employee['name']); ?>"><br><br>
<input name="title" value="<?php echo e($employee['title']); ?>"><br><br>
<input name="skills" value="<?php echo e($employee['skills']); ?>"><br><br>
<button>Update</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Workshop8\app\views/employee/edit.blade.php ENDPATH**/ ?>