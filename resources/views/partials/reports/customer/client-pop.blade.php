<?php
$uid = time() . rand(1, 9999) . rand(1, 9999);
$imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz', 'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm', 'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'];
?>
<style>
    table {
        border-collapse: collapse;
    }

    .border th, .border td {
        border: 1px solid rgba(188, 188, 188, 0.96);
        padding: 5px;
    }

    .right {
        text-align: right;
    }

    .my-table tr td {
        font-size: 14px;
        padding: 10px;
    }

    .my-table th{
        font-weight: bold;
        font-size:16px;
        background-color: #d2d6de;
        text-align: center;
    }

    a .active{
        font-weight: bold;
        color: #333;
    }

    td:nth-child(odd){
        color: #333;
        font-weight: bold;
        font-size:16px;
    }

    ul li{
        list-style: none;
    }
</style>
<style>
    #popup{{  $uid }} {
        background-color: white;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 9999;
        display: none;
        overflow: hidden;
        -webkit-overflow-scrolling: touch;
        outline: 0;
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4)
    }
    #iframe{{  $uid }} {
        border: 0;
        width: 100%;
        height: 100%;
        /*margin: 30px auto;*/
        position: relative;
        display: block;
        box-shadow: 0 2px 3px rgba(0,0,0,0.125);
    }

     #page{{  $uid }} { height: 100%; }

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

@if($row != null)
    {{--{{dd($row)}}--}}
<?php
    $client = optional(\App\Models\Client::find($row->id));
    //            $guarantor = optional(\App\Models\Guarantor::find($row->guarantor_id));

    $province = $client->province;
    $district = $client->district;
    $commune = $client->commune;
    $village = $client->village;

    $employee = $client->employee;

    $surveys = \App\Models\OwnershipFarmland::find($client->ownership_of_farmland);
    $ownerships = \App\Models\Ownership::find($client->ownership);

    $loan_detail = \App\Models\Loan::where('client_id', $row->id)->orderBy('id')->get();
    $c = $loan_detail != null ? count($loan_detail) : 0;

    // $saving_detail = \App\Models\LoanCompulsory::where('client_id', $row->id)->orderBy('id')->get();
    // $c = $saving_detail != null ? count($saving_detail) : 0;

    $histories = $client->histories;
?>
<div class="container-fluid">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#personal">Personal Detail</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#family">Family Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#address">Address</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#employee">Employee Status</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#survey">Survey And Ownership</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#kyc">KYC Document</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#image">Image</a>
        </li>
        @if(companyReportPart() == 'company.moeyan')
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#guarantor">Guarantor</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#inspector">Inspector</a>
        </li>
        @endif
    </ul>
</div>
<br>
<div class="tab-content">
<div id="personal"  class="container-fluid tab-pane active">
    <div class="clear-{{  $uid }}">

        <table class="table table-striped" width="100%" class="my-table">

            <tr>
                <td></td>
                <td></td>
                <td>Client Name</td>
                <td name="name_other"> - {{$client->name_other}}</td>
            </tr>
            <tr>
                <td>NRC</td>
                <td name="nrc_number"> - {{$client->nrc_number}}</td>

                <td>Father's Name</td>
                <td name="father_name"> - {{$client->father_name}}</td>

                <td rowspan="6" width="300"><img src="{{asset($client->photo_of_client)}}" width="200" alt=""></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td  name="dob"> - {{$client->dob}}</td>
                <td>Phone No</td>
                <td  name="primary_phone_number"> - {{$client->primary_phone_number}}</td>
            </tr>

            <tr>
                <td>Marital Status</td>
                <td  name="marital_status"> - {{$client->marital_status}}</td>
                <td>Husband Name</td>
                <td  name="husband_name"> - {{$client->husband_name}}</td>
            </tr>
            <tr>
                <td>Position</td>
                <td  name="occupation_of_husband"> - {{$client->occupation_of_husband}}</td>

                <td>School Name</td>
                <td>xxx</td>
            </tr>
            {{--<tr>
                <td>Interest Rate</td>
                <td  name="interest_rate"> - {{$row->interest_rate}}</td>
                <td>Last Repayment Month</td>
                <td>31232</td>
            </tr>
            <tr>
                <td>Repayment Schedule</td>
                <td  name="repayment_term"> - {{$row->repayment_term}}</td>
            </tr>--}}


        </table>
        <br>
        <br>
        <div><h3>Loan List</h3></div>
        <table class="table table-striped" width="100%" class="my-table">
            <thead class="border">
            <tr>
                <th>Loan ID</th>
                <th>Application Date</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Payment</th>
                <th>Business Proposal</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody class="border">

            @if($loan_detail != null)

                @foreach($loan_detail as $r)
                    <tr>
                        <td>{{$r->disbursement_number}}</td>
                        <td>{{date('d-m-Y', strtotime($r->loan_application_date))}}</td>
                        <td class="right">{{number_format(optional($r)->loan_amount)}}</td>
                        <td class="right">{{number_format(optional($r)->interest_receivable)}}</td>
                        <td class="right">{{number_format(optional($r)->interest_repayment)}}</td>
                        <td class="right">{{$r->business_proposal}}</td>
                        <td class="right">{{$r->disbursement_status}}</td>
                    </tr>

                @endforeach

            @endif

            </tbody>
        </table>

        <br>
        <div><h3>Saving List</h3></div>
        <table class="table table-striped" width="100%" class="my-table">
            <thead class="border">
            <tr>
                <th>Loan ID</th>
                <th>Saving Amount</th>
                <th>Interest Rate</th>
                <th>Interest Amount</th>
                <th>Saving Number</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody class="border">

            @if($loan_detail != null)

                @foreach($loan_detail as $r)
                
                <?php
                    $saving = \App\Models\LoanCompulsory::where('loan_id', $r->id)->first();
                    // $c = $saving_detail != null ? count($saving_detail) : 0;
                    // $saving_amount = ($r->loan_amount * $saving->saving_amount) / 100;
                ?>
                    <tr>
                        <td>{{$r->disbursement_number}}</td>
                        @if ($saving != null) 
                            <td class="right">{{number_format(optional($saving)->principles)}}</td>
                            <td class="right">{{$saving->interest_rate}}</td>
                            <td class="right">{{number_format(optional($saving)->interests)}}</td>
                            <td class="right">{{$saving->compulsory_number}}</td>
                            <td class="right">{{$saving->compulsory_status}}</td>
                        @else
                             <td class="right"></td>
                             <td class="right"></td>
                             <td class="right"></td>
                             <td class="right"></td>
                             <td class="right"></td>  
                        @endif
                        
                    </tr>

                @endforeach

            @endif


            </tbody>
        </table>

    </div>
</div>

<div id="family" class="container-fluid tab-pane fade">
    <div class="clear-{{  $uid }}">
    <table class="table table-striped" width="100%" class="my-table">
        <tr>
            <td>Marital Stauts</td>
            <td  name="marital_status"> - {{$client->marital_status}}</td>

            <td>Father Name</td>
            <td  name="father_name"> - {{$client->father_name}}</td>

            <td>Spouse Name</td>
            <td  name="husband_name"> - {{$client->husband_name}}</td>

            <td>Occupation of husband</td>
            <td  name="occupation_of_husband"> - {{$client->occupation_of_husband}}</td>
        </tr>
        <tr>
            <td>No children in family</td>
            <td  name="no_children_in_family"> - {{$client->no_children_in_family}}</td>

            <td>No of working people</td>
            <td  name="no_of_working_people"> - {{$client->no_of_working_people}}</td>

            <td>No of dependent</td>
            <td  name="no_of_dependent"> - {{$client->no_of_dependent}}</td>

            <td>No of person in family</td>
            <td  name="no_of_person_in_family"> - {{$client->no_of_person_in_family}}</td>
        </tr>

    </table>
    </div>
</div>

<div id="address" class="container-fluid tab-pane fade">
    <div class="clear-{{  $uid }}" id="address">
        <table class="table table-striped" width="100%" class="my-table">
        <tr>
                <td>State / Region</td>
                <td> - {{$province}}</td>

                <td>District / Division</td>
                <td> - {{$district}}</td>

                <td>Township</td>
                <td> - {{$commune}}</td>

                <td>Town / Village / Group Village</td>
                <td> - {{$village}}</td>
        </tr>
        <tr>
                <td>Ward / Small Village</td>
                <td> - {{$village}}</td>

                <td>House Number</td>
                <td> - {{$client->house_number}}</td>

                <td>Address</td>
                <td> - {{$client->address1}}</td>

                <td>Address 2</td>
                <td> - {{$client->address_2}}</td>
        </tr>
        </table>
    </div>
</div>

@if ($employee)
<div id="employee" class="container-fluid tab-pane fade">
<div class="clear-{{  $uid }}">
        <table class="table table-striped" width="100%" class="my-table">
        <tr>
            <td>Position</td>
            <td> - {{$employee->position}}</td>

            <td>Employee Status</td>
            <td> - {{$employee->employment_status}}</td>

            <td>Employee Industry</td>
            <td> - {{$employee->employment_industry}}</td>

            <td>Seniority Level</td>
            <td> - {{$employee->senior_level}}</td>
        </tr>
        <tr>
            <td>Company Name</td>
            <td> - {{$employee->company_name}}</td>

            <td>Branch</td>
            <td> - {{$employee->branch}}</td>

            <td>Department</td>
            <td> - {{$employee->department}}</td>

            <td>Work Phone</td>
            <td> - {{$employee->work_phone}}</td>
        </tr>
        <tr>
            <td>Work Day</td>
            <td> - {{$employee->work_day}}</td>

            <td>Basic Salary</td>
            <td> - {{$employee->basic_salary}}</td>

            <td>Company Address</td>
            <td> - {{$client->company_address1}}</td>
        </tr>
    </table>
</div>
</div>
@endif

<div id="survey" class="container tab-pane fade">
<div class="clear-{{  $uid }}">
    <div class="row">
        <h3>Survey</h3>
        <label for="">Ownership of farmland</label>
        <ul>
        <?php
        if($surveys){
         foreach($surveys as $survey){
            ?>
            <li>
                <input type="checkbox" class="form-check-input" name="survey" value="{{$survey->name}}" checked disabled/>{{$survey->name}}<br>
            </li>
        <?php } 
        }?>
        </ul>
    </div>

    <div class="row">
        <h3>Ownerships</h3>
        <ul>
        <?php
        if($ownerships){
         foreach($ownerships as $ownership){
            ?>
            <li>
                <input type="checkbox" class="form-check-input" name="ownership" value="{{$ownership->name}}" checked disabled/>{{$ownership->name}}<br>
            </li>
        <?php } 
        }?>
        </ul>
    </div>
</div>
</div>

<div id="kyc" class="container-fluid tab-pane fade" style="margin-left:80px;">
    <div class="clear-{{  $uid }}">
        <div class="row" style="margin-bottom:50px;">
            <?php
            $default_docs = array('Family Registration' => $client->family_registration_copy, 'NRC Photo' => $client->nrc_photo, 'Finger Print' => $client->scan_finger_print);
            foreach($default_docs as $key => $default_doc){
                if($default_doc){
                    echo "<div class='col-md-4'>";
                    echo "<h2>$key</h2>";
                    foreach($default_doc as $doc){
                        $explodeImage = explode('.', $doc);
                        $extension = end($explodeImage);
                        ?>
                        @if(in_array($extension, $imageExtensions))
                        <a href="{{asset($doc)}}" title="{{$key}}">
                            <img src="{{asset($doc)}}" alt="{{$key}}" width="400">
                        </a>
                        @else
                        <br>
                        <a target="_blank" href="{{asset($doc)}}" title="{{$key}} Copy">
                            <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 1.63em;"></i>
                        <label for=""> {{$doc}}</label>
                        </a><br>
                        @endif
                    <?php }
                    echo "</div>";
                }
            }?>
            </div>

            <?php 
            if(companyReportPart() == 'company.moeyan'){?>
            <div class="row"  style="margin-bottom:50px;">
            <?php
            $default_docs = array('Form Photo (F)' => $client->form_photo_front, 'Form Photo (B)' => $client->form_photo_back, 'Company Letter Head' => $client->company_letter_head,
                            'Community Recommendation' => $client->community_recommendation, 'Employment Certificate' => $client->employment_certificate, 'Other Document' => $client->other_document);
            foreach($default_docs as $key => $default_doc){
                if($default_doc){
                    echo "<div class='col-md-4'>";
                    echo "<h2>$key</h2>";
                    foreach($default_doc as $doc){
                        $explodeImage = explode('.', $doc);
                        $extension = end($explodeImage);
                        ?>
                        @if(in_array($extension, $imageExtensions))
                        <a href="{{asset($doc)}}" title="{{$key}}">
                            <img src="{{asset($doc)}}" alt="{{$key}}" width="400">
                        </a>
                        @else
                        <br>
                        <a target="_blank" href="{{asset($doc)}}" title="{{$key}} Copy">
                            <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 1.63em;"></i>
                        <label for=""> {{$doc}}</label>
                        </a><br>
                        @endif
                    <?php }
                    echo "</div>";
                }
            }?>
            </div>
        <?php  } ?>
        </div>
</div>

<div id="image" class="container tab-pane fade">
    <div class="clear-{{ $uid }}">
        <div class="row">
            <div class="col-md-4">

            @if($client->photo_of_client)
            <a download="client.jpg" href="{{asset($client->photo_of_client)}}" title="Photo of client">
            <label for="">Photo of client - </label>
                <i class="fa fa-arrow-circle-down" aria-hidden="true" style="font-size: 1.63em;"></i>
            </a>
                    <br>
                    <br>
            <a href="{{asset($client->photo_of_client)}}" title="Photo of client">
            <img src="{{asset($client->photo_of_client)}}" alt="Photo of client" width="400">
            </a>
            @endif
            </div>
        </div>
    </div>
</div>

@if(companyReportPart() == 'company.moeyan')
<div id="guarantor" class="container tab-pane fade">
    <div class="clear-{{ $uid }}">
        <div class="row">
            <div class="col-md-10">
            <table class="table table-striped" width="100%" class="my-table">
            <tbody class="border">
            <tr>
            <td>Guarantor One</td>
                @foreach ($loan_detail as $loan)
                    @if($loan->guarantor_id)
                    <td style="border:solid 1px black;">{{$loan->disbursement_number}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
            <td></td>
                @foreach ($loan_detail as $loan)
                    @if($loan->guarantor_id)
                    @php
                    $guarantor = \App\Models\Guarantor::select('full_name_en')->where('id',$loan->guarantor_id)->first();
                    @endphp
                        @if($guarantor)
                            <td style="border:solid 1px black;">{{$guarantor->full_name_en}}</td>
                        @else
                            <td style="border:solid 1px black;"></td>
                        @endif
                    @endif
                @endforeach
                </tr>
                <tr>
                <td>Guarantor Two</td>
                @foreach ($loan_detail as $loan)
                    @if($loan->guarantor2_id)
                    <td style="border:solid 1px black;">{{$loan->disbursement_number}}</td>
                    @endif
                @endforeach
            </tr>
            <td></td>
                @foreach ($loan_detail as $loan)
                    @if($loan->guarantor2_id)
                    @php
                    $guarantor2 = \App\Models\Guarantor::select('full_name_en')->where('id',$loan->guarantor2_id)->first();
                    @endphp
                        @if($guarantor2)
                            <td style="border:solid 1px black;">{{$guarantor2->full_name_en}}</td>
                        @else
                            <td style="border:solid 1px black;"></td>
                        @endif
                    @endif
                @endforeach
                </tr>
            </tbody>
        </table>   
            </div>
        </div>
    </div>
</div>
<div id="inspector" class="container-fluid tab-pane fade">
    <div class="clear-{{ $uid }}">
        <div class="row">
            <div class="col-md-10">
            <table class="table table-striped" width="100%" class="my-table">
            <tbody class="border">
            <tr>
            <td>Inspector One</td>
                @foreach ($loan_detail as $loan)
                    @if($loan->inspector_id)
                    <td style="border:solid 1px black;">{{$loan->disbursement_number}}</td>
                    @endif
                @endforeach
            </tr>
            <tr>
            <td></td>
                @foreach ($loan_detail as $loan)
                    @if($loan->inspector_id)
                    @php
                    $inspector = \App\Models\Inspector::select('full_name_en')->where('id',$loan->inspector_id)->first();
                    @endphp
                        @if($inspector)
                            <td style="border:solid 1px black;">{{$inspector->full_name_en}}</td>
                        @else
                            <td style="border:solid 1px black;"></td>
                        @endif
                    @endif
                @endforeach
                </tr>
                <tr>
                <td>Inspector Two</td>
                @foreach ($loan_detail as $loan)
                    @if($loan->inspector2_id)
                    <td style="border:solid 1px black;">{{$loan->disbursement_number}}</td>
                    @endif
                @endforeach
            </tr>
            <td></td>
                @foreach ($loan_detail as $loan)
                    @if($loan->inspector2_id)
                    @php
                    $inspector2 = \App\Models\Inspector::select('full_name_en')->where('id',$loan->inspector2_id)->first();
                    @endphp
                        @if($inspector2)
                            <td style="border:solid 1px black;">{{$inspector2->full_name_en}}</td>
                        @else
                            <td style="border:solid 1px black;"></td>
                        @endif
                    @endif
                @endforeach
                </tr>
            </tbody>
        </table>   
            </div>
        </div>
    </div>
</div>
@endif
</div>

<script>
$(document).ready(function(){
    var histories = <?php echo $histories?>;
    $.each(histories.reverse(), function( index, history) {

        if(history.edited_at == 'address1' || history.edited_at == 'address2'){
            $("#address").append(
                `<div>
                    <label for="${history.edited_at}">Date ${history.created_at}</label>
                    <br>
                    <span id="${history.edited_at}">
                          ${history.value}
                    </span>
                </div><br>`);
        }
        else{
            $("td[name="+history.edited_at+"]").append(`
            <span class="d-inline-block"> <button class='btn btn-primary btn-xs  dropdown-toggle'  data-toggle="dropdown" aria-haspopup='true' aria-expanded="false" id='${history.id}'><i class='fa fa-history'></i></button>
                <div class="dropdown-menu">
                    <span> Date : ${history.created_at}  - </span>
                    <a class="dropdown-item" href="#">${history.value}</a>
                </div>
            </span>`);
        }
    });
})
</script>
@endif
