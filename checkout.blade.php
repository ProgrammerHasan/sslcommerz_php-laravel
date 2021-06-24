<form method="post">
    <div class="row">
        <div class="col-md-12" style="margin-top: -15px;">
            <h6 class="mt-4" style="margin-bottom: -10px;border-top: 1px solid #dee0e2;padding: 5px;">Select Payment Method</h6>

            <hr>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="iagreetermscons" id="iagreetermscons">
                <label class="custom-control-label" for="iagreetermscons">I agree to the Terms and Conditions of the website,</label>
                <p class="text-danger" style="margin-left: -15px;">You need to read and agree to the
                    <a href="{{ route('pages.privacy-policy') }}" target="_blank" class="font-weight-bold text-info" >Privacy Policy</a>
                    before confirm your order.</p>
            </div>
            <hr>
            <button class="btn btn-primary btn-block" disabled id="btn-confirm-checkout" type="submit">
                <i class="fa fa-shopping-cart"></i> Continue to checkout
            </button>
        </div>
    </div>
</form>

    <script>

       // I agree to the Terms and Conditions of the website.
        $('#iagreetermscons').click(function(){
            if($('input[name="iagreetermscons"]').is(':checked'))
            {
                document.getElementById("btn-confirm-checkout").disabled = false;
            }else
            {
                document.getElementById("btn-confirm-checkout").disabled = true;
                toastr.warning('Please Check tick mark || I agree to the Terms and Conditions of the website.');
            }
        });
    </script>
