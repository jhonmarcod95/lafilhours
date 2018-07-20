<!-- Modal -->
<div class="modal fade" id="ModalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="Formdelete" method="post">
        {{ csrf_field() }}
        <input id="Scheduleid" type="hidden" name="Scheduleid" value="" >
        <div class="modal-body">
          Are you sure to delete this schedule
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Ok">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          
        </div>
      </form>
    </div>
  </div>
</div>