<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    
    
    
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis&family=Outfit:wght@700&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="/css/style.css">

   
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/5101d6241a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script>
        function dismissAlertElement(alertElement) {
            if (!alertElement || alertElement.dataset.hmDismissed === '1') {
                return;
            }

            alertElement.dataset.hmDismissed = '1';
            alertElement.classList.add('hm-alert-hiding');

            window.setTimeout(function () {
                alertElement.style.display = 'none';
            }, 350);
        }

        function closeAlert() {
            var alerts = document.querySelectorAll('#myAlert, .alert.alert-dismissible');
            for (var i = 0; i < alerts.length; i++) {
                if (alerts[i].offsetParent !== null) {
                    dismissAlertElement(alerts[i]);
                    break;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var alerts = document.querySelectorAll('#myAlert, .alert.alert-dismissible');
            alerts.forEach(function (alertElement) {
                window.setTimeout(function () {
                    dismissAlertElement(alertElement);
                }, 4000);
            });
        });
    </script>
    <script>
        window.hmInitTables = function (root) {
            if (!root) {
                return;
            }

            var tables = root.querySelectorAll('table[data-hm-datatable="true"]');

            function sanitizeCookieSegment(value) {
                return String(value || '').replace(/[^a-zA-Z0-9]/g, '_');
            }

            function getTableOrderCookieKey(table, index) {
                var pageKey = sanitizeCookieSegment(window.location.pathname || 'home');
                var tableKey = sanitizeCookieSegment(table.id || table.dataset.hmCookieKey || ('table_' + index));
                return 'hm_table_order_' + pageKey + '_' + tableKey;
            }

            function readCookie(name) {
                var encodedName = encodeURIComponent(name) + '=';
                var cookieParts = document.cookie.split(';');

                for (var i = 0; i < cookieParts.length; i++) {
                    var cookie = cookieParts[i].trim();
                    if (cookie.indexOf(encodedName) === 0) {
                        return decodeURIComponent(cookie.substring(encodedName.length));
                    }
                }

                return null;
            }

            function writeCookie(name, value, days) {
                var expires = '';
                if (typeof days === 'number') {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = '; expires=' + date.toUTCString();
                }

                document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
            }

            function readOrderFromCookie(cookieKey) {
                var raw = readCookie(cookieKey);
                if (!raw) {
                    return null;
                }

                try {
                    var parsed = JSON.parse(raw);
                    return Array.isArray(parsed) ? parsed : null;
                } catch (error) {
                    return null;
                }
            }

            tables.forEach(function (table, index) {
                if ($.fn.dataTable.isDataTable(table)) {
                    return;
                }

                var pageLength = parseInt(table.dataset.hmPageLength || '10', 10);
                var orderAttr = table.dataset.hmOrder;
                var responsiveAttr = (table.dataset.hmResponsive || 'true').toLowerCase();
                var scrollXAttr = (table.dataset.hmScrollX || 'false').toLowerCase();
                var cookieKey = getTableOrderCookieKey(table, index);
                var savedOrder = readOrderFromCookie(cookieKey);
                var options = {
                    responsive: responsiveAttr !== 'false',
                    scrollX: scrollXAttr === 'true',
                    autoWidth: false,
                    deferRender: true,
                    searchDelay: 250,
                    pageLength: pageLength,
                    lengthMenu: [10, 25, 50, 100],
                    dom: "<'hm-table-controls'lf>rt<'hm-table-footer'ip>",
                    language: {
                        search: '',
                        searchPlaceholder: table.dataset.hmSearchPlaceholder || 'Search'
                    },
                    columnDefs: [{ targets: 'no-sort', orderable: false }]
                };

                if (savedOrder) {
                    options.order = savedOrder;
                } else if (orderAttr) {
                    try {
                        options.order = JSON.parse(orderAttr);
                    } catch (error) {
                        options.order = [];
                    }
                }

                var dataTable = $(table).DataTable(options);

                dataTable.on('order.dt', function () {
                    writeCookie(cookieKey, JSON.stringify(dataTable.order()), 30);
                });
            });
        };

        $(document).ready(function () {
            window.hmInitTables(document);

            $(document).on('shown.bs.tab', 'button[data-bs-toggle="tab"]', function () {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
            });
        });
    </script>
   <style>
.welcome-message {
        color: #000;
        font-size: 50px;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        text-align:center;
    }

    @import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@200;300;400;500;600;700&display=swap');

body{

    font-family: 'Urbanist', sans-serif;
    overflow-x: hidden;
    padding: 0;
    margin: 0;
}

a{

    color: #fff;;
    text-decoration: none;
}

.sidebar-container{

    position: fixed;
    width: fit-content;
    height:100vh;
    z-index: 2;
}

.sidebar-menu{

    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 15px 15px 15px 35px;
    background: linear-gradient(180deg, #080155 1.76%, rgba(19, 13, 92,0.8888) 100%);
    overflow-y: hidden;
}

.nav-items .nav-item{

    width: 250px;
    padding: 30px 0;
    display: flex;
    align-items: flex-end;
}

.nav-item i{

    color:#c3b7c9;
    font-size: 28px;
    margin-right: 50px;
}

.nav-item span{

    font-size: 18px;
    letter-spacing: 2px;
    font-family: 'Outfit', sans-serif;
    width: 100%;
    border-bottom: 2px solid transparent;
    transition: all .8s ease;
}

.nav-item:hover i{

    color: #fff;
}

.nav-item:hover span{

    border-color: #fff;
}

.nav-locale a{

    width: 260px;
    display: flex;
    align-items: center;
    font-size: 18px;
    font-family: 'Outfit', sans-serif;
    letter-spacing: 2px;
    color: #fff;
    margin: 0 0 30px -10px;
}

.nav-locale i{

    background-color:  #080155;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 40px;
    transition: all .8s ease;
    border: 2px solid transparent;
}

.nav-locale i:hover{
  border-color:#fff;
}

.nav-toggle{
    position: absolute;
    right: -15px;
    top: 30px;
    font-size: 30px;
    color:  #080155;
    cursor: pointer;
    background: #fff;
    border-radius: 50%;
    padding: 1px 4px;
}


.sidebar-menu{
    width: 110px;
    transition: all .4s ease-out;
    overflow-x: hidden;
}

.sidebar-menu.show{

    width: 270px;
    transition: all .7s ease-out;
}

.sidebar-menu span{

    opacity: 0;
    transition: all .2s ease-out;
}

.sidebar-menu.show span{

    opacity: 1;
    transition: all 1.5s ease-out;
}


  /*tabs syling */
  #tab-position {
    display: flex;
    align-items: center;
    padding-top: 20px;
  }

  .nav-tabs {
    border: none;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 6px;
    border-radius: 18px;
    background: linear-gradient(135deg, #ffffff 0%, #f5efe6 100%);
    box-shadow: 0 12px 28px rgba(28, 26, 34, 0.12);
  }

  .nav-tabs .nav-link {
    position: relative;
    border: none;
    border-radius: 14px;
    padding: 10px 18px;
    margin: 0;
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    letter-spacing: 0.04em;
    color: var(--hm-ink);
    background: transparent;
    transition: all 0.2s ease;
  }

  .nav-tabs .nav-link:hover {
    color: var(--hm-ink);
    background: #f1e6d6;
  }

  .nav-tabs .nav-link.active {
    color: #fff;
    background: linear-gradient(135deg, var(--hm-navy) 0%, #33356c 100%);
    box-shadow: 0 10px 18px rgba(28, 26, 34, 0.2);
  }

  .nav-tabs .nav-link.active:hover {
    color: #fff;
  }

  .nav-tabs .nav-link:focus-visible {
    outline: none;
    box-shadow: 0 0 0 3px rgba(202, 165, 106, 0.35);
  }

  .tab-content {
    margin-top: 16px;
    padding: 20px;
    border: none;
    border-radius: 0;
    background: transparent;
    box-shadow: none;
  }

  @media (max-width: 768px) {
    .nav-tabs {
      border-radius: 14px;
      padding: 6px;
    }

    .nav-tabs .nav-link {
      padding: 8px 14px;
      font-size: 0.92rem;
    }
  }



@keyframes bounce { 0% { transform: translateY(0); } 100% { transform: translateY(-30px); } }

.content-container .content{

    max-width: 1200px;
    margin: 0 auto;
    padding-top: 6%;
}

.content .title{

    margin-bottom: 50px;
}

.title span{

    color: #DCB494;
    font-size: 60px;
}

.title h1{

    font-size: 84px;
    font-weight: 500;
    color: #844421;
    letter-spacing: 10px;
    margin-top: 0;
}

.content p{

    width: 90%;
    text-align: end;
    margin-left: auto;
    color: #B36B43;
    letter-spacing: 2px;
    font-size: 18px;
}
/*Alert styling */
.close-alert:hover{
  cursor:pointer;
}
#myAlert {
    max-width: min(550px, calc(100vw - 32px));
    width: fit-content;
    display: inline-block;
    position: fixed;
    top: 96px;
    right: 15px;
    margin: 0;
    z-index: 1200;
    transition: opacity 0.35s ease, transform 0.35s ease;
}

.hm-alert-hiding {
    opacity: 0;
    transform: translateY(-6px);
}

/*header */
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

:root {
    --hm-ink: #1c1a22;
    --hm-ink-muted: #60616a;
    --hm-sand: #f6f1ea;
    --hm-brass: #caa56a;
    --hm-navy: #5a3bd1;
    --hm-card: #ffffff;
    --hm-border: #e7e0d4;
    --hm-shadow: 0 18px 40px rgba(28, 26, 34, 0.12);
}

.btn-primary {
    background-color: var(--hm-navy);
    border-color: var(--hm-navy);
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active,
.btn-primary:focus-visible {
    background-color: #6c4fe6;
    border-color: #6c4fe6;
}

.btn-primary:focus,
.btn-primary:focus-visible {
    box-shadow: 0 0 0 0.2rem rgba(90, 59, 209, 0.35);
}

.hm-table-card {
    position: relative;
    padding: 18px;
    border-radius: 18px;
    border: 1px solid var(--hm-border);
    background: linear-gradient(135deg, #ffffff 0%, #fbf7f1 100%);
    box-shadow: var(--hm-shadow);
    overflow: hidden;
}

.hm-table-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(202, 165, 106, 0.16), transparent 46%);
    pointer-events: none;
}

.hm-table-rail {
    position: relative;
    z-index: 1;
}

.hm-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    color: var(--hm-ink);
    background: transparent;
}

.hm-table thead th {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--hm-ink-muted);
    background: rgba(231, 224, 212, 0.7);
    border: none;
    padding: 14px 16px;
}

.hm-table tbody tr {
    background: var(--hm-card);
    border: 1px solid var(--hm-border);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hm-table tbody tr:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(28, 26, 34, 0.12);
}

.hm-table tbody td {
    padding: 14px 16px;
    border: none;
    font-size: 0.95rem;
    color: var(--hm-ink);
}

.hm-table tbody td:first-child {
    border-radius: 14px 0 0 14px;
}

.hm-table tbody td:last-child {
    border-radius: 0 14px 14px 0;
}

.hm-table-card .dataTables_wrapper .dataTables_length,
.hm-table-card .dataTables_wrapper .dataTables_filter {
    margin-bottom: 16px;
    color: var(--hm-ink-muted);
    font-weight: 500;
}

.hm-table-card .dataTables_wrapper .dataTables_filter input,
.hm-table-card .dataTables_wrapper .dataTables_length select {
    border-radius: 999px;
    border: 1px solid var(--hm-border);
    padding: 6px 14px;
    margin-left: 8px;
    background: #fffaf2;
    color: var(--hm-ink);
}

.hm-table-card .dataTables_wrapper .dataTables_filter input:focus,
.hm-table-card .dataTables_wrapper .dataTables_length select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(202, 165, 106, 0.25);
    border-color: var(--hm-brass);
}

.hm-table-card .dataTables_wrapper .dataTables_paginate {
    margin-top: 18px;
}

.hm-table-card .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 999px;
    border: 1px solid var(--hm-border);
    padding: 4px 12px;
    margin: 0 4px;
    color: var(--hm-ink);
    background: #fffaf2;
}

.hm-table-card .dataTables_wrapper .dataTables_paginate .paginate_button.current,
.hm-table-card .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: var(--hm-navy);
    color: #fff !important;
    border-color: var(--hm-navy);
}

.hm-table-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f1e6d6;
    color: var(--hm-ink) !important;
    border-color: var(--hm-brass);
}

.hm-table-card .dataTables_wrapper .dataTables_info {
    color: var(--hm-ink-muted);
}

.hm-table-card .dtr-details {
    width: 100%;
}

.hm-table-card .dtr-title {
    font-weight: 600;
    color: var(--hm-ink-muted);
}

.hm-table-card .dtr-data {
    color: var(--hm-ink);
}

@media (max-width: 768px) {
    .hm-table-card {
        padding: 14px;
        border-radius: 14px;
    }

    .hm-table thead th,
    .hm-table tbody td {
        padding: 12px 12px;
        font-size: 0.9rem;
    }
}

.logout-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(15, 23, 42, 0.55);
    z-index: 1100;
}

.logout-modal.show {
    display: flex;
}

.logout-modal-dialog {
    width: min(420px, 100%);
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.25);
}

.logout-modal-title {
    margin: 0;
    font-size: 1.2rem;
    color: #0f172a;
}

.logout-modal-text {
    margin: 10px 0 0;
    color: #334155;
}

.logout-modal-actions {
    margin-top: 18px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.logout-btn {
    border: none;
    border-radius: 10px;
    padding: 9px 14px;
    font-weight: 600;
    cursor: pointer;
}

.logout-btn-cancel {
    background: #e5e7eb;
    color: #111827;
}

.logout-btn-confirm {
    background: #b91c1c;
    color: #ffffff;
}

body.modal-open {
    overflow: hidden;
}
    </style>
  </head>
<body>
<div id="hm-toast-container" class="hm-toast-container" aria-live="polite" aria-atomic="true"></div>

<div class="sidebar-container">

<div class="sidebar-menu">

    <div class="nav-items">

            <a class="nav-item" href="{{ route('home') }}">
                <i class="fas fa-home"></i>
                <span class="ms-1 d-none d-sm-inline">Home</span>
            </a>
            <a class="nav-item" href="{{ route('hotel') }}">
                <i class="fa-sharp fa-solid fa-hotel"></i>
                <span class="ms-1 d-none d-sm-inline">My Hotel</span>
            </a>
            <a class="nav-item" href="{{ route('staff') }}">
                <i class="fa-solid fa-user"></i> 
                <span class="ms-1 d-none d-sm-inline">Staff</span>
            </a>
            <a class="nav-item" href="{{ route('rooms') }}">
                <i class="fa-solid fa-bed"></i>
                <span class="ms-1 d-none d-sm-inline">Rooms</span>
            </a>
            <a class="nav-item" href="{{ route('booking') }}">
                <i class="fa-solid fa-calendar"></i>
                <span class="ms-1 d-none d-sm-inline">Bookings</span>
            </a>
            <a class="nav-item" href="{{ route('customer') }}">
                <i class="fa-solid fa-person"></i>
                <span class="ms-1 d-none d-sm-inline">Customers</span>
            </a>
            <a class="nav-item" href="{{ route('payment') }}">
                <i class="fa-solid fa-money-bill"></i>
            </i><span class="ms-1 d-none d-sm-inline">Payment</span></a>
            </a>
    </div>

    <div class="nav-locale" id="logout">
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" data-hm-ajax="false">
      @csrf
        </form>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); confirmLogout('logout-form');">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Log Out</span>
        </a>
    </div>

    <div class="nav-toggle">
        <i onclick="handleMenuDisplay(this)" class="fa-solid fa-circle-chevron-right"></i>
    </div>
</div>
</div>

<div id="logout-confirm-modal" class="logout-modal" aria-hidden="true">
    <div class="logout-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <h3 id="logout-modal-title" class="logout-modal-title">Log out</h3>
        <p class="logout-modal-text">Are you sure you want to log out?</p>
        <div class="logout-modal-actions">
            <button type="button" class="logout-btn logout-btn-cancel" id="logout-cancel-btn">Cancel</button>
            <button type="button" class="logout-btn logout-btn-confirm" id="logout-confirm-btn">Log Out</button>
        </div>
    </div>
</div>

    <script>
function handleMenuDisplay(element){

const menu = document.querySelector('.sidebar-menu');

if(menu.classList.contains('show')){
    
    menu.classList.remove('show');
    element.classList.add('fa-circle-chevron-right');
    element.classList.remove('fa-circle-chevron-left');

}else{

    menu.classList.add('show');
    element.classList.remove('fa-circle-chevron-right');
    element.classList.add('fa-circle-chevron-left');
}
}

var logoutFormId = null;
var logoutModal = document.getElementById('logout-confirm-modal');
var logoutCancelBtn = document.getElementById('logout-cancel-btn');
var logoutConfirmBtn = document.getElementById('logout-confirm-btn');

function toggleLogoutModal(show){
    if (!logoutModal) {
        return;
    }

    logoutModal.classList.toggle('show', show);
    logoutModal.setAttribute('aria-hidden', show ? 'false' : 'true');
    document.body.classList.toggle('modal-open', show);

    if (show && logoutCancelBtn) {
        logoutCancelBtn.focus();
    }
}

function confirmLogout(formId){
    logoutFormId = formId;
    toggleLogoutModal(true);
}

if (logoutCancelBtn) {
    logoutCancelBtn.addEventListener('click', function () {
        toggleLogoutModal(false);
    });
}

if (logoutConfirmBtn) {
    logoutConfirmBtn.addEventListener('click', function () {
        if (!logoutFormId) {
            return;
        }

        var logoutForm = document.getElementById(logoutFormId);
        if (logoutForm) {
            logoutForm.submit();
        }
    });
}

if (logoutModal) {
    logoutModal.addEventListener('click', function (event) {
        if (event.target === logoutModal) {
            toggleLogoutModal(false);
        }
    });
}

document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && logoutModal && logoutModal.classList.contains('show')) {
        toggleLogoutModal(false);
    }
});
    </script>
  <div class="container" style="margin-left:150px;">
  <div class="row" id="welcome" style="background-color: #ebe8e8; min-height:80px;">
    <!-- Left section for title -->
    <div class="col-10"  style="display: flex; align-items: center;">
        <h2 style=" font-family: 'Outfit', sans-serif;">{{$tittle}}</h2>
    </div>
    
    <!-- Right section for image and spans -->
    <div class="col-2" style="display: flex; align-items: center; justify-content: flex-end;">
    <img class="rounded-circle header-profile-user"  src="{{url('./storage/images/user_circle_icon_152504.png')}}" alt="Header" style=" margin-right:8px;">
    <span class="ms-2 d-none d-sm-block user-item-desc" style="display: flex; align-items: center; margin-right:12px;">
        <span class="user-name">{{ Auth::user()->name }}</span>
        <span class="user-sub-title">Administrator</span>
    </span>
</div>

</div>
            @yield('content')
    </div>
</body>
</html>
