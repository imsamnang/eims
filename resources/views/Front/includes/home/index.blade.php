@if ($sliders)
<section class="banner banner-property">
    <div class="owl-carousel owl-nav-top-right" data-animateOut="fadeOut" data-nav-arrow="false" data-items="1"
        data-md-items="1" data-sm-items="1" data-xs-items="1" data-xx-items="1" data-space="0">
        @foreach ($sliders['data'] as $row)
        <div class="item">
            <div class="property-offer">
                <div class="property-offer-item">
                    <div class="property-offer-image bg-holder"
                        style="background-image: url({{$row['property']['image']}});">
                        <div class="container">
                            <div class="row justify-content-end">
                                <div class="col-lg-5 col-md-8 col-sm-12">
                                    <div class="property-details">
                                        <div class="property-details-inner">
                                            <h5 class="property-title">
                                                <a href="{{$row['property']['action']['detail']}}">
                                                    {{$row['property']['title']}}
                                                </a>
                                            </h5>
                                            <span class="property-address">
                                                <i class="fas fa-map-marker-alt fa-xs"></i>
                                                {{$row['property']['province']}},
                                                {{$row['property']['district']}},
                                                {{$row['property']['commune']}}

                                            </span>
                                            <span class="property-agent-date float-right">
                                                <i class="far fa-clock fa-md"></i>

                                                <span datetime="{{$row['property']['created_at']}}">
                                                    {{$row['property']['created_att']}}
                                                </span>
                                            </span>
                                            <p class="mb-0 d-block mt-3">{{$row['property']['description']}}</p>
                                            <div class="property-price">
                                                $ {{$row['property']['price']}}
                                                @if ($row['property']['rental_period'])
                                                <span> / {{$row['property']['rental_period']}}</span>

                                                @endif
                                            </div>
                                            <ul class="property-info list-unstyled d-flex">
                                                @if ($row['property']['bedroom'])
                                                <li class="flex-fill property-bed">
                                                    <i class="fas fa-bed"></i>
                                                    {{__('Bed')}}<span>{{$row['property']['bedroom']}}</span>
                                                </li>
                                                @endif
                                                @if ($row['property']['bathroom'])
                                                <li class="flex-fill property-bath">
                                                    <i class="fas fa-bath"></i>
                                                    {{__('Bath')}}<span>{{$row['property']['bathroom']}}</span>
                                                </li>
                                                @endif
                                                <li class="flex-fill property-m-sqft">
                                                    <i class="far fa-square"></i>
                                                    {{__('Size')}}<span>{{$row['property']['size']}}</span> {{__('m²')}}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="property-btn">
                                            <a class="property-link" href="{{$row['property']['action']['detail']}}">
                                                {{__('See Details')}}
                                            </a>
                                            <ul class="property-listing-actions list-unstyled mb-0">
                                                <li class="property-compare">
                                                    <a data-toggle="tooltip" data-placement="top"
                                                        title="{{__('Compare')}}" id="property-compare-add"
                                                        href="{{route('front.compare.action','add')}}"
                                                        data-slug="{{$row['property']['slug']}}"
                                                        data-target-count="#compares-count">
                                                        <i
                                                            class="fas fa-exchange-alt {{in_array($row['property']['slug'],$compares) ? 'text-success' : ''}}"></i>
                                                    </a>
                                                </li>
                                                <li class="property-favourites">
                                                    <a data-toggle="tooltip" id="property-favourite-add"
                                                        data-placement="top" title="{{__('Favourite')}}"
                                                        href="{{route('front.favourite.action','add')}}"
                                                        data-target-count="#favourites-count"
                                                        data-slug="{{$row['property']['slug']}}">
                                                        <i
                                                            class="fas fa-heart {{in_array($row['property']['slug'],$favourites) ? 'text-danger' : ''}}"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>
@endif


<!--Sponsor-->
@if ($sponsored)
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
        <span class="text-white h3">{{ __('Sponsored by') }}</span>
    </div>

</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
        <ul class="list-unstyled list-inline social text-center">
            @foreach ($sponsored['data'] as $item)
            <li class="img-thumbnail list-inline-item mb-2" width="150" height="150">
                <a href="#">
                    <img class="img-responsive" width="150" height="150" src="{{ $item['image'] }}" />
                </a>
            </li>
            @endforeach
        </ul>
    </div>

</div>

@endif
