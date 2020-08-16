<div class="card-body p-0" style="text-align: initial">
    <div class="tab-content p-0 border-0" id="myTabContent">

        <div id="tab--{{$row['id']}}" class="tab-pane fade active show" role="tabpanel"
            aria-labelledby="tab--{{$row['id']}}">
            @include(config("pages.parent").".includes.print.includes.subbody.a",['row'=>$row])
        </div>

    </div>
</div>
