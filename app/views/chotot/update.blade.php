<ul class="img-grid">
    @foreach ($ads as $ad)
    <li data-id="{{$ad->id}}" data-row="{{$ad->row}}" data-col="{{$ad->col}}" data-sizex="1" data-sizey="1" title="{{$ad->title}}" >
        <div class='title'>{{$ad->title}}</div>
        <img atl="{{$ad->title}}" src="{{$ad->img}}"/>
    </li>
    @endforeach
    {{Form::token()}}
</ul>