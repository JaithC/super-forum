@extends("App::app")

@section('title',"「".$user->username."」的帖子列表")
@section('description','为你展示「'.$user->username.'」的帖子列表')

@section('content')

    <div class="row row-cards justify-content-center">
        <div class="col-md-12">
            <div class="row row-cards justify-content-center">
                <div class="col-lg-9">
                    <div class="row row-cards justify-content-center">
                        @if($page->count())
                            @foreach($page as $data)
                                <article class="col-md-12">
                                    <div class="border-0 card card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-auto">
                                                        <a href="/users/{{$data->user->username}}.html" class="avatar"
                                                           style="background-image: url({{super_avatar($data->user)}})">

                                                        </a>
                                                    </div>
                                                    <div class="col">
                                                        <a href="/users/{{$data->user->username}}.html"
                                                           style="margin-bottom:0;text-decoration:none;"
                                                           class="card-title text-reset">{{$data->user->username}}</a>
                                                        <div style="margin-top:1px">{{__("app.Published on")}}:{{$data->created_at}}</div>
                                                    </div>
                                                    <div class="col-auto">
                                                        @if($data->essence>0)
                                                            <div class="ribbon bg-green text-h3">
                                                                {{__("app.essence")}}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12 markdown home-article">
                                                        <a href="/{{$data->id}}.html" class="text-reset">
                                                            <h2>
                                                                @if($data->topping>0)
                                                                    <span class="text-red">
                                                    {{__('app.top')}}
                                                </span>
                                                                @endif
                                                                {{$data->title}}</h2>
                                                        </a>
                                                        <span class="home-summary">{!! content_brief($data->post->content,get_options("topic_brief_len",250)) !!}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12" style="margin-top: 5px">
                                                <div class="d-flex align-items-center">
                                                    <div class="col-auto bottomLine">
                                                        <a href="/tags/{{$data->tag->id}}.html" style="text-decoration:none">
                                                            <div class="card-circle">
                                                                {!! $data->tag->icon !!}
                                                                <span>{{$data->tag->name}}</span>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="ms-auto">
                                    <span class="text-muted" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                          title="{{__("app.pageviews")}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round"><path stroke="none"
                                                                                                  d="M0 0h24v24H0z"
                                                                                                  fill="none"/><circle
                                                    cx="12" cy="12" r="2"/><path
                                                    d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"/></svg>
                                        {{$data->view}}
                                    </span>
                                                        <a style="text-decoration:none;" core-click="like-topic" topic-id="{{$data->id}}"
                                                           class="ms-3 text-muted cursor-pointer" data-bs-toggle="tooltip"
                                                           data-bs-placement="bottom" title="{{__("topic.likes")}}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                                 stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M19.5 13.572l-7.5 7.428l-7.5 -7.428m0 0a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572"/>
                                                            </svg>
                                                            <span core-show="topic-likes">{{$data->like}}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <div class="border-0 card card-body">
                                    <div class="text-center card-title">{{__("app.No more results")}}</div>
                                </div>
                            </div>
                        @endif
                        {!! make_page($page) !!}
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="row row-cards rd">
                        <div class="col-md-12 sticky" style="top: 105px">
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-status-top bg-primary"></div>
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                {{get_options("web_name")}}
                                            </h3>
                                            <p>
                                                {{get_options("description",__("app.no description"))}}
                                            </p>
                                        </div>
                                        <div class="card-footer">
                                            @if(auth()->check())
                                                <a href="/topic/create" class="btn btn-dark">{{__("topic.create")}}</a>
                                            @else
                                                <a href="/login" class="btn btn-dark">{{__("app.login")}}</a>
                                                <a href="/register" class="btn btn-light">{{__("app.register")}}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="border-0 card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row justify-content-center">
                                                        <span class="avatar avatar-lg center-block" style="background-image: url({{super_avatar($user)}})"></span>
                                                        <br>
                                                        <b class="card-title text-h2 text-center" style="margin-top: 5px;margin-bottom:2px">{{ $user->username }}</b>
                                                        <span class="text-center" style="color:rgba(0,0,0,.45)">共 {{$user->fans}} 位粉丝</span>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a class="card-btn cursor-pointer" user-click="user_follow" user-id="{{ $user->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                    <path d="M16 11h6m-3 -3v6"></path>
                                                </svg>
                                                <span>{{__("app.follow")}}</span></a>
                                            <a href="/users/{{$user->username}}.html" class="card-btn">{{__("app.watch")}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('headers')
    <link rel="stylesheet" href="{{ mix('plugins/Topic/css/app.css') }}">
@endsection
@section('scripts')
    <script src="/tabler/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{mix('plugins/Topic/js/core.js')}}"></script>
@endsection
