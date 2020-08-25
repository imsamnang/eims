@if ($sliders)

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
