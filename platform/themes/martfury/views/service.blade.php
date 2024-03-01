<?php
/**
 * Created by PhpStorm.
 * User: Abdulhamid
 * Date: 11/24/2023
 * Time: 10:42 PM
 */ ?>
<div class="row service-page">
    <div class="col-md-4">
        <ul class="list">
            @foreach($services as $serviceMenuItem)
            <li class="list-item">
                <div>
                    <a href="/service/{{$serviceMenuItem->slug}}"> <img src="{{RvMedia::getImageUrl($serviceMenuItem->icon, '', false, RvMedia::getDefaultImage())}}" class="list-item-image"> </a>
                </div>
                <div class="list-item-content">
                    <h4><a href="/service/{{$serviceMenuItem->slug}}">{{$serviceMenuItem->name}} </a></h4>
                    <p>{{$serviceMenuItem->summary}}</p>
                </div>
            @endforeach
        </ul>

    </div>
    <div class="col-md-8">

       <h1> {{$service->name}} </h1>

        {!! $service->content !!}

    </div>
</div>
