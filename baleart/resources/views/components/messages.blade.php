<div class="alert alert-{{$type}}" style="{{$attributes["style"]}}">
    {{$title}}
    {{$majuscules($mayusculas)}}
    <div style="background-color:blue; color:chartreuse">
        <p>{{$slot}}</p>
    </div>    
</div>