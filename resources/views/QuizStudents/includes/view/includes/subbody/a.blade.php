<div class="card-header">
    <h3>{{$row['quiz']}}</h3>
    <span>
        {{$row['study']}}
    </span>

</div>

<div class="card-header">
    <div class="list-group list-group-flush">
        <div href="#" class="list-group-item">
            <div class="row">
                <div class="avatar avatar-xl rounded">
                    <img data-src="{{$row['photo']}}" alt="" id="crop-image">
                </div>
                <div class="col ml--2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-sm">
                                {{$row['name']}}
                            </h4>
                        </div>
                    </div>
                    <p class="text-sm mb-0">
                        {{$row['gender']}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
