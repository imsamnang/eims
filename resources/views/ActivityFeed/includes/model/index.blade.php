<div class="modal card-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content clearfix equal">

            <div class="col col-vertical card-modal-gallery">
                <div class="swiper-container">
                    <div class="swiper-wrapper">

                    </div>
                    {{-- <div class="swiper-pagination swiper-pagination-white"></div> --}}
                    <div class="swiper-button-prev swiper-button-white"></div>
                    <div class="swiper-button-next swiper-button-white"></div>
                </div>
            </div>

            <div class="card card-posts card-modal-content p-0">

            </div>
        </div>
    </div>
</div>

<div class="modal modal-share fade" id="modal-share" tabindex="-1" role="dialog" aria-labelledby="modal-share" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document">
        <form role="{{config("pages.form.role")}}" class="needs-validation" method="POST"
            action="{{str_replace("post","share",config("pages.form.action.detect"))}}" id="form-share"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content rounded-0">
                <div class="modal-header bg-secondary rounded-0 p-2">
                    <h3 class="h3 m-0">
                        {{__("Share post")}}
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                @auth
                  <div class="modal-header p-2">
                    <div class="media w-100">
                        <img alt="" class="avatar rounded-circle mr-2" src="{{Auth::user()->profile()}}">
                        <div class="media-body">
                            <textarea style="height:100px" data-mention-url="{{str_replace("post","user",config("pages.form.action.detect"))}}" class="form-control post-message" placeholder=""
                            ></textarea>
                        </div>
                    </div>
                </div>
                @endauth

                <div class="modal-body p-0" style="height:300px">
                </div>
                <div class="modal-footer bg-secondary rounded-0 p-2">
                    <div class="d-flex w-100">
                        <button type="button" class="btn border btn-sm text-sm btn-cancel" data-dismiss="modal" aria-label="Close">
                            {{__("Cancel")}}
                        </button>
                        <div class="d-flex justify-content-end w-100 text-right">
                            <div class="form-row mr-3">
                                <label class="custom-toggle who-see">
                                    <input type="checkbox" name="who_see" value="only_me">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="{{__("Public")}}"
                                        data-label-on="{{__("Only me")}}"></span>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-sm text-white bg-{{config("app.theme_color.name")}}" data-update-text="{{__("Post")}}">
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-paper-plane"></i>
                                    <span class="d-none d-sm-block text-sm">
                                        {{__("Post")}}
                                    </span>

                                </span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="reaction-template d-none">
    <div class="row align-items-center m-0 py-2">
        <div class="col-xl-12">
            <div class="d-flex text-nowrap card-reaction-wrapper">
                <div class="col reaction-stat-clone">
                    <span class="reaction-emo"></span>
                    <span class="reaction-details" data-you-react="" data-lable-you="{{__("You")}}"
                        data-lable-other=""></span>
                </div>
                <div class="card-events">
                    <a data-count="0" data-toggle="view-reaction" data-feed-id=""> {{__("Like")}}</a>
                    <a data-count="0" data-toggle="view-comment" data-feed-id=""> {{__("Comment")}}</a>
                </div>
            </div>

        </div>

    </div>
    <div class="col-xl-12">
        <div class="card-actions">
            <div class="row">
                <div class="col-xl-4 col-sm-4 col-4 text-nowrap">
                    <button class="btn w-100 reaction-btn" data-toggle="reaction">
                        <i class="reaction-btn-emo fas fa-thumbs-up"></i>
                        <span class="reaction-btn-text">{{__("Like")}}</span>
                        <form action="{{str_replace("post","reaction",config("pages.form.action.detect"))}}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="feed_id" value="">
                            <input type="hidden" name="type" value="like">
                            <ul class="reactions-box">
                                <li class="reaction reaction-like" data-reaction="Like"
                                    data-lable="{{__("Like")}}">
                                </li>
                                <li class="reaction reaction-love" data-reaction="Love"
                                    data-lable="{{__("Love")}}">
                                </li>
                                <li class="reaction reaction-haha" data-reaction="HaHa"
                                    data-lable="{{__("HaHa")}}">
                                </li>
                                <li class="reaction reaction-wow" data-reaction="Wow"
                                    data-lable="{{__("Wow")}}"></li>
                                <li class="reaction reaction-sad" data-reaction="Sad"
                                    data-lable="{{__("Sad")}}"></li>
                                <li class="reaction reaction-angry" data-reaction="Angry"
                                    data-lable="{{__("Angry")}}">
                                </li>
                            </ul>
                        </form>

                    </button>
                </div>
                <div class="col-xl-4 col-sm-4 col-4 text-nowrap">
                    <button class="btn w-100 ">
                        <i class="fas fa-comment" aria-hidden="true"></i>
                        {{__("Comment")}}
                    </button>
                </div>
                <div class="col-xl-4 col-sm-4 col-4 text-nowrap">
                    <button data-toggle="share" class="btn w-100 ">
                        <i class="fas fa-share" aria-hidden="true"></i>
                        {{__("Share")}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="comment-template d-none">
    <div class="card-footer p-2">
        <div class="media align-items-center">
            <img alt="" class="avatar avatar-sm rounded-circle mr-2" data-src="">
            <div class="media-body">
                <form data-toggle="feed-comment"
                    action="{{str_replace("post","comment",config("pages.form.action.detect"))}}" method="POST">
                    @csrf
                    <input type="hidden" name="feed_id" value="">
                    <input data-mention-url="{{str_replace("post","user",config("pages.form.action.detect"))}}"
                        data-toggle="comment" name="comment" class="form-control form-control-sm rounded-pill"
                        placeholder="" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="replied-template d-none">
    <div class="media align-items-center">
        <img alt="" class="avatar avatar-xs rounded-circle mr-2" data-src="">
        <div class="media-body pr-2">
            <form action="{{str_replace("post","replied",config("pages.form.action.detect"))}}" method="POST">
                @csrf
                <input type="hidden" name="comment_id" value="">
                <input data-mention-url="{{str_replace("post","user",config("pages.form.action.detect"))}}"
                    data-toggle="replied-comment" name="comment" class="form-control form-control-sm rounded-pill"
                    placeholder="" />
            </form>
        </div>
    </div>
</div>
