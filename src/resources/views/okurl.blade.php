@extends('admin.layouts.app')
@section('content')
    <section class="page-section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">Successful transaction</div>
                        <div class="card-body text-center">
                            <h1 class="my-3">
                                <i class="fa fa-check-circle"></i>
                            </h1>
                            <p>Successful transaction </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function () {
                parent.document.location.href = 'https://www.hotelvlaho.com/'; // the redirect goes here
            }, 3000); // 5 seconds
        </script>
    </section>
@endsection
