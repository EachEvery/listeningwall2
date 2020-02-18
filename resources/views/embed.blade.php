@extends('master')

@push('head')
<style>
    body, html {
        font-size: 1.2vh;
    }
</style>
@endpush


@section('title', $iframe->website)

@section('page')

<section class="h-screen flex items-center" style="background-color: {{empty($iframe->background_color) ? '#000' : $iframe->background_color}};">
    <div class="swiper-container">

        <div class="swiper-wrapper">            
            @foreach($responses as $response)    
                <div class="swiper-slide flex justify-center items-center" style="width: 40rem;" id="{{$response->id}}">        
                    <div class="px-16 py-12 bg-white pb-32 w-full">

                        <section class="font-black text-black flex flex-col justify-center items-center transition mb-12" style="height: 9rem">
                            <span class="leading-tight mb-2 text-center uppercase" style="font-size: 2.6rem">
                                {{empty($response->title) ? 'Untitled' : $response->title}}
                            </span>                

                            <span class="uppercase text-lg">
                                by {{$response->author}}
                            </span>
                        </section>

                        
                        <div class="border-b border-dotted transition">
                            @foreach(range(1,16) as $row)
                                @php
                                    $row = sprintf("row-%s", $row);
                                    $words = collect($response->words_with_color)->filter(function($w) use ($row) {
                                        return $w->row === $row;
                                    });
                                @endphp

                                <div class="h-10 border-t border-dotted row relative transition">                
                                    @foreach($words as $word)                                
                                        <div style="left: {{$word->left_percentage}}%; background-color: {{$word->color}}" class="bg-blue px-4 py-2 bg-category-color text-white rounded-full inline-block text-lg transition uppercase font-sans font-black border-2 border-white absolute" >
                                            {{$word->word}}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>      
</section>

@endsection


@push('head')
<link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.css">
<link rel="stylesheet" href="https://unpkg.com/swiper/css/swiper.min.css">
<script src="https://unpkg.com/swiper/js/swiper.js"></script>
<script src="https://unpkg.com/swiper/js/swiper.min.js"></script>
@endpush


@push('foot')
<style>

    .swiper-slide {
        
        opacity: .5;
        transition: 300ms all ease;
    }
    .swiper-slide.swiper-slide-active {
        opacity: 1;
        filter: none;
    }
</style>    
<script>
var mySwiper = new Swiper ('.swiper-container', {
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    slidesPerView: 'auto',    
    mousewheel: true,
    spaceBetween: 50,
    centeredSlides: true,
    slideToClickedSlide: true,
    loop: true,    

    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },

    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },
})
</script>
@endpush

