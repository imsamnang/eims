<div class="card-header d-flex align-items-center p-2 m-0">

    <div class="d-flex align-items-center card-author-avatar">
        <a href="#">
            <img data-src="{{$feed["user"]["profile"]}}" class="avatar rounded-circle">
        </a>
        <div class="mx-3">
            <div class="card-author">
            <a data-toggle="card-author" data-user='{{json_encode($feed["user"])}}' href="#" class="text-dark font-weight-600 text-sm">{{$feed["user"]["name"]}}</a>
            </div>
            <div class="d-flex">
                <small class="text-muted mr-2 card-who-see" data-toggle="who-see" data-a="{{$feed["who_see"]}}"><i
                        class="fas fa-{{$feed["who_see"] == "public" ? "globe-asia" : "lock"}}"></i> </small>
                <small class="text-muted card-time text-xs" datetime="{{$feed["created_at"]}}">
                    <span class="preload d-block" style="width: 100px;margin-top: 3px;"><span class="preload-bg"
                            style=""></span></span>
                </small>
            </div>

        </div>
    </div>
    <div class="text-right ml-auto">
        @auth
        <div class="card-options">
            <div class="dropdown">
                <a href="javascript:void(0);" data-toggle="dropdown">
                    <i class="fas fa-chevron-down carret" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-bookmark" aria-hidden="true"></i>

                            <strong>{{__("Save post")}}</strong>
                            <br />
                            <small>{{__("Add this to your saved items")}} </small>

                        </a>
                    </li>

                    @if ($feed["user"]["id"] == Auth::user()->id)
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-edit" aria-hidden="true"></i>
                            <strong>{{__("Edit post")}}</strong>
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-trash" aria-hidden="true"></i>
                            <strong>{{__("Delete")}}</strong>

                        </a>
                    </li>

                    @endif

                    @if ($feed["user"]["id"] !== Auth::user()->id)
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <strong>{{__("Report post")}}</strong>
                            <br />
                            <small>{{__("I'm concerned about this post")}} </small>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-code" aria-hidden="true"></i>
                            {{__("Embed")}}
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>

                            {{__("More options")}}
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        @endauth

    </div>
</div>
