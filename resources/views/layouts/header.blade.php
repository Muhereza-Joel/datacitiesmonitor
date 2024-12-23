<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{$pageTitle ?? 'M $ E Monitor'}}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- Favicons -->
  <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
  <link href="{{ asset('assets/img/logo.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
  <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css" integrity="sha512-UiKdzM5DL+I+2YFxK+7TDedVyVm7HMp/bN85NeWMJNYortoll+Nd6PU9ZDrZiaOsdarOyk9egQm6LOJZi36L2g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css" />
  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dark.css') }}" rel="stylesheet">

  <style>
    #loading-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.1);
      z-index: 9999;
    }

    #loading-indicator {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 50px;
      height: 50px;
      border: 8px dotted green;
      border-radius: 50%;
      border-top: 8px solid #e74c3c;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }



    .btn {
      border-radius: 5px !important;
    }

    .btn-primary {
      background-color: #59879e;
    }

    .highlight {
      background-color: yellow;
      /* or any color you prefer */
      font-weight: bold;
      /* optional for emphasis */
    }

    .two-line-truncate {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      /* Limit to 2 lines */
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .one-line-truncate {
      display: -webkit-box;
      -webkit-line-clamp: 1;
      /* Limit to 2 lines */
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .pagetitle {
      background-color: #ffffff;
      margin-bottom: 8px;
      padding: 4px 8px;
      border-radius: 5px;
      border: 2px solid #e0dada;
    }

    .shepherd-modal {
      background-color: rgba(0, 0, 0, 0.8);
      /* Semi-transparent background */

    }

    .shepherd-content {
      color: #075E54;
      /* White text color */
    }

    .shepherd-buttons {
      margin-top: 20px;
      /* Add spacing between buttons */
    }

    .shepherd-button {
      background-color: #075E54;
      /* Blue button color */
      color: #fff;
      /* White text color */
      border-radius: 5px;
      /* Rounded corners */
      padding: 4px 8px;
      /* Padding for buttons */
      margin-right: 10px;
      /* Add spacing between buttons */
    }

    .shepherd-button:hover {
      background-color: #075E54;
      /* Darker blue on hover */
    }
  </style>

  <style>
    .search-form {
      width: 180% !important;
      /* Ensure the form takes full width */
    }

    .search-form .search-input {
      width: 9000px;
      /* Set a fixed width for the input field */
      padding: 9px 20px !important;
      /* Increase padding for comfort */
      border-radius: 5px !important;
      /* Optional: rounded corners */
      border: 1px solid #ccc;
      /* Optional: border color */
    }

    .search-form .form-control {
      border-radius: 5px;
      /* Optional: rounded corners for select */
    }

    .search-form .form-control.me-2 {
      width: 150px;
      /* Set a fixed width for the select dropdown */
    }

    .search-form .btn {
      padding: 10px 15px;
      /* Increase button size */
      border-radius: 5px;
      /* Optional: rounded corners for button */
    }
  </style>

  <style>
    .table-responsive {
      width: 100%;
      overflow-x: auto;
    }

    .status-draft {
      /* border-top: 10px solid #fc03a1; */
      border-left: 5px solid #fc03a1;
    }

    .status-review {
      /* border-top: 10px solid #0a1157; */
      border-left: 5px solid #0a1157;
    }

    .status-public {
      /* border-top: 10px solid green; */
      border-left: 5px solid green;
    }

    .status-archived {
      /* border-top: 10px solid #1cc9be; */
      border-left: 5px solid #1cc9be;
    }

    .status-key {
      display: flex;
      gap: 10px;
      margin-bottom: 10px;
    }

    .status-key span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .status-key .key-draft {
      width: 20px;
      height: 10px;
      background-color: #fc03a1;
    }

    .status-key .key-review {
      width: 20px;
      height: 10px;
      background-color: #0a1157;
    }

    .status-key .key-public {
      width: 20px;
      height: 10px;
      background-color: green;
    }

    .status-key .key-archived {
      width: 20px;
      height: 10px;
      background-color: #1cc9be;
    }

    .drag-active {
      border: 2px dashed #28a745;
      /* Change border color */
      background-color: #f0f0f0;
      /* Light background */
      /* Add more styles as needed */
    }
  </style>

  <!-- Add this in your custom CSS for additional styling -->
  <style>
    .progress-bar {
      transition: width 0.6s ease;
      font-weight: bold;
    }

    .progress-bar.bg-success {
      background-color: #28a745 !important;
    }
  </style>



</head>

<body class="body" {{ session('user.preferences.dark_mode') === 'true' ? 'data-bs-theme=dark' : 'data-bs-theme=light' }}>