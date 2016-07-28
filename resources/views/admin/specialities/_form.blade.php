<div class="form-group">
    <label class="col-xs-12" for="register1-name">专业名称<span class="text-danger">*</span></label>
    <div class="col-xs-12">
        <input class="form-control" type="text" id="register1-name" name="name" placeholder="输入专业名称" value="{{$speciality->name}}">
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12" for="contact1-degree">专业学位<span class="text-danger">*</span></label>
    <div class="col-xs-12">
        <select class="form-control" id="contact1-degree" name="degree_id">
            @foreach($degrees as $degree)
                <option value="{{$degree->id}}" @if($speciality->degree_id == $degree->id) selected @endif>{{$degree->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12" for="contact1-category">专业学科</label>
    <div class="col-xs-12">
        <select class="form-control" id="contact1-category" name="category_id">
            @foreach($categories as $category)
                <option value="{{$category->id}}" @if($speciality->category_id == $category->id) selected @endif>{{$category->english_name}} {{$category->chinese_name}} </option>
            @endforeach
        </select>
    </div>
</div>