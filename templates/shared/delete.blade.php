<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title">Delete Row</h4>
</div>
<div class="modal-body">
	<form method="post" action="{{{ URL::current() }}}" id="deleteRecord2">
		<input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
		<p>Are you sure want to delete the row?</p>
	</form>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
	<button type="button" class="btn btn-primary" onclick="$('#deleteRecord2').submit (); return false;">Yes</button>
</div>