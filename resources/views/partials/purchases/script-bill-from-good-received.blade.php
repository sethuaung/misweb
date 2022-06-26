<?php
    $row = isset($row)?$row:null;
    $row_gs = $row->pre_goods_products;
?>
@if($row_gs != null)
    @foreach( $row_gs as $r)
        @include('partials.purchases.product-list',['row'=>$row,'r'=>$r,'from_received'=>true])
    @endforeach
@endif
