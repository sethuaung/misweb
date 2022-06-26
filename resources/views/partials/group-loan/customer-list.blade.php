<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>


    <?php $base=asset('vendor/adminlte') ?>
    <link rel="stylesheet" href="{{$base}}/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
</head>
<body>
    <?php
        $group_loan_id = request()->group_loan_id? request()->group_loan_id :0;
        $group_loan_detail = \App\Models\GroupLoanDetail::where('group_loan_id',$group_loan_id)->get();

        $loan =  \App\Models\Loan::where('group_loan_id',$group_loan_id)
            ->where('disbursement_status','Pending')
            ->get();
    ?>
<div class="container">
    <div class="row" style="margin-top: 20px">
       <form action="{{url('admin/disbursement-pending-approval')}}" method="post">
           <input type="hidden" name="_token" value="{{csrf_token()}}">
           <table class="table table-bordered" style="width: 100%">
               <thead>
               <tr>
                   <th>Branch Name</th>
                   <th>Center Name</th>
                   <th>Client ID</th>
                   <th>Client Name</th>
                   <th>Principle</th>
                   <th>Interest</th>
                   <th>Loan Officer</th>
                   <th>Loan Type</th>
                   <th>Applicant No</th>
                   <th>Applicant Name</th>
                   <th>Total Loans</th>

                   <th>
                       <input type="checkbox" value="" id="check_all" class="c-checked-group">
                   </th>
               </tr>
               </thead>
               <tbody>

               <?php
               $total =  0;

               ?>
               @if($loan != null)
                   <?php
//                        dd($loan);
                   ?>
                   @foreach($loan as $row)
                       <?php
                           $client = \App\Models\Client::find($row->client_id);
                           $rand_id = rand(1,9999).time().rand(1,9999);
                           $branch=\App\Models\Branch::find($row->branch_id);
                           $center=\App\Models\CenterLeader::find($row->center_leader_id);
                           $loan_officer=\App\Models\UserU::find($row->loan_officer_id);
                           $loan_product=\App\Models\LoanProduct::find($row->loan_production_id);
                           $principle=\App\Models\LoanCalculate::where('disbursement_id',$row->id)->sum('principal_s');
                           $interest=\App\Models\LoanCalculate::where('disbursement_id',$row->id)->sum('interest_s');

                       ?>

                       <tr>
                           <td>{{optional($branch)->title}}</td>
                           <td>{{optional($center)->title}}</td>
                           <td>{{optional($client)->client_number}}</td>
                           <td>
                               @if (optional($client)->name_other != null)
                                   {{optional($client)->name_other}}
                               @else
                                   {{optional($client)->name}}
                               @endif
                           </td>
                           <td>{{$principle}}</td>
                           <td>{{$interest}}</td>
                           <td>{{optional($loan_officer)->name}}</td>
                           <td>{{optional($loan_product)->name}}</td>
                           <td>{{$row->disbursement_number}}</td>
                           <td>{{optional($client)->name}}</td>
                           <td>{{$row->loan_amount}}</td>
                           <td>
                               <input type="checkbox" data-id="{{$rand_id}}"  name="checked[{{$rand_id}}]" value="{{$rand_id}}" class="c-checked">
                               <input type="hidden" name="loan_id[{{$rand_id}}]" value="{{$row->id}}">
                           </td>
                       </tr>

                       <?php
                          $total += $row->loan_amount;
                       ?>
                   @endforeach
               @endif
               <tr>
                   <td style="text-align: right" colspan="2">Total</td>
                   <td>{{$total}}</td>
               </tr>
               </tbody>
           </table>
           <div class="row">
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>Note</label>
                       <input class="form-control" name="approve_note">
                   </div>
               </div>
               <div class="col-sm-4">
                   <div class="form-group">
                       <label>Date</label>
                       <span>
                        <input  class="form-control approve_date" name="approve_date" value="{{date('Y-m-d')}}"></span>
                       <span></span>
                   </div>
               </div>
               <div class="col-sm-4">
                   <input type="submit" class="btn btn-sm btn-primary" style="margin-top: 28px;" value="Approve" />
               </div>
           </div>
       </form>
    </div>
</div>


<script src="{{$base}}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        // $('.select-province').select2();
        $('.c-checked-group').on('change',function(event) {

            if(this.checked) { // check select status
                $('.c-checked').each(function() {
                    this.checked = true;  //select all

                });
            }else{
                $('.c-checked').each(function() {
                    this.checked = false; //deselect all
                });
            }
        });
        $('.c-checked').on('change',function () {

        });

    });
    $(function () {
        $('.approve_date').datepicker({
                format: 'yyyy-mm-dd',
            }
        );
    });
</script>
</body>
</html>