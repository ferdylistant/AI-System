<!-- Modal -->
<div class="modal fade" id="modalForgotPassword" tabindex="-1" role="dialog" aria-labelledby="titleModalForgotPassword" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <form autocomplete="off" class="form" id="formResetPasswordModal">
              <div class="modal-header">
                  <h5 class="modal-title" id="titleModalForgotPassword"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="text-center">
                    <img src="https://cdn-icons-png.flaticon.com/512/6146/6146600.png" alt="car-key">
                    <h2 class="text-center">Forgot Password?</h2>
                    <p class="text-muted">You can reset your password here.</p>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                </div>
                                @CSRF
                                <input id="email" name="email" placeholder="example@gmail.com" class="form-control"  type="email" required>
                            </div>
                        </div>
                        <button name="btnForget" class="btn btn-lg btn-primary btn-block btnForget" type="submit">Reset Password</button>
                </div>
              </div>
          </form>
      </div>
    </div>
  </div>
