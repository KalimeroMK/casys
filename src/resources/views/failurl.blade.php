@extends('layouts.app')

@section('content')
    <section class="page-section">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">Unsuccessful transaction</div>
                        <div class="card-body text-center">
                            <h1 class="my-3">
                                <i class="fa fa-times-circle"></i>
                            </h1>
                            <p>Unsuccessful transaction </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        setTimeout(function () {
            parent.document.location.href = 'add redirect url '; // the redirect goes here
        }, 3000); // 5 seconds
    </script>
@endsection
