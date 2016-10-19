@include('m.public.header')
<div class="clear"></div>
<div class="main">
    <div class="login_resgister">
        <form action="{{route('estimate.step_second')}}" method="get" onsubmit="return checkFirst()">
            <label for="country">选择目标国家</label>
            <select name="selected_country_id" class="select01"  onchange="changeZy();">
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if($country->id == $selected_country_id) selected @endif>{{$country->name}}</option>
                @endforeach
               
            </select>
            <label for="degree">将要攻读学历</label>
            <select name="selected_degree_id" class="select01"   onchange="changeZy();">
                @foreach($degrees as $degree)
                    <option value="{{$degree->id}}" @if($degree->id == $selected_degree_id) selected @endif>{{$degree->name}}</option>
                @endforeach
            </select>
            <label for="years">计划留学时间</label>
            <select name="selected_year" class="select01" id="years" >
                @foreach($years as $year)
                    <option value="{{$year}}" @if($year == $selected_year) selected @endif>{{$year}}</option>
                @endforeach
            </select>
            <label for="speciality_categories">期望就读专业</label>
            <select name="speciality_category_id" class="select01" id="speciality_categories"   onchange="changeZy();" v-model="selected_category_id">
                @foreach($speciality_categories as $speciality_category)
                    <option value="{{ $speciality_category->id }}" @if($speciality_category->id == $selected_category_id) selected @endif >{{ $speciality_category->chinese_name }}</option>
                @endforeach
                
            </select>
            
            <select name="speciality_name" class="select01" v-model="selected_speciality_name">
              
                
               
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

<script>
    changeZy();
    function checkFirst()
    {
        var speciality_name = $("#speciality_categories").val();
        if(!speciality_name)
        {
            alert('专业正在加载中...');
            return false;
        }
        else
        {
            return true;
        }
        
    }
</script>