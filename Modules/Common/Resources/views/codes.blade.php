@extends('common::layouts.master')

@section('code')
active
@endsection
@section('content')
<div class="container-fluid  dashboard-content">
    <h3 class="text-center">Company Codes</h3>
    <!-- Button trigger modal -->
    <div class="text-right">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Add new Company
        </button>
    </div>
    
    <table class="table table-responsive">
        <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Company</th>
              <th scope="col">Code</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
              @foreach ($codes as $key => $code)
                <tr>
                    <th scope="row">{{++$key}}</th>
                    <td>{{$code->name}}</td>
                    <td>{{$code->code}}</td>
                    <td>
                        <a class="btn btn-danger" href="{{route('code.destroy', $code->id)}}" onclick="return confirm('Are you sure you want to delete this company?')">Delete</a>
                        <a class="btn btn-success text-white" data-toggle="modal" data-target="#updateModal{{$code->id}}">Update</a>
                    </td>
                </tr>




                <div class="modal fade" id="updateModal{{$code->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Update Company</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="{{route('code.update', $code->id)}}" method="POST">
                              @csrf
                              <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="{{$code->name}}" id="">
                              </div>
                
                              <div class="form-group">
                                <label for="">Code</label>
                                <input type="text" class="form-control" name="code" value="{{$code->code}}" id="">
                              </div>
                
                              <input type="submit" class="btn btn-primary">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
              @endforeach
          </tbody>
    </table>
</div>



@include('common::addcodemodal');  

@endsection
