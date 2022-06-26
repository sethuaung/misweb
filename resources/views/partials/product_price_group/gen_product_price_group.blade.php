@if($rows != null)
    <?php
    $price_groups = \App\Models\PriceGroup::all();
    ?>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    @if($price_groups != null)
                        @foreach($price_groups as $pg)
                            <th>{{$pg->name}}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <?php
                    $u_variants = $row->unit_variant;
                    $id = $row->id;

                    ?>
                    <tr>
                        <td>{{$row->product_name}}</td>
                        @if($price_groups != null)
                            @foreach($price_groups as $pg)
                                <td>
                                    <table style="width: 100%">
                                    <tr>
                                    @forelse ($u_variants as $unit)
                                            <?php

                                            $m = \App\Models\ProductPriceGroup::where('product_id',$id)->where('unit_id',$unit->id)->where('price_group_id',$pg->id)->first();
                                            ?>
                                            <td>{{$unit->title}}
                                                <input value="{{optional($m)->price}}" type="text" data-product_id="{{$id}}" data-unit="{{$unit->id}}" data-pg="{{$pg->id}}" class="form-control unit-price">
                                            </td>
                                    @empty

                                            <?php
                                            $m = \App\Models\ProductPriceGroup::where('product_id',$id)->where('unit_id',0)->where('price_group_id',$pg->id)->first();
                                            ?>
                                            <td style="width: 100%"><input value="{{optional($m)->price}}" type="text"  data-product_id="{{$id}}" data-unit="0" data-pg="{{$pg->id}}" class="form-control unit-price"></td>
                                    @endforelse

                                    </tr>
                                    </table>

                                </td>
                            @endforeach
                        @endif
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>
@endif
