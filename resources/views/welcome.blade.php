<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Interview project</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}">

        <style>
            body{
               padding: 40px; 
            }
        </style>
    </head>
    <body>

        <div class="container">
            <h2>Overview</h2><br>
            <form class="form-inline" action="{{ route('filter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="date" name="from_date" class="form-control" placeholder="xcvxc">    
                </div>
                <div class="form-group">
                    <input type="date" name="to_date" class="form-control" placeholder="xcvxc">    
                </div>
                <div class="form-group">
                    <select class="form-control" name="filter_by">
                    <option value="Woocommerce">Woocommerce</option>               
                    <option value="Contact form 7">Contact form 7</option>               
                    <option value="Clasic editor">Clasic editor</option>               
                    <option value="Yoast SEO">Yoast SEO</option>               
                    </select>
                </div>
    
                <button type="submit" class="btn btn-default">Search</button>
            </form>            
        </div><br><br>

        <div class="container">
            <div class="row">
              <div class="col-md-4">
                  <p>Plugin</p>
              </div>
              <div class="col-md-8">
                  <p>{{ $name }}</p>
              </div>
            </div>
        </div><br><br>

        <div class="container">
            <div class="row">
                <div class="col-md-3 p-3 card">
                    <h1>70%</h1>
                    <h6>Activation rate</h6>
                </div>
                <div class="col-md-3 p-3 card">
                    <h1>70%</h1>
                    <h6>Deactivation rate</h6>
                </div>
                <div class="col-md-3 p-3 card">
                    <h1>70</h1>
                    <h6>Activated</h6>
                </div>
                <div class="col-md-3 p-3 card">
                    <h1>70</h1>
                    <h6>Deactivated</h6>
                </div>
            </div>
        </div><br><br>

        <div class="container">
            <div class="row">
              <div class="col-md-6">
                  <h3>Active install</h3>
                  <span>{{ $name }}</span><span class="ml-5">{{ $active_install }}</span>
              </div>
              <div class="col-md-6">
                  <h3>Downloads</h3>
                  <span>{{ $name }}</span><span class="ml-5">{{ $download }}</span>
              </div>
            </div>
        </div><br><br>

          <div class="container">
              <div class="row">
                <div class="col-md-6">
                    <h3>Activation/Deactivation</h3>
                </div>
              </div>
          </div>

        <script src="{{ asset('bootstrap.min.js') }}"></script>
    </body>
</html>
