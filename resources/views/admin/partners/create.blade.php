<div class="card partnerModal">
    <div class="card-body">
        <p>To begin exchanging leads with a new partner, you must have their partner code. Enter the code below to
        lookup and obtain information about the requested sales partner.</p>
      <form method="POST" action="/admin/partners">
          @csrf
          @method('POST')
          <div class="row">
              <div class="col-lg-12">
                  <div class="form-floating">
                      <input type="text" class="form-control" name="code">
                      <label>Enter Partner Code</label>
                      <span class="helper-text">Enter the partner's Logic Access Code</span>
                  </div>
                    <input type="submit" class="btn btn-primary wait" data-anchor=".partnerModal" value="Create Invitation">
              </div>
          </div>
      </form>
    </div>
</div>
