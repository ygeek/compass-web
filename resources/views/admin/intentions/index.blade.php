@extends('layouts.admin')
@section('content')
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">意向列表</h3>
        </div>
        <div class="block-content">
            <form method="GET">
                <input type="text" name="name" placeholder="姓名" value="{{ $name }}" />
                <input type="text" class="date" name="start_date" placeholder="起始日期" value="{{ $start_date}}"/>
                <input type="text" class="date" name="end_date" placeholder="结束日期" value="{{ $end_date }}"/>
                <select name="state">
                    <option value="">所有状态</option>
                    <option value="unassigned" @if($state == 'unassigned') selected='selected' @endif>未分配</option>
                    <option value="assigned" @if($state == 'assigned') selected='selected' @endif>已分配</option>
                </select>
                <button>查询</button>
            </form>
            <button id="multiple_assign">多选分配</button>
            <button id="multiple_export">多选导出Excel</button>
            <table class="table table-striped table-borderless table-header-bg">
                <thead>
                <tr>
                    <th><input type="checkbox" id="multiple_all"/></th>
                    <th>用户姓名</th>
                    <th>状态</th>
                    <th>提交时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="table_body">
                @foreach($intentions as $intention)
                    <tr>
                        <th><input type="checkbox" data-id="{{ $intention->id }}"/></th>
                        <td>
                            {{$intention->name}}
                        </td>
                        <td>
                            @if($intention->state == 'unassigned')
                                未分配
                            @else
                                已分配
                            @endif
                        </td><td>
                            {{$intention->created_at}}
                        </td>
                        <td>
                            @if($intention->state == 'unassigned')
                            <form style="display:inline-block" action="{{ URL::route('admin.intentions.update', $intention->id) }}" method="POST">
                                <input type="hidden" name="_method" value="PATCH">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary">分配</button>
                            </form>
                            @endif

                            <form style="display:inline-block" action="{{ URL::route('admin.intentions.export_to_excel', $intention->id) }}" method="POST">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn btn-primary">导出Excel</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $intentions->render() }}
        </div>
    </div>

    <script type="text/javascript">
        $('.date').datepicker({
            format: "yyyy-mm-dd",
            startView: 1,
            language: "zh_cn"
        });
        $('#multiple_all').on('click',function(){
            var tmp = this.checked;
            $("#table_body tr th input[type=checkbox]").each(function(){
                this.checked=tmp;
            });
        });
        $('#multiple_assign').on('click',function(){
            var send_count=0,back_count=0;
            $("#table_body tr th input[type=checkbox]").each(function(){
                if (this.checked){
                    var url = "{{ route('admin.intentions.update', ['id' => 'tmp']) }}".replace('tmp', $(this).data('id'));
                    send_count++;
                    $.post(url, { _method:"PATCH",  _token:"{{ csrf_token() }}"},function(result){
                        if(++back_count == send_count){
                            window.location.reload();
                        }
                    });
                }
            });
        });

        // creates iframe and form in it with hidden field,
        // then submit form with provided data
        // url - form url
        // data - data to form field

        function ajax_download(url, data) {
            var $iframe,
                    iframe_doc,
                    iframe_html;

            if (($iframe = $('#download_iframe')).length === 0) {
                $iframe = $("<iframe id='download_iframe'" +
                        " style='display: none' src='about:blank'></iframe>"
                ).appendTo("body");
            }

            iframe_doc = $iframe[0].contentWindow || $iframe[0].contentDocument;
            if (iframe_doc.document) {
                iframe_doc = iframe_doc.document;
            }

            iframe_html = "<html><head></head><body><form method='POST' action='" +
                    url +"'>"

            Object.keys(data).forEach(function(key){
                iframe_html += "<input type='hidden' name='"+key+"' value='"+data[key]+"'>";

            });

            iframe_html +="</form></body></html>";

            iframe_doc.open();
            iframe_doc.write(iframe_html);
            $(iframe_doc).find('form').submit();
        }

        $('#multiple_export').on('click',function(){
            var send_count=0;
            $("#table_body tr th input[type=checkbox]").each(function(){
                if (this.checked){
                    var url = "{{ route('admin.intentions.export_to_excel', ['id' => 'tmp']) }}".replace('tmp', $(this).data('id'));
                    setTimeout(
                            function () {
                                ajax_download(url, {'_method': "POST", '_token': "{{ csrf_token() }}"})
                            }, 1000*(send_count++)
                    );
                }
            });
        });
    </script>
@endsection