@extends('backend.master')

@section('title', 'Edit Customer')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.customers.update',$customer->id) }}" method="post" class="accountForm"
      enctype="multipart/form-data">
      @method('PUT')
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Name
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter title" name="name"
              value="{{ $customer->name }}" required>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Phone
              <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" placeholder="Enter phone" name="phone"
              value="{{ $customer->phone }}" required>
          </div>
         <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Email Address
            </label>
            <input type="email" class="form-control" placeholder="Enter Email Address" name="email_address"
              value="{{ $customer->email_address }}">
          </div>
          <div class="mb-3 col-md-6">
            <label for="dob" class="form-label">
              DOB
            </label>
            <div class="input-group date" id="reservationdate" data-target-input="nearest">
              <input type="text" placeholder="Enter product expire date" class="form-control datetimepicker-input" data-target="#reservationdate" name="dob" value="{{ $customer->dob }}" />
              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">Update</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush

@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
<script>
  $(function() {
    //Date picker
    $('#reservationdate').datetimepicker({
      format: 'YYYY-MM-DD'
    });
  })
</script>
@endpush