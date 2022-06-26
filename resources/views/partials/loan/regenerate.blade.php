@extends('backpack::layout')
@push('after_styles')
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
    <link rel="stylesheet" href="{{asset("vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
    <link href="{{ asset('vendor/adminlte/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
          type="text/css"/>
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    {{--<link rel="stylesheet" href="{{ asset('js/MonthPicker.css') }}">--}}

    <link href="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('content')

    <div id="success_message" class="ajax_response" style=""></div>
    <div class="row">
        <div class="col-md-3">
            <label for="time">Start Date</label>
            <input type="text" id="start_date" class="form-control" name="start_date" placeholder="Select Start Date" required>
            
        </div>
    
        <div class="col-md-3">
            <label for="end_time">End Date</label>
            <input type="text" id="end_date" class="form-control" name="end_date" placeholder="Select End Date" required>
            
        </div>
    
        <div class="col-md-12" style="margin-top:10px;">
            <button class="btn btn-success" type="submit" value="submit" id="btn-success">Regenerate</button>
        </div>
    </div>
    <hr>
    <h3>Regenerate One Schedule</h3>
    <div class="row">
        <div class="col-md-3">
            <label for="time">Start Date</label>
            <input type="text" id="start_date_one" class="form-control" name="start_date_one" placeholder="Select Start Date" required>
            
        </div>
    
        <div class="col-md-3">
            <label for="end_time">End Date</label>
            <input type="text" id="end_date_one" class="form-control" name="end_date_one" placeholder="Select End Date" required>
            
        </div>
    
        <div class="col-md-12" style="margin-top:10px;">
            <button class="btn btn-success" type="submit" value="submit" id="btn-success-one">Regenerate</button>
        </div>
    </div>
    <hr>
    <h3>Regenerate with Loan Number</h3>
    <div class="row">
        <div class="col-md-3">
            <label for="time">Loan Number</label>
            <input type="text" id="loan_number" class="form-control" name="loan_number" placeholder="Loan Number" required>
            
        </div>
    
        <div class="col-md-3">
            <label for="end_time">Client ID</label>
            <input type="text" id="client_id" class="form-control" name="client_id" placeholder="Client ID" required>
            
        </div>
    
        <div class="col-md-12" style="margin-top:10px;">
            <button class="btn btn-success" type="submit" value="submit" id="btn-success-loan">Regenerate</button>
        </div>
    </div>
    <hr>
    <h3>Loan Delete</h3>
    <div class="row">
        <div class="col-md-3">
            <label for="time">Loan Number</label>
            <input type="text" id="loan_number_delete" class="form-control" name="loan_number_delete" placeholder="Loan Number" required>
            
        </div>
    
        <div class="col-md-3">
            <label for="end_time">Client ID</label>
            <input type="text" id="client_id_delete" class="form-control" name="client_id_delete" placeholder="Client ID" required>
            
        </div>
    
        <div class="col-md-12" style="margin-top:10px;">
            <button class="btn btn-danger" type="submit" value="submit" id="btn-success-loan-delete">Delete Loan</button>
        </div>
    </div>
@endsection 


@section('after_scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $(function(){
           $('#start_date').datetimepicker({
               format: 'DD/MM/YYYY'
           });
       });

       $(function(){
           $('#end_date').datetimepicker({
               format: 'DD/MM/YYYY'
           });
       });

       $(function(){
           $('#btn-success').click(function(e){
            e.preventDefault();
             var start_date = $('[name="start_date"]').val();  
             var end_date = $('[name="end_date"]').val();
             $.ajax({
                type:'GET',
              url:'{{route('regenerateloan')}}',
              data:{start_date:start_date, end_date:end_date},
              success:function(data){
                $('#success_message').fadeIn().html("<div class='alert alert-success col-md-12'>Schedule Regenerated Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                  }, 2000 );
              }
             })
           })
       }) 
</script>
<script>
    $(function(){
           $('#start_date_one').datetimepicker({
               format: 'DD/MM/YYYY'
           });
       });

       $(function(){
           $('#end_date_one').datetimepicker({
               format: 'DD/MM/YYYY'
           });
       });

       $(function(){
           $('#btn-success-one').click(function(e){
            e.preventDefault();
             var start_date_one = $('[name="start_date_one"]').val();  
             var end_date_one = $('[name="end_date_one"]').val();
             $.ajax({
                type:'GET',
              url:'{{route('regenerateloanone')}}',
              data:{start_date_one:start_date_one, end_date_one:end_date_one},
              success:function(data){
                $('#success_message').fadeIn().html("<div class='alert alert-success col-md-12'>Schedule Regenerated Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                  }, 2000 );
              }
             })
           })
       }) 
</script>
<script>
    $(function(){
           $('#btn-success-loan').click(function(e){
            e.preventDefault();
             var loan_number = $('[name="loan_number"]').val();  
             var client_id = $('[name="client_id"]').val();
             $.ajax({
                type:'GET',
              url:'{{route('regenerateloannumber')}}',
              data:{loan_number:loan_number, client_id:client_id},
              success:function(data){
                $('#success_message').fadeIn().html("<div class='alert alert-success col-md-12'>Schedule Regenerated Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                  }, 2000 );
              }
             })
           })
       })

    $(function(){
           $('#btn-success-loan-delete').click(function(e){
            e.preventDefault();
             var loan_number_delete = $('[name="loan_number_delete"]').val();  
             var client_id_delete = $('[name="client_id_delete"]').val();
             $.ajax({
                type:'GET',
              url:'{{route('deleteloannumber')}}',
              data:{loan_number_delete:loan_number_delete, client_id_delete:client_id_delete},
              success:function(data){
                $('#success_message').fadeIn().html("<div class='alert alert-danger col-md-12'>Loan Deleted Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                  }, 2000 );
              }
             })
           })
       })     
</script>
<script src="{{ asset('vendor/adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

@endsection
    
    