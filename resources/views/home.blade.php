@extends('layouts.app')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dosis&family=Libre+Baskerville&family=Outfit:wght@700&family=Roboto&display=swap" rel="stylesheet">
<style>

    .header-profile-user {
    height: 60px;
    width: 60px;
    border: 1px solid #8a8371;
    padding: 3px;
}
.user-sub-title {
    color: #74788d;
    font-size: 11px;
    font-weight: 600;
}
.user-name {
    font-size: 14.4px;
    font-weight: 600;
    display: block;
    color: #495057;
    text-transform: uppercase;
}
.row{
    margin-bottom:20px;
}

#nr{
    font-family: 'Libre Baskerville', serif;
    font-weight:600;
}
</style>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Visitors', 'Bookings'],
        ['2018',  860,       1120],
        ['2019',  1230,      740],
        ['2020',  900,      840],
        ['2021',  1500,      700],
        ['2022',  1750,      850], 
        ['2023',  2000,      950],
        ['2024',  2150,     1000],
        ['2025',  2300,     1050],
        ['2026',  2450,     1100]
    ]);

        var options = {
          title: 'Visitors and Bookings',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

<script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var rows = [
          ['Single', Number({{ $singleCount }})],
          ['Double', Number({{ $doubleCount }})],
          ['Twin', Number({{ $twinCount }})],
          ['Quad', Number({{ $quadCount }})],
          ['Suite', Number({{ $suiteCount }})],
          ['Villa', Number({{ $villaCount }})]
        ];
        var total = rows.reduce(function(sum, row) {
          return sum + row[1];
        }, 0);
        var chartRows = rows.map(function(row) {
          var label = row[0];
          var value = row[1];
          if (total === 0) {
            return [label, value];
          }

          var pct = ((value / total) * 100).toFixed(1).replace('.0', '');
          return [label + ' (' + pct + '%)', value];
        });
        var data = google.visualization.arrayToDataTable(
          [['Room Type', 'Count']].concat(chartRows)
        );

        var options = {
          title: 'Room Types',
          pieHole: 0.22,
          pieSliceText: 'percentage',
          sliceVisibilityThreshold: 0,
          pieSliceTextStyle: {
            color: '#ffffff',
            fontSize: 11,
            bold: true
          },
          chartArea: {
            left: 16,
            top: 36,
            width: '88%',
            height: '82%'
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
<?php
        $tittle="Dashboard"
?>

<div class="row">
      <div class="col-xl-3 col-sm-6 col-12">
          <div class="card board1 fill">
              <div class="card-body">
                  <div class="dash-widget-header">
                      <div>
                          <h3 class="card_widget_header" id="nr">{{ $bookingCount }}</h3>
                          <h6 class="text-muted">Total Booking</h6> </div>
                      <div class="ml-auto mt-md-3 mt-lg-0"> <span class="opacity-7 text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="#3518d6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                      <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                      <circle cx="8.5" cy="7" r="4"></circle>
                      <line x1="20" y1="8" x2="20" y2="14"></line>
                      <line x1="23" y1="11" x2="17" y2="11"></line>
                      </svg></span> </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-xl-3 col-sm-6 col-12">
          <div class="card board1 fill">
              <div class="card-body">
                  <div class="dash-widget-header">
                      <div>
                          <h3 class="card_widget_header" id="nr">{{ $roomCount }}</h3>
                          <h6 class="text-muted">Available Rooms</h6> </div>
                      <div class="ml-auto mt-md-3 mt-lg-0"> <i class="fa-solid fa-bed" style="font-size: 25px; color:#3518d6;"></i></div>
                  </div>
              </div>
          </div>
 </div>
          <div class="col-xl-3 col-sm-6 col-12">
          <div class="card board1 fill">
              <div class="card-body">
                  <div class="dash-widget-header">
                      <div>
                          <h3 class="card_widget_header" id="nr">{{ $customerCount }}</h3>
                          <h6 class="text-muted">Total Customers</h6> </div>
                      <div class="ml-auto mt-md-3 mt-lg-0">
                      <i class="fa-solid fa-person" style="font-size: 25px; color:#3518d6;"></i>
                    </div>
                  </div>
              </div>
          </div>
          </div>

          <div class="col-xl-3 col-sm-6 col-12">
          <div class="card board1 fill">
              <div class="card-body">
                  <div class="dash-widget-header">
                      <div>
                          <h3 class="card_widget_header" id="nr">{{ $visitors }}</h3>
                          <h6 class="text-muted">Total Visitors</h6> </div>
                      <div class="ml-auto mt-md-3 mt-lg-0"> 
                        <i class="fa-solid fa-user-group" style="font-size: 25px; color:#3518d6;"></i> </div>
                  </div>
              </div>
          </div>


          </div>
          </div>


          <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">VISITORS</h4> </div>
                        <div class="card-body">
                        <div id="curve_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h4 class="card-title">ROOMS BOOKED</h4> </div>
                        <div class="card-body">
                        <div id="donutchart"></div>
                        </div>
                    </div>
                </div>
            </div>




          <div class="row">
      <div class="col-md-12 d-flex">
          <div class="card card-table flex-fill">
          <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title float-left mt-2">Bookings</h4>
                  <a href="{{ route('booking') }}"><button type="button" class="btn btn-primary float-right veiwbutton">View All</button></a>
              </div>
              <div class="card-body">
                  <div class="table-responsive" style=" text-align: center;">
                      @component('layouts.data-table', ['id' => 'home-bookings'])
                          <thead>
                              <tr>
                              <th class="text-center">Booking ID</th> 
                                <th class="text-center">Customer Name</th>
                                <th class="text-center">Number of guests</th>
                                <th class="text-center">Room Number</th>
                                <th class="text-center">Check In</th>
                                <th class="text-center">Check Out</th>
                                <th class="text-center">Comment</th>
                                <th class="text-center">Status</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($bookings as $row )
                              <tr>
                              <td>{{ $row['id'] }}</td>
                              <td>{{ $row->customer->name ?? ' '  }}</td>
                              <td>{{ $row['guests'] }}</td>
                              <td>{{ optional($row->room)->room_number ?? 'Unassigned' }}</td>
                              <td>{{ $row['checkin'] }}</td>
                              <td>{{ $row['checkout'] }}</td>
                              <td>{{ $row['comment'] ?? ' ' }}</td>
                              <td ><span style="border-radius:15px; color: #fff; padding: 7px;  background-color:{{ $row['status'] === 'Confirmed' ? 'blue' : ($row['status'] === 'Completed' ? 'green' : 'black')}}">{{ $row['status'] }}</span></td>          
                              </tr>
                              @endforeach
                          </tbody>
                      @endcomponent
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

@endsection

