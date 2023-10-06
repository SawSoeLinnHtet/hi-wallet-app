<div class="header-menu">
    <div class="d-flex justify-content-center">
        <div class="row w-100">
            <div class="col-md-8 offset-md-2 ">
                <div class="row">
                    <div class="col-2 text-center">
                        @if(!request()->is('/'))
                            <a href="#" class="back-btn">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        @endif
                    </div>
                    <div class="col-8 text-center">
                        <h3>
                            @yield('title')
                        </h3>
                    </div>
                    <div class="col-2 text-center">
                        <a href="#">
                            <i class="fas fa-bell"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>