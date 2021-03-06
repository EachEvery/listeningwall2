@extends('master')

@push('head')
     <script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.12.min.js"></script>       
        <style>
            html, body {
                background-color: #000;
                color: #fff;                
                font-size: .833vh;
                overflow: hidden;                    
            }

            * {
                user-select: none;
                
            }

            @media screen and (min-height: 1200px) {
                *, html, body {
                    cursor: none !important;
                }
            }
        </style>
@endpush

@section('title', $collection['name'])
@section('page')
    <div id="app" class="overflow-hidden"></div>

    @php
        $aws = json_encode(array_except(config('filesystems.disks.s3'), ['driver']));
    @endphp

    <script>        
    (function() {
        window.aws = JSON.parse('{!!$aws!!}');     
        window.collection = {!!$collection!!};
        window.locale = '{{$locale}}';
    })();            
    </script>

    <script src="{{mix('js/app.js')}}"></script>
@endsection