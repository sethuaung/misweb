<style>
    #show-detail-modal .modal-dialog{
        padding-left: 30%;
        padding-right: 30%;
        float:center;
    }
    table {
        border-collapse: collapse;
    }
    th{
        font-weight: bold;
        font-size:16px;
        padding-right: 15px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<div id="DivIdToPrintPop">
    <form method="GET" action="{{url('/admin/transfer_pop')}}">
    @csrf
        <div class="row">
            <div class="col-md-8">
                <input type="hidden" name="tran_id" value="{{ $tran_id }}"/>
                <input type="text" class="form-control" name="description" placeholder="Add Description"/>
            </div>
            <div class="col-md-4">
                <input type="submit" class="btn btn-primary" value="Print Transfer">
            </div>
        </div>
    </form>
</div>
