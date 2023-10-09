<div class="bottom-menu">
    <a href="" class="scan-tab">
        <div class="inside">
            <i class="fas fa-qrcode"></i>
        </div>
    </a>
    <div class="d-flex justify-content-center">
        <div class="row w-100">
            <div class="col-md-8 offset-md-2 ">
                <div class="row">
                    <div class="col-3 text-center">
                        <a href="{{ route('home') }}">
                            <i class="fas fa-home"></i>
                            <p>
                                Home
                            </p>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="{{ route('get-wallet-index') }}">
                            <i class="fas fa-wallet"></i>
                            <p>
                                Wallet
                            </p>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="{{ route('get-transaction-index') }}">
                            <i class="fas fa-exchange-alt"></i>
                            <p>
                                Transaction
                            </p>
                        </a>
                    </div>
                    <div class="col-3 text-center">
                        <a href="{{ route('profile') }}">
                            <i class="fas fa-user"></i>
                            <p>
                                Profile
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>