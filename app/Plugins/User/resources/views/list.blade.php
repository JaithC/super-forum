@extends("App::app")

@section('title',  __("user.list"))
@section('description', '本站会员列表,共['.$page->count().'条内容]')
@section('keywords', '本站会员列表')

@section('header')
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Page title -->
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <!-- Page pre-title -->
                        <div class="page-pretitle">
                            Overview
                        </div>
                        <h2 class="page-title">
                            {{ __("user.list") }}
                        </h2>
                        {{__("user.members in total",['total' => $count])}}
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')

            <div class="row row-cards">
                @if ($page->count())
                    @foreach ($page as $value)
                        <div class="col-md-6 col-lg-3">
                            <div class="border-0 card">
                                <div class="card-body p-4 text-center">
                                    <span class="avatar avatar-xl mb-3 avatar-rounded" style="background-image: url({{super_avatar($value)}})"></span>
                                    <h3 class="m-0 mb-1">
                                        <a href="/users/{{$value->username}}.html">{{$value->username}}</a>
                                    </h3>
                                    <div class="text-muted">{{__("user.st member",['member' => $value->id])}}</div>
                                    <div class="text-muted">{{__("app.Join time",['time' => $value->created_at])}}</div>
                                    <div class="mt-3">
                                        <a href="/users/group/{{$value->Class->id}}.html">
                                            {!! Core_Ui()->Html()->UserGroup($value->Class) !!}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-4">
                        <div class="border-0 card">
                            <div class="card-status-top bg-danger"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto">
                                        <span class="avatar"></span>
                                    </div>
                                    <div class="col">
                                        <h3 class="card-title text-h2">{{__("app.No more results")}}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {!! make_page($page) !!}
            </div>

@endsection
