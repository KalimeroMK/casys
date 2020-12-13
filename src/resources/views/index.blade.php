@extends('layouts.app')

@section('content')
    <section class="page-section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">Payment system</div>
                        <div class="card-body p-0">
                            <form
                                    action="https://www.cpay.com.mk/client/Page/default.aspx?xml_id=/.loginToPay/.simple/"
                                    method="POST"
                                    id="cPayForm"
                                    name="cPayForm"
                                    target="cPayFrame"
                            >
                                @csrf
                                <input id="PayToMerchant" name="PayToMerchant"
                                       value="{{ $casys['required']['PayToMerchant'] }}"
                                       type="hidden"/>
                                <input id="MerchantName" name="MerchantName"
                                       value="{{ $casys['required']['MerchantName'] }}"
                                       type="hidden"/>
                                <input id="AmountToPay" name="AmountToPay"
                                       value="{{ $casys['required']['AmountToPay'] }}" type="hidden"/>
                                <input id="AmountCurrency" name="AmountCurrency" value="MKD" type="hidden"/>
                                <input id="Details1" name="Details1" value="{{ $casys['required']['Details1'] }}"
                                       type="hidden"/>
                                <input id="Details2" name="Details2" value="{{ $casys['required']['Details2'] }}"
                                       type="hidden"/>
                                <input id="PaymentOKURL" name="PaymentOKURL"
                                       value="{{ $casys['required']['PaymentOKURL'] }}" type="hidden"/>
                                <input id="PaymentFailURL" name="PaymentFailURL"
                                       value="{{ $casys['required']['PaymentFailURL'] }}" type="hidden"/>
                                <input id="CheckSumHeader" name="CheckSumHeader"
                                       value="{{ $casys['checkSumHeader']}}"
                                       type="hidden"/>
                                <input id="CheckSum" name="CheckSum" value="{{ $casys['checkSum'] }}"
                                       type="hidden"/>
                                <input id="FirstName" name="FirstName" value="{{ $casys['user']['FirstName'] }}"
                                       type="hidden"/>
                                <input id="LastName" name="LastName" value="{{ $casys['user']['LastName'] }}"
                                       type="hidden"/>
                                <input id="Country" name="Country" value="{{ $casys['user']['Country'] }}"
                                       type="hidden"/>
                                <input id="Email" name="Email" value="{{ $casys['user']['Email'] }}"
                                       type="hidden"/>
                                <input id="OriginalAmount" name="OriginalAmount"
                                       value="{{ $casys['required']['OriginalAmount'] }}" type="hidden"/>
                                <input id="OriginalCurrency" name="OriginalCurrency"
                                       value="{{ $casys['required']['OriginalCurrency'] }}" type="hidden"/>
                                <input type="submit" class="d-none" value="Pay"/>
                            </form>
                            <div class="embed-responsive w-100" style="min-height: 545px">
                                <iframe
                                        class="embed-responsive-item"
                                        src="{{ route('loader') }}"
                                        name="cPayFrame"
                                        id="cPayFrame">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            ('#cPayForm').delay(5000).submit();
        })
    </script>
@endpush
