@include('m.public.header')
<div class="clear"></div>
<div class="main">
    <div class="login_resgister">
        <form action="{{route('estimate.step_second')}}" method="get">
            <select name="selected_country_id" class="select01" >
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if($country->id == $selected_country_id) selected @endif>{{$country->name}}</option>
                @endforeach
               
            </select>
            <select name="selected_degree_id" class="select01">
                @foreach($degrees as $degree)
                    <option value="{{$degree->id}}" @if($degree->id == $selected_degree_id) selected @endif>{{$degree->name}}</option>
                @endforeach
            </select>
            <select name="selected_year" class="select01" id="years">
                @foreach($years as $year)
                    <option value="{{$year}}" @if($year == $selected_year) selected @endif>{{$year}}</option>
                @endforeach
            </select>
            <select name="speciality_category_id" class="select01" id="speciality_categories" v-model="selected_category_id">
                @foreach($speciality_categories as $speciality_category)
                    <option value="{{ $speciality_category->id }}" @if($speciality_category->id == $selected_category_id) selected @endif >{{ $speciality_category->chinese_name }}</option>
                @endforeach
                
            </select>
            <select name="speciality_name" class="select01" v-model="selected_speciality_name">
                @foreach($speciality_categories as $speciality_category)
                    
                    <option value="{{ $speciality_category->name }}" >{{ $speciality_category->name }}</option>
                    
                @endforeach
                
               
            </select>
            @if(isset($cpm))
            <input type="hidden" name="cpm" value="{{ $cpm }}">
            @endif

            @if($college_id)
            <input type="hidden" name="college_id" value="{{ $college_id }}">
            @endif
            <input type="submit" value="下一步" class="select_button">
        </form>
    </div>
    <div class="clear"></div>
</div>

