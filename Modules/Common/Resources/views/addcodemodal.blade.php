  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Submit Code</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('code.store')}}" method="POST">
              @csrf
              <div class="form-group">
                <label style="color: black !important;" for="">Betting Company</label>
                <input style="background: white !important; color:black!important;" type="text" class="form-control" name="name" id="">
              </div>

              <div class="form-group">
                <label style="color: black !important;" for="">Code</label>
                <input style="background: white !important; color:black!important;" type="text" class="form-control" name="code" id="">
              </div>

              <input type="submit" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
  </div>