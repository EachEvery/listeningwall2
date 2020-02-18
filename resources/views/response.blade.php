@extends('master')
@section('title', $response->title.' by '.$response->author)

@push('head')
<link rel="stylesheet" href="/vendor/nova/app.css" />

<script>
(function() {
    window.poemCard = {!!json_encode($response->poem_card_data)!!}
})();
</script>
@endpush


@section('page')

<div id="app"></div>

<script src="{{mix('js/renderResponse.js')}}"></script>

@endsection