  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Share Code</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
           @if(Sentinel::check())
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

              <div class="form-group">
                <label style="color: black !important;" for="">End Date</label>
                <input style="background: white !important; color:black!important;" type="date" class="form-control" name="date" id="">
              </div>

              <input type="submit" class="btn btn-primary">
          </form>
          @else
            <span>Please <a class="text-success" href="{{route('site.login.form')}}">login</a> to share Codes.</span>
          @endif
        </div>
      </div>
    </div>
  </div>