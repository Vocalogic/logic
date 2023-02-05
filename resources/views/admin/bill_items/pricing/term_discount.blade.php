<div class="row">
    @foreach($item->discountTerm as $term => $val)
        <div class="col-lg-4">
            <div class="form-floating mt-2">
                <input type="text" class="form-control" name="sterm_{{$term}}"
                       value="{{$val}}">
                <label>{{$term}}-Month Term Discount (in %)</label>
                <span
                    class="helper-text">Enter auto-discount amount <b>from MSRP</b> for a {{$term}} month term selected.</span>
            </div>
        </div>
    @endforeach
</div>
