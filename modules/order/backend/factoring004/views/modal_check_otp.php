<script>
  document.addEventListener('DOMContentLoaded', () => {
    function renderModalForm () {
      const template = `<div id="modal-factoring004-check-otp" style="display: none" title="Check OTP">
        <form id="form-factoring004-check-otp" method="post">
            <div style="padding: 2rem 0">
                <input type="text" id="factoring004-otp" minlength="4" maxlength="4" placeholder="Enter OTP code" pattern="\\d+" required>
            </div>
            <div style="text-align: center">
                <button id="factoring004-otp-submit">Check</button>
            </div>
        </form>
    </div>`;

      $(document.body).append(template);
    }

    function showModal() {
      $('#modal-factoring004-check-otp')
        .dialog({
          autoOpen: true,
          modal: true,
          closeOnEscape: false,
          resizable: false,
          open () {
            $('.ui-dialog-titlebar-close').remove();
          },
          close () {
            $('#factoring004-otp').val('');
            hideError();
          },
        });
    }

    renderModalForm();
    showModal();

    $('#save').append('<input id="factoring004-main-otp" type="hidden" name="otp">');
    $('#form-factoring004-check-otp').submit(e => {
      e.preventDefault();

      $('#factoring004-main-otp').val($('#factoring004-otp').val());
      $('#save').find('.btn_save').click();
    });
  });
</script>