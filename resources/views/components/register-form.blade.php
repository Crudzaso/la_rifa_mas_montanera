<form class="form w-100" novalidate="novalidate" id="kt_sign_up_form"
    data-kt-redirect-url="authentication/layouts/creative/sign-in.html" action="{{ route('register') }}">
    @crsf
    <div class="text-center mb-11">
        <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
        <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
    </div>
    <div class="row g-3 mb-9">
        <div class="col-md-6">
            <a href="#"
                class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                <img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg" class="h-15px me-3" />Sign in
                with Google</a>
        </div>
        <div class="col-md-6">
            <a href="#"
                class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                <img alt="Logo" src="assets/media/svg/brand-logos/apple-black.svg"
                    class="theme-light-show h-15px me-3" />
                <img alt="Logo" src="assets/media/svg/brand-logos/apple-black-dark.svg"
                    class="theme-dark-show h-15px me-3" />Sign in with Apple</a>
        </div>
    </div>
    <div class="separator separator-content my-14">
        <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
    </div>
    <div class="fv-row mb-8">
        <input type="text" placeholder="Email" name="email" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="fv-row mb-8" data-kt-password-meter="true">
        <div class="mb-1">
            <div class="position-relative mb-3">
                <input class="form-control bg-transparent" type="password" placeholder="Password" name="password"
                    autocomplete="off" />
                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                    data-kt-password-meter-control="visibility">
                    <i class="ki-outline ki-eye-slash fs-2"></i>
                    <i class="ki-outline ki-eye fs-2 d-none"></i>
                </span>
            </div>
            <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
            </div>
        </div>
        <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
    </div>
    <div class="fv-row mb-8">
        <input placeholder="Repeat Password" name="confirm-password" type="password" autocomplete="off"
            class="form-control bg-transparent" />
    </div>
    <div class="fv-row mb-8">
        <label class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="toc" value="1" />
            <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">I Accept the
                <a href="#" class="ms-1 link-primary">Terms</a></span>
        </label>
    </div>
    <div class="d-grid mb-10">
        <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
            <span class="indicator-label">Sign up</span>
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
        </button>
    </div>
    <div class="text-gray-500 text-center fw-semibold fs-6">Already have an Account?
        <a href="authentication/layouts/creative/sign-in.html" class="link-primary fw-semibold">Sign in</a>
    </div>

</form>
