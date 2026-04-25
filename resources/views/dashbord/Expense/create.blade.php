<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Dashbord</title>
      
      {{-- css --}}
      @include('dashbord.layouts.css')
    </head>
  <body class="  ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
      {{-- left navbar --}}
        @include('dashbord.layouts.left_nav');

        {{-- top navbar --}}
        @include('dashbord.layouts.top_nav')

      <div class="content-page">
        {{-- main page --}}
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                   <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-danger"><i class="ri-home-4-line mr-1 float-left"></i>Dashbord</a></li>
                                   <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}" class="text-danger">Expense List</a></li>
                                   <li class="breadcrumb-item active" aria-current="page">Add Expense</li>
                                </ol>
                             </nav>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Add Expense</h4>
                            </div>
                            @if ($errors->any())
                            <div class="alert alert-danger">
                               <ul>
                                     @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                     @endforeach
                               </ul>
                            </div>
                         @endif
                        </div>
                        <div class="card-body">
                            <form action="{{ route('expenses.store') }}" method="POST" data-toggle="validator" novalidate="true">
                                @csrf
                                <div class="row"> 
                                    <div class="col-md-4">                      
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" required="" placeholder="Enter Name" name="name">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone Number</label>
                                            <input type="text" class="form-control" required="" placeholder="Enter Phone Number" name="phone">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expense">Expense Type</label>
                                            <select class="custom-select" id="expense" name="expense_type">
                                                <option value=" " >Please Select</option>    
                                                <option value="transport" >Transport</option>
                                                <option value="maintanance">Maintanance</option>
                                                <option value="purchase">Purchase</option>
                                                <option value="others">Others</option>
                                            </select>
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" class="form-control" placeholder="Enter Amount"  required=""  name="amount">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="Satus">Satus</label>
                                            <select class="custom-select"  id="Satus" name="status">
                                                <option value=" ">Please Select</option>
                                                <option value="paid" >Paid</option>
                                                <option value="due" >Due</option>
                                            </select>
                                         </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Due</label>
                                            <input type="text" class="form-control" placeholder="Enter Due Amount" name="due">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" class="form-control" required="" name="date">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Note</label>
                                            <textarea class="form-control" id="horizontalTextarea" rows="1" placeholder="Textarea" required="" name="description"></textarea>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                </div>                            
                                <button type="submit" class="btn btn-primary mr-2 disabled">Add Expense</button>
                                <button type="reset" class="btn btn-danger">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
           </div>
            <!-- Page end  -->
        </div>
      </div>
    </div>

  {{-- js --}}
  @include('dashbord.layouts.js')
  </body>
</html> 