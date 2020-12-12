@extends('layouts.app')
@section('content')
    <section class="page-section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">Error</div>
                        <div class="card-body text-center">
                            <h1 class="my-3">
                                <i class="fa fa-check-circle"></i>
                                <div class="alert-danger">Payment error</div>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function () {
                parent.document.location.href = 'add url for redirect /'; // the redirect goes here
            }, 10000); // 10 seconds
        </script>
    </section>
@endsection
