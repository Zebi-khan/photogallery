<?php require_once 'header.php' ;?>


  <!-- Forgot Password -->
  <div class="forgot py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header text-center">
              <h4>Forgot Password</h4>
            </div>
            <div class="card-body">
              <form>
                <div class="form-group py-3">
                  <label for="email" class="mb-2">Email address</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Reset</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once 'footer.php' ;?>