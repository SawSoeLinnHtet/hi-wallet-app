@extends('frontend.layouts.app')

@section('title', 'Scan & Pay')

@section('content')
    <div class="receive-qr">
        <div class="card">
            <div class="card-body p-2 px-3 text-center py-4">
                @include('frontend.layouts.flash')
                <div class="mb-0 p-3">
                    <img src="{{ asset('frontend/img/scanandpay.png') }}" style="width: 220px" alt="Scan Image">
                </div>
                <p class="mb-3">Click button, put QR code in the frame and pay.</p>
                <button class="btn btn-theme px-3 btn-sm" data-bs-toggle="modal" data-bs-target="#scan-modal">
                    Scan
                </button>

                <!-- Modal -->
                <div class="modal fade scan-modal" id="scan-modal" tabindex="-1" aria-labelledby="scan-modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Scan & Pay</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner" width="100%" height="240px"></video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('frontend/js/scanner/qr-scanner.umd.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var videoElem = document.getElementById('scanner');

            const qrScanner = new QrScanner(videoElem, function (result) {
                if(result){
                    $('#scan-modal').modal('hide');
                    qrScanner.stop();

                    window.location.replace('/scan_and_pay/form?to_phone=' + result);
                }
            });

            $('#scan-modal').on('shown.bs.modal', function (event) {
                qrScanner.start();
            });

            $('#scan-modal').on('hidden.bs.modal', function (event) {
                qrScanner.stop();
            });
        })
    </script>
@endpush