<div>
    <div class="row">


        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form wire:submit="save">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="body" wire:model="data"
                                                  style="height: 2000px;"></textarea>
                                        <label>TOS Body</label>
                                        <span class="helper-text">Enter the Terms of Service (in markdown format)</span>
                                    </div>
                                    <input type="submit" name="save" class="btn btn-light-primary" value="Save TOS">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <P>
                        {!! $converted !!}
                    </P>
                </div>
            </div>
        </div>

    </div>


</div>
