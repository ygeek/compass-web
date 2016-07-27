<a
  href="{{route('colleges.rank', ['category_id' => $category['_id']])}}"
  class="level-{{$level}} @if($category['_id'] == $selected_category_id) active @endif" >{{ $category['name'] }}</a>
@if (count($category['children']) > 0)
    @foreach($category['children'] as $category)
        @include('colleges.rank_category', ['category' => $category, 'level' => $level + 1, 'selected_category_id' => $selected_category_id])
    @endforeach
@endif
