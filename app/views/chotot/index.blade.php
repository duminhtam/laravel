<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="not-ie6 not-ie7 not-ie8 not-ie9 " xmlns="http://www.w3.org/1999/xhtml"  lang="vi-VN">
<head>
    <title>
        Chotot.vn Crawler - Build 1
    </title>
    @stylesheets('chotot')
    @javascripts('chotot')
</head>
<body>
    <script type="text/javascript">
        var chototSetting = {max_cols:{{$config->max_cols}}, runInterval:{{$config->runInterval}},idleInterval:{{$config->idleInterval}}};
    </script>

    <div class="gridster">
        <ul class="img-grid">
            @foreach ($ads as $ad)
                <li data-id="{{$ad->id}}" data-row="{{$ad->row}}" data-col="{{$ad->col}}" data-sizex="1" data-sizey="1" title="{{$ad->title}}" >
                    <div class='title'>{{$ad->title}}</div>
                    <img atl="{{$ad->title}}" src="{{$ad->img}}"/>
                </li>
            @endforeach
            {{Form::token()}}
        </ul>
    </div>


</body>
</html>
