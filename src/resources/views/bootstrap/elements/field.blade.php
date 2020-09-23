<div class="row">
    <div class="col-md-12">
        @if($field_type == 'text')
        <div class="form-group label-floating">
            <label class="control-label">{{isset($title) ? $title : ''}}</label>

            <input class="form-control" type="text" name="{{$name}}" value="{{isset($value) ? $value : ''}}" />
        </div>
        @else
        @endif
    </div>
</div>