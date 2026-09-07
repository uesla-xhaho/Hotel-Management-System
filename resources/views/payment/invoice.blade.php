<link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis&family=Outfit:wght@700&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">    
<style>
   body{
    margin-top:20px;
    background:#eee;
}

.invoice {
    background: #fff;
    padding: 20px
}

.invoice-company {
    font-size: 20px
}

.invoice-header {
    margin: 0 -20px;
    background: #f0f3f4;
    padding: 20px
}

.invoice-date,
.invoice-from,
.invoice-to {
    display: table-cell;
    width: 1%
}

.invoice-from,
.invoice-to {
    padding-right: 20px
}

.invoice-date .date,
.invoice-from strong,
.invoice-to strong {
    font-size: 16px;
    font-weight: 600
}

.invoice-date {
    text-align: right;
    padding-left: 20px
}

.invoice-price {
    background: #f0f3f4;
    display: table;
    width: 100%
}

.invoice-price .invoice-price-left,
.invoice-price .invoice-price-right {
    display: table-cell;
    padding: 20px;
    font-size: 20px;
    font-weight: 600;
    width: 75%;
    position: relative;
    vertical-align: middle
}

.invoice-price .invoice-price-left .sub-price {
    display: table-cell;
    vertical-align: middle;
    padding: 0 20px
}

.invoice-price small {
    font-size: 12px;
    font-weight: 400;
    display: block
}

.invoice-price .invoice-price-row {
    display: table;
    float: left
}

.invoice-price .invoice-price-right {
    width: 25%;
    background: #2d353c;
    color: #fff;
    font-size: 28px;
    text-align: right;
    vertical-align: bottom;
    font-weight: 300
}

.invoice-price .invoice-price-right small {
    display: block;
    opacity: .6;
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 12px
}

.invoice-footer {
    border-top: 1px solid #ddd;
    padding-top: 10px;
    font-size: 10px
}

.invoice-note {
    color: #999;
    margin-top: 80px;
    font-size: 85%
}

.invoice>div:not(.invoice-footer) {
    margin-bottom: 20px
}

.btn.btn-white, .btn.btn-white.disabled, .btn.btn-white.disabled:focus, .btn.btn-white.disabled:hover, .btn.btn-white[disabled], .btn.btn-white[disabled]:focus, .btn.btn-white[disabled]:hover {
    color: #2d353c;
    background: #fff;
    border-color: #d9dfe3;
}
</style>

@foreach($results as $row)
<div class="container">
       <div class="col-md-12">
          <div class="invoice">
             <!-- begin invoice-company -->
             <div class="invoice-company text-inverse">
                <span class="pull-right hidden-print" style="float: right;">
                <button onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</button>
                </span>
                <strong>{{ $row->booking->room->hotel->name }}</strong>
             </div>
             <!-- end invoice-company -->
             <!-- begin invoice-header -->
             <div class="invoice-header">
                <div class="invoice-from">
                   <small>from</small>
                   <address class="m-t-5 m-b-5">
                   <strong class="text-inverse">Name: </strong>{{ $row->booking->room->hotel->name }}<br>
                   <strong class="text-inverse">Address:</strong> {{ $row->booking->room->hotel->address }}<br>
                   <strong class="text-inverse">Phone:</strong> {{ $row->booking->room->hotel->phone }}<br>
                   <strong class="text-inverse">Email:</strong> {{ $row->booking->room->hotel->email }}
                   </address>
                </div>
                <div class="invoice-to">
                   <small>to</small>
                   <address class="m-t-5 m-b-5">
                      <strong class="text-inverse">Name: </strong>{{ $row->customer->name }}<br>
                      <strong class="text-inverse">Personal ID: </strong>{{ $row->customer->personal_id }}<br>
                      <strong class="text-inverse">Phone:</strong> {{ $row->customer->phone }}<br>
                   </address>
                </div>
                <div class="invoice-date">
                   <small>Invoice / Date</small>
                   <div class="date text-inverse m-t-5">{{ $row->booking->checkout }}</div>
                   <div class="invoice-detail">
                   #{{ $row->id }}<br>
                   </div>
                </div>
             </div>
             <!-- end invoice-header -->
             <!-- begin invoice-content -->
             <div class="invoice-content">
                <!-- begin table-responsive -->
                <div class="table-responsive">
                   <table class="table table-invoice">
                      <thead>
                         <tr>
                            <th>BOOKING DESCRIPTION</th>
                            <th class="text-center" width="10%">RATE</th>
                            <th class="text-center" width="10%">DURATION</th>
                            <th class="text-center" width="20%">TOTAL</th>
                         </tr>
                      </thead>
                      <tbody>
                         <tr>
                            <td>
                               <span class="text-inverse">Booking for Room {{ $row->booking->room->room_number ?? ('#' . $row->booking->room->id) }} for {{ $row->duration }} nights</span><br>
                               <small>Checked in: {{ $row->booking->checkin }}&nbsp;&nbsp; Checked Out: {{ $row->booking->checkout }}</small>
                            </td>
                            <td class="text-center">$ {{ $row->booking->room->price }}</td>
                            <td class="text-center">{{ $row->duration}}</td>
                            <td class="text-center">$ {{ $row->price }} </td>
                         </tr>
                      </tbody>
                   </table>
                </div>
                <!-- end table-responsive -->
                <!-- begin invoice-price -->
                <div class="invoice-price">
                   <div class="invoice-price-left">
                      <div class="invoice-price-row">
                        <span class="text-inverse">TOTAL AMOUNT :</span>
                      </div>
                   </div>
                   <div class="invoice-price-right">
                     <span class="f-w-600">${{ $row->price }}</span>
                   </div>
                </div>
                <!-- end invoice-price -->
             </div>
             <!-- end invoice-content -->
             <!-- begin invoice-note -->
             <div class="invoice-note">
                * Make all cheques payable to {{ $row->booking->room->hotel->name }}<br>
                * Payment is due within 30 days<br>
                * If you have any questions concerning this invoice, contact  {{ $row->booking->room->hotel->phone }}
             </div>
             <!-- end invoice-note -->
             <!-- begin invoice-footer -->
             <div class="invoice-footer">
                <p class="text-center m-b-5 f-w-600">
                   THANK YOU FOR YOUR BUSINESS
                </p>
                <p class="text-center">
                   <span class="mr-5" style="margin-right:4px;"><i class="fa fa-fw fa-lg fa-phone-volume"></i>{{ $row->booking->room->hotel->phone }}</span>
                   <span class="mr-10"><i class="fa fa-fw fa-lg fa-envelope"></i> {{ $row->booking->room->hotel->email }}</span>
                </p>
             </div>
             <!-- end invoice-footer -->
          </div>
       </div>
    </div>
@endforeach


