<?php
$uid = time().rand(1,9999).rand(1,9999);
?>
@push('crud_fields_scripts')
   {{--<style>--}}
       {{--#popup{{  $uid }} {--}}
           {{--background-color: white;--}}
           {{--width: 100%;--}}
           {{--height: 100%;--}}
           {{--position: fixed;--}}
           {{--top: 0;--}}
           {{--right: 0;--}}
           {{--bottom: 0;--}}
           {{--left: 0;--}}
           {{--z-index: 9999;--}}
           {{--display: none;--}}
           {{--overflow: hidden;--}}
           {{---webkit-overflow-scrolling: touch;--}}
           {{--outline: 0;--}}
           {{--background-color: rgb(0,0,0); /* Fallback color */--}}
           {{--background-color: rgba(0,0,0,0.4)--}}
       {{--}--}}
       {{--#iframe{{  $uid }} {--}}
           {{--border: 0;--}}
           {{--width: 100%;--}}
           {{--height: 100%;--}}
           {{--/*margin: 30px auto;*/--}}
           {{--position: relative;--}}
           {{--display: block;--}}
           {{--box-shadow: 0 2px 3px rgba(0,0,0,0.125);--}}
       {{--}--}}

       {{--#saveActions{--}}
           {{--display: none;--}}
       {{--}--}}
   {{--</style>--}}


@endpush