
@if(count($rows)>0)
    <table class="table table-bordered" style="width: 100%">
        <thead>
        <tr  style="text-align: center;background:#03a9f4;color: white">
            <th>{{_t('Phone')}}</th>
            <th>{{_t('Customer Type')}}</th>
            <th>{{_t('Disbursement Date')}}</th>
            <th>{{_t('Co Name')}}</th>
            <th>{{_t('Branch')}}</th>
            <th>{{_t('Center')}}</th>
            <th>{{_t('Loan Type')}}</th>
            <th>{{_t('Rate')}}</th>
            <th>{{_t('Term')}}</th>
            <th>{{_t('Payment Term')}}</th>
            <th>{{_t('Loan Request')}}</th>
            <th>{{_t('Prince Outstanding')}}</th>
            <th>{{_t('Currency')}}</th>
            <th>{{_t('Status')}}</th>
            <th>{{_t('Cycle')}}</th>
        </tr>
        </thead>
        <tbody>
            @if($rows != null)
              @foreach($rows as $row)
                  <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                  </tr>
              @endforeach
            @endif
        </tbody>
    </table>

@else
    <h1 style="text-align: center;font-weight: bold">Data Not Fund</h1>
@endif