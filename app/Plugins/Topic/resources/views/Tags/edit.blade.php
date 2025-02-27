@extends("App::app")
@section('title',__("tag.edit tag"))

@section('content')
    <div class="col-md-12">
        <div class="card card-body" id="vue-topic-tag-edit">
            <h3 class="card-title">{{__("tag.edit tag")}}:{{$data->name}}</h3>
            <form method="post" action="/tags/edit?Redirect=/tags/{{$data->id}}/edit" @@submit.prevent="submit" enctype="multipart/form-data">
                <x-csrf/>
                <div class="mb-3">
                    <label class="form-label">
                        {{__("app.name")}}
                    </label>
                    <input type="text" class="form-control" value="{{$data->name}}" name="name" v-model="name" required>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">
                                {{__("app.color")}}
                            </label>
                            <input type="color" {{$value->color}} class="form-control form-control-color" name="color" v-model="color" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        {{__("app.icon")}}
                    </label>
                    <a href="https://tabler-icons.io/">https://tabler-icons.io/</a>
                    <textarea name="icon" v-model="icon" rows="10" class="form-control" required>{{$value->icon}}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label"> {{__("tag.Which user group can use this label")}}? </label>
                    <select class="form-select" name="userClass[]" multiple size="8">
                        @foreach($userClass as $value)
                            <option value="{{$value->name}}" @if(user_DeCheckClass($data,$value->name)) selected @endif>{{$value->name}}</option>
                        @endforeach
                    </select>
                    <small style="color:red">{{__("tag.If not selected, this label is available to all user groups")}}</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{__("app.description")}}</label>
                    <textarea name="description" class="form-control" rows="4">{{$data->description}}</textarea>
                </div>
                <div class="mb-3">
                    <button name="id" value="{{$data->id}}" class="btn btn-primary">{{__("app.submit")}}</button>
                </div>
            </form>
        </div>
    </div>
@endsection


