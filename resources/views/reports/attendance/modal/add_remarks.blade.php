@if(Auth::privelege()->attendance_control)
<!-- Modal -->
<div class="modal fade" id="ModalRemarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Remarks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{ url('/remarks') }}">
        {{ csrf_field() }}
        <input id="Logid" type="hidden" name="Logid" value="" >
        <div class="modal-body">
          <textarea name="remarks" class="form-control" id="remarks" rows="5" maxlength="200" placeholder="Input Some text here..." required></textarea>
          <!-- <input id="remarks" type="text" name="remarks" class="form-control input-sm" placeholder="Input Some text here..." maxlength="25" required> -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" value="Save changes">
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@if(Auth::user()->user_type == "1")
<div class="modal fade" id="ModalViewRemarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remarks Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{ url('/remarks/delete') }}">
        {{ csrf_field() }}
        <input id="remarksID" type="hidden" name="remarksID" value="" >
        <div class="modal-body">
          <textarea name="text_remarks" class="form-control" id="text_remarks" rows="5" maxlength="200" required></textarea>
        </div>
        <div class="modal-footer">
          @if(Auth::privelege()->attendance_control)
            <input type="submit" class="btn btn-danger" value="Delete Remarks">
          @endif
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@if(Auth::user()->user_type == "2")
<div class="modal fade" id="ModalViewSecondRemarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Remarks Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{ url('/remarks2/delete') }}">
        {{ csrf_field() }}
        <input id="secondRemarksID" type="hidden" name="remarksID" value="" >
        <div class="modal-body">
          <textarea name="text_remarks" class="form-control" id="second_text_remarks" rows="5" maxlength="200" required></textarea>
        </div>
        <div class="modal-footer">
          @if(Auth::privelege()->attendance_control)
            <input type="submit" class="btn btn-danger" value="Delete Remarks">
          @endif
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif