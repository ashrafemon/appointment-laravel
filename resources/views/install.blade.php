<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Api Installer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>

<div class="container-fluid py-2">
    <div class="jumbotron">
        <h1 class="display-4">API Installer</h1>
        <hr/>

        <div class="row justify-content-center">
            {{--            <div class="col-md-3 col-sm-6 col-12 mb-3">--}}
            {{--                <form action="" method="post">--}}
            {{--                    <div class="card border-0 shadow-sm">--}}
            {{--                        <div class="card-header">--}}
            {{--                            <h5>Migration</h5>--}}
            {{--                        </div>--}}
            {{--                        <div class="card-body">--}}

            {{--                            @csrf--}}
            {{--                            <div class="form-group">--}}
            {{--                                <label for="dbname" class="small">Database Name</label>--}}
            {{--                                <input type="text" id="dbname" name="dbname" class="form-control form-control-sm"/>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group">--}}
            {{--                                <label for="dbtype" class="small">Database Type</label>--}}
            {{--                                <select name="dbtype" id="dbtype" class="form-control form-control-sm">--}}
            {{--                                    <option value="mysql">Mysql</option>--}}
            {{--                                    <option value="pgsql">Postgresql</option>--}}
            {{--                                    <option value="sqlite">Sqlite</option>--}}
            {{--                                </select>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group">--}}
            {{--                                <label for="dbport" class="small">Database Port</label>--}}
            {{--                                <input type="text" id="dbport" name="dbport" class="form-control form-control-sm"/>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group">--}}
            {{--                                <label for="dbusername" class="small">Database User</label>--}}
            {{--                                <input type="text" id="dbusername" name="dbusername"--}}
            {{--                                       class="form-control form-control-sm"/>--}}
            {{--                            </div>--}}

            {{--                            <div class="form-group">--}}
            {{--                                <label for="dbpassword" class="small">Database Password</label>--}}
            {{--                                <input type="text" id="dbpassword" name="dbpassword"--}}
            {{--                                       class="form-control form-control-sm"/>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="card-footer">--}}
            {{--                            <div class="text-right">--}}
            {{--                                <button type="submit" class="btn btn-primary btn-sm">Add Database</button>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </form>--}}
            {{--            </div>--}}

            <div class="col-md-5 col-12 mb-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success alert-dismissible">
                                <button class="close small" data-dismiss="alert">&times;</button>
                                <strong class="small font-weight-bold">{{Session::get('message')}}</strong>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>Migration Command</h5>
                            <hr/>
                            <a href="{{route('migrate.fresh')}}" class="btn btn-danger btn-sm btn-block">Fresh Migrate
                                Command</a>
                            <a href="{{route('migrate.new')}}" class="btn btn-info btn-sm btn-block">New Migrate
                                Command</a>
                            <a href="{{route('migrate.seed')}}" class="btn btn-success btn-sm btn-block">Seed
                                Command</a>
                        </div>

                        <div>
                            <h5>Temp Clear Command</h5>
                            <hr/>
                            <a href="{{route('cache.clear')}}" class="btn btn-danger btn-sm btn-block">Clear All
                                Cache</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>
</body>
</html>
