<!-- select2 from ajax -->
@php
    $r_id = rand(1,9999);

    $state = null;
    $district = null;
    $township = null;
    $village = null;
    $ward = null;
    $house_number = null;
    $address = null;

    if(isset($entry)){
        if($entry != null){
            $state = isset($entry->{$field['state_name']})?$entry->{$field['state_name']} : null;
            $district = isset($entry->{$field['district_name']})?$entry->{$field['district_name']} : null;
            $township = isset($entry->{$field['township_name']})?$entry->{$field['township_name']} : null;
            $village = isset($entry->{$field['village_name']})?$entry->{$field['village_name']} : null;
            $ward = isset($entry->{$field['ward_name']})?$entry->{$field['ward_name']} : null;

        }
    }


    $f_id1 = isset($field['state_name'])? str_replace(']','',str_replace('[','',$field['state_name'])) :'state_name';
    $f_id2 = isset($field['district_name'])? str_replace(']','',str_replace('[','',$field['district_name'])) :'district_name';
    $f_id3 = isset($field['township_name'])? str_replace(']','',str_replace('[','',$field['township_name'])) :'township_name';
    $f_id4 = isset($field['village_name'])? str_replace(']','',str_replace('[','',$field['village_name'])) :'village_name';
    $f_id5 = isset($field['ward_name'])? str_replace(']','',str_replace('[','',$field['ward_name'])) :'ward_name';

    $old_value_state = old($field['state_name']) ? old($field['state_name']) : ($state != null ? $state : (isset($field['state_default']) ? $field['state_default'] : false ));
    $old_value_district = old($field['district_name']) ? old($field['district_name']) : ($district != null ? $district : (isset($field['district_default']) ? $field['district_default'] : false ));
    $old_value_township = old($field['township_name']) ? old($field['township_name']) : ($township != null ? $township : (isset($field['township_default']) ? $field['township_default'] : false ));
    $old_value_village = old($field['village_name']) ? old($field['village_name']) : ($village != null ? $village : (isset($field['village_default']) ? $field['village_default'] : false ));
    $old_value_ward = old($field['ward_name']) ? old($field['ward_name']) : ($ward != null ? $ward : (isset($field['ward_default']) ? $field['ward_default'] : false ));
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('State') }} / {{_t('Region')}}</label>
    <select
            name="{{ $field['state_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id1.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_state)
            @php
                $item = \App\Address::find($old_value_state);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
</div>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('District') }} / {{_t('Division')}}</label>
    <div class="input-group">
    <select
            name="{{ $field['district_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id2.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_district)
            @php
                $item = \App\Address::find($old_value_district);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
    <div class="input-group-addon">
            <a href="" data-remote="false" data-toggle="modal" data-target="#show-create-district"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Township') }}</label>
    <div class="input-group">
    <select
            name="{{ $field['township_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id3.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_township)
            @php
                $item = \App\Address::find($old_value_township);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
    <div class="input-group-addon">
            <a href="" data-remote="false" data-toggle="modal" data-target="#show-create-township"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Town') }} / {{ _t('Village') }} / {{ _t('Group Village') }}</label>
    <div class="input-group">
    <select
            name="{{ $field['village_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id4.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_village)
            @php
                $item = \App\Address::find($old_value_village);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
    <div class="input-group-addon">
            <a href="" data-remote="false" data-toggle="modal" data-target="#show-create-village"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{{ _t('Ward') }} / {{ _t('Small Village') }}</label>
    <div class="input-group">
    <select
            name="{{ $field['ward_name'] }}"
            style="width: 100%"
            id="select2_ajax_{{ $f_id5.$r_id }}"
            @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >
        @if ($old_value_ward)
            @php
                $item = \App\Address::find($old_value_ward);
            @endphp
            @if ($item != null)
                <option value="{{ $item->code }}" selected>
                    {{ $item->name }} / {{ $item->description }}
                </option>
            @endif
        @endif
    </select>
    <div class="input-group-addon">
            <a href="" data-remote="false" data-toggle="modal" data-target="#show-create-ward"><span class="glyphicon glyphicon-plus"></span></a>
        </div>
    </div>
</div>

@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

{{--  State modal start here --}}
<div class="modal fade" id="show-create-district" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Add District/Division</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form >
                <div id="success_message" class="ajax_response" style=""></div>
                <div class="form-group">
                    <label>District/Division:</label>
                    <input type="text" name="district" id="district" class="form-control" placeholder="District / Division" required="Please add district">
                    <input type="hidden" name="state" id="state_name" required> 
                </div>

                <div class="form-group">
                    <label>Name in Myanmar:</label>
                    <input type="text" name="name_in_myanmar" id="name_in_myanmar" class="form-control" placeholder="Name in Myanmar" required="Please add myanmar name">
                </div>
    
                <div class="form-group">
                    <button class="btn btn-success btn-submit">Submit</button>
                </div>
    
            </form>
        </div>
      </div>
    </div>
   </div> 
    {{--  State modal end here --}}

    {{--  Township modal start here --}}
    <div class="modal fade" id="show-create-township" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Add Township</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form >
                <div id="success_message_township" class="ajax_response" style=""></div>
                <div class="form-group">
                    <label>Township:</label>
                    <input type="text" name="township" id="township" class="form-control" placeholder="Township" required="Please add township">
                    <input type="hidden" name="district_township" id="district_township" required> 
                </div>

                <div class="form-group">
                    <label>Name in Myanmar:</label>
                    <input type="text" name="name_in_myanmar_township" id="name_in_myanmar_township" class="form-control" placeholder="Name in Myanmar" required="Please add myanmar name">
                </div>
    
                <div class="form-group">
                    <button class="btn btn-success btn-submit-township">Submit</button>
                </div>
    
            </form>
        </div>
      </div>
    </div>
   </div>
    {{--  Township modal end here --}}

    {{--  Village modal start here --}}
    <div class="modal fade" id="show-create-village" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Add Village</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form >
                <div id="success_message_village" class="ajax_response" style=""></div>
                <div class="form-group">
                    <label>Village:</label>
                    <input type="text" name="village" id="village" class="form-control" placeholder="Village" required="Please add village">
                    <input type="hidden" name="township_village" id="township_village" required> 
                </div>

                <div class="form-group">
                    <label>Name in Myanmar:</label>
                    <input type="text" name="name_in_myanmar_village" id="name_in_myanmar_village" class="form-control" placeholder="Name in Myanmar" required="Please add myanmar name">
                </div>
    
                <div class="form-group">
                    <button class="btn btn-success btn-submit-village">Submit</button>
                </div>
    
            </form>
        </div>
      </div>
    </div>
   </div>
    {{--  Village modal end here --}}

    {{--  Ward modal start here --}}
    <div class="modal fade" id="show-create-ward" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Add Ward</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form >
                <div id="success_message_ward" class="ajax_response" style=""></div>
                <div class="form-group">
                    <label>Ward:</label>
                    <input type="text" name="ward" id="ward" class="form-control" placeholder="Ward" required="Please add ward">
                    <input type="hidden" name="village_ward" id="village_ward" required> 
                </div>

                <div class="form-group">
                    <label>Name in Myanmar:</label>
                    <input type="text" name="name_in_myanmar_ward" id="name_in_myanmar_ward" class="form-control" placeholder="Name in Myanmar" required="Please add myanmar name">
                </div>
    
                <div class="form-group">
                    <button class="btn btn-success btn-submit-ward">Submit</button>
                </div>
    
            </form>
        </div>
      </div>
    </div>
   </div>
    {{--  Ward modal end here --}}
      {{-- FIELD CSS - will be loaded in the after_styles section --}}
      @push('crud_fields_styles')
          <!-- include select2 css-->
          <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
          {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
          <link href="{{ asset('vendor/adminlte/plugins/select2/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
  
      @endpush
  
      {{-- FIELD JS - will be loaded in the after_scripts section --}}
      @push('crud_fields_scripts')
          <!-- include select2 js-->
          <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
      @endpush
  
  @endif
  @push('crud_fields_scripts')
  
      <script>
        $(document).ready(function(){
            $("#saveActions").hide();
        });

          if(!String.prototype.trim) {
              String.prototype.trim = function () {
                  return this.replace(/^\s+|\s+$/g,'');
              };
          }
          function getAddressAu{{ $r_id }}() {
  
              var house_number_name = $('#house_number_name{{ $r_id }}').val();
              var v1 = $('#select2_ajax_{{ $f_id1.$r_id }} option:selected').text();
              var v2 = $('#select2_ajax_{{ $f_id2.$r_id }} option:selected').text();
              var v3 = $('#select2_ajax_{{ $f_id3.$r_id }} option:selected').text();
              var v4 = $('#select2_ajax_{{ $f_id4.$r_id }} option:selected').text();
              var v5 = $('#select2_ajax_{{ $f_id5.$r_id }} option:selected').text();
              console.log(v2);
              var full_address = house_number_name + ' ' + v5.trim() + ' ' + v4.trim() + ' ' +
                  v3.trim() + ' ' + v2.trim() + ' ' + v1.trim() ;
              $("#state_name").val(v1);
              $("#district_township").val(v2);
              $("#township_village").val(v3);
              $("#village_ward").val(v4);
              $('#address_name{{ $r_id }}').val(full_address);
          }
      </script>
      {{-- District AJAX Script Start Here--}}
      <script>
            $.ajaxSetup({
  
          headers: {
  
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
  
          });
  
          $(".btn-submit").click(function(e){
  
          e.preventDefault();
          var district = $("input[name=district]").val();
          var state = $("input[name=state]").val();
          var name_in_myanmar = $("input[name=name_in_myanmar]").val();
          
  
          $.ajax({
  
              type:'POST',
              url:'{{route('state')}}',
              data:{district:district, state:state,name_in_myanmar:name_in_myanmar},

              success:function(data){
                $('#district').val("");
                $('#name_in_myanmar').val("");
                $('#success_message').fadeIn().html("<div class='alert alert-success'>Division Added Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message').fadeOut("slow");
                  }, 2000 );
                }
            });
        });
        </script>
        {{-- District AJAX Script End Here--}}

        {{-- Township AJAX Script Start Here--}}
        <script>
            $.ajaxSetup({
  
          headers: {
  
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
  
          });
  
          $(".btn-submit-township").click(function(e){
  
          e.preventDefault();
          var township = $("input[name=township]").val();
          var district_township = $("input[name=district_township]").val();
          var name_in_myanmar_township = $("input[name=name_in_myanmar_township]").val();
          
  
          $.ajax({
  
              type:'POST',
              url:'{{route('township')}}',
              data:{township:township, district_township:district_township,name_in_myanmar_township:name_in_myanmar_township},

              success:function(data){
                $('#township').val("");
                $('#name_in_myanmar_township').val("");
                $('#success_message_township').fadeIn().html("<div class='alert alert-success'>Township Added Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message_township').fadeOut("slow");
                  }, 2000 );
                }
            });
        });
          </script>
        {{-- Township AJAX Script End Here--}}

        {{-- Village AJAX Script Start Here--}}
          <script>
            $.ajaxSetup({
  
          headers: {
  
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
  
          });
  
          $(".btn-submit-village").click(function(e){
  
          e.preventDefault();
          var village = $("input[name=village]").val();
          var township_village = $("input[name=township_village]").val();
          var name_in_myanmar_village = $("input[name=name_in_myanmar_village]").val();
          
  
          $.ajax({
  
              type:'POST',
              url:'{{route('village')}}',
              data:{village:village, township_village:township_village,name_in_myanmar_village:name_in_myanmar_village},

              success:function(data){
                $('#village').val("");
                $('#name_in_myanmar_village').val("");
                $('#success_message_village').fadeIn().html("<div class='alert alert-success'>Village Added Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message_village').fadeOut("slow");
                  }, 2000 );
                }
            });
        });
        </script>
        {{-- Village AJAX Script End Here--}}

        {{-- Ward AJAX Script Start Here--}}
          <script>
            $.ajaxSetup({
  
          headers: {
  
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
  
          });
  
          $(".btn-submit-ward").click(function(e){
  
          e.preventDefault();
          var ward = $("input[name=ward]").val();
          var village_ward = $("input[name=village_ward]").val();
          var name_in_myanmar_ward = $("input[name=name_in_myanmar_ward]").val();
          
  
          $.ajax({
  
              type:'POST',
              url:'{{route('ward')}}',
              data:{ward:ward, village_ward:village_ward,name_in_myanmar_ward:name_in_myanmar_ward},

              success:function(data){
                $('#ward').val("");
                $('#name_in_myanmar_ward').val("");
                $('#success_message_ward').fadeIn().html("<div class='alert alert-success'>Ward Added Sucessfully!</div>");
                    setTimeout(function() {
                        $('#success_message_ward').fadeOut("slow");
                  }, 2000 );
                }
            });
        });
        </script>
        {{-- Village AJAX Script End Here--}}
        <script>
            jQuery(document).ready(function($) {
              
              $('#house_number_name{{ $r_id }}').on('change',function () {
                  getAddressAu{{ $r_id }}();
              });
  
              $("#select2_ajax_{{ $f_id1.$r_id }}").each(function (i, obj) {
                  if (!$(obj).hasClass("select2-hidden-accessible"))
                  {
                      $(obj).select2({
                          theme: 'bootstrap',
                          multiple: false,
                          placeholder: "",
                          minimumInputLength: "0",
                          ajax: {
                              url: "{{ url('api/myanmar-address-state') }}",
                              dataType: 'json',
                              quietMillis: 250,
                              data: function (params) {
                                  return {
                                      q: params.term, // search term
                                      page: params.page
                                  };
                              },
                              processResults: function (data, params) {
                                  params.page = params.page || 1;
  
                                  var result = {
                                      results: $.map(data.data, function (item) {
  
                                          var textField = "name";
                                          return {
                                              text: item[textField] + ' / '+ item["description"],
                                              id: item["code"]
                                          }
                                      }),
                                      more: data.current_page < data.last_page
                                  };
                                  return result;
                              },
                              cache: true
                          },
                      }).on('change',function () {
                          $("#select2_ajax_{{ $f_id2.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id3.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');
                          getAddressAu{{ $r_id }}();
                      });
                  }
              });
  
  
              $("#select2_ajax_{{ $f_id2.$r_id }}").each(function (i, obj) {
                  if (!$(obj).hasClass("select2-hidden-accessible"))
                  {
                      $(obj).select2({
                          theme: 'bootstrap',
                          multiple: false,
                          placeholder: "",
                          minimumInputLength: "0",
                          ajax: {
                              url: "{{ url('api/myanmar-address-district') }}",
                              dataType: 'json',
                              quietMillis: 250,
                              data: function (params) {
                                  return {
                                      q: params.term, // search term
                                      page: params.page,
                                      state_id : $("#select2_ajax_{{ $f_id1.$r_id }}").val()
                                  };
                              },
                              processResults: function (data, params) {
                                  params.page = params.page || 1;
  
                                  var result = {
                                      results: $.map(data.data, function (item) {
  
                                          var textField = "name";
                                          return {
                                              text: item[textField] + ' / '+ item["description"],
                                              id: item["code"]
                                          }
                                      }),
                                      more: data.current_page < data.last_page
                                  };
  
                                  return result;
                              },
                              cache: true
                          },
                      }).on('change',function () {
                          $("#select2_ajax_{{ $f_id3.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');
  
  
                          getAddressAu{{ $r_id }}();
  
                      });
                  }
              });
  
  
              $("#select2_ajax_{{ $f_id3.$r_id }}").each(function (i, obj) {
                  if (!$(obj).hasClass("select2-hidden-accessible"))
                  {
                      $(obj).select2({
                          theme: 'bootstrap',
                          multiple: false,
                          placeholder: "",
                          minimumInputLength: "0",
                          ajax: {
                              url: "{{ url('api/myanmar-address-township') }}",
                              dataType: 'json',
                              quietMillis: 250,
                              data: function (params) {
                                  return {
                                      q: params.term, // search term
                                      page: params.page,
                                      district_id: $("#select2_ajax_{{ $f_id2.$r_id }}").val()
                                  };
                              },
                              processResults: function (data, params) {
                                  params.page = params.page || 1;
  
                                  var result = {
                                      results: $.map(data.data, function (item) {
  
                                          var textField = "name";
                                          return {
                                              text: item[textField] + ' / '+ item["description"],
                                              id: item["code"]
                                          }
                                      }),
                                      more: data.current_page < data.last_page
                                  };
  
                                  return result;
                              },
                              cache: true
                          },
                      }).on('change',function () {
                          $("#select2_ajax_{{ $f_id4.$r_id }}").val(null).trigger('change');
                          $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');
  
  
                          getAddressAu{{ $r_id }}();
  
                      });
                  }
              });
  
  
              $("#select2_ajax_{{ $f_id4.$r_id }}").each(function (i, obj) {
                  if (!$(obj).hasClass("select2-hidden-accessible"))
                  {
                      $(obj).select2({
                          theme: 'bootstrap',
                          multiple: false,
                          placeholder: "",
                          minimumInputLength: "0",
                          ajax: {
                              url: "{{ url('api/myanmar-address-village') }}",
                              dataType: 'json',
                              quietMillis: 250,
                              data: function (params) {
                                  return {
                                      q: params.term, // search term
                                      page: params.page,
                                      township_id: $("#select2_ajax_{{ $f_id3.$r_id }}").val()
                                  };
                              },
                              processResults: function (data, params) {
                                  params.page = params.page || 1;
  
                                  var result = {
                                      results: $.map(data.data, function (item) {
  
                                          var textField = "name";
                                          return {
                                              text: item[textField] + ' / '+ item["description"],
                                              id: item["code"]
                                          }
                                      }),
                                      more: data.current_page < data.last_page
                                  };
  
                                  return result;
                              },
                              cache: true
                          },
                      }).on('change',function () {
                          $("#select2_ajax_{{ $f_id5.$r_id }}").val(null).trigger('change');
  
  
                          getAddressAu{{ $r_id }}();
  
  
                      });
                  }
              });
  
  
              $("#select2_ajax_{{ $f_id5.$r_id }}").each(function (i, obj) {
                  if (!$(obj).hasClass("select2-hidden-accessible"))
                  {
                      $(obj).select2({
                          theme: 'bootstrap',
                          multiple: false,
                          placeholder: "",
                          minimumInputLength: "0",
                          ajax: {
                              url: "{{ url('api/myanmar-address-ward') }}",
                              dataType: 'json',
                              quietMillis: 250,
                              data: function (params) {
                                  return {
                                      q: params.term, // search term
                                      page: params.page,
                                      village_id: $("#select2_ajax_{{ $f_id4.$r_id }}").val()
                                  };
                              },
                              processResults: function (data, params) {
                                  params.page = params.page || 1;
  
                                  var result = {
                                      results: $.map(data.data, function (item) {
  
                                          var textField = "name";
                                          return {
                                              text: item[textField] + ' / '+ item["description"],
                                              id: item["code"]
                                          }
                                      }),
                                      more: data.current_page < data.last_page
                                  };
  
                                  return result;
                              },
                              cache: true
                          },
                      }).on('change',function () {
  
                          getAddressAu{{ $r_id }}();
  
                      });
                  }
              });
  
  
          });
      </script>
      
  @endpush