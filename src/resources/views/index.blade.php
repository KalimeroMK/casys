@extends('layouts.app')

@section('content')
    <section class="page-section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">{{ __('Hotel Vlaho payment system') }}</div>
                        <div class="card-body p-0">
                            <form
                                action="https://www.cpay.com.mk/client/Page/default.aspx?xml_id=/.loginToPay/.simple/"
                                method="POST"
                                id="cPayForm"
                                name="cPayForm"
                                target="cPayFrame"
                            >
                                @foreach ($casys['required'] as $key => $value)
                                    <input id="{{ $key }}" name="{{ $key }}" value="{{ $value }}" type="hidden"/>
                                @endforeach
                                <input id="CheckSumHeader" name="CheckSumHeader" value="{{ $casys['checkSumHeader'] }}"
                                       type="hidden"/>
                                <input id="CheckSum" name="CheckSum" value="{{ $casys['checkSum'] }}" type="hidden"/>
                                @foreach ($casys['fields'] as $key => $value)
                                    <input id="{{ $key }}" name="{{ $key }}" value="{{ $value }}" type="hidden"/>
                                @endforeach
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
            $('#cPayForm').delay(5000).submit();
        })
    </script>
@endpush
