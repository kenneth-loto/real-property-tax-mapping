<?php
session_start(); // Start the session
include_once '../session.php';
Session::init();
include '../function.php';
$function = new Functions();

// Ensure the user is logged in by calling the checkSession method
$function->checkSession(); // Call the session check method
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Real Property Tax Mapping with Tax Collection System</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/external.css">
  <link rel="stylesheet" href="../assets/css/header.css">

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
    }
</style>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex justify-content-center align-items-center mt-3">
          <a href="index.php" class="text-nowrap logo-img">
            <img src="../assets/images/logos/logoo.png" width="150" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <div class="text-center mt-2"> <!-- Centering div -->
            <h3 class="fw-semibold">STAFF</h3>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu text-light">Home</span>
            </li>

            <li class="sidebar-item">
                <a class="btn sidebar-link" href="index.php" aria-expanded="false">
                    <span>
                        <i class="fa-solid fa-tachometer-alt"></i>
                    </span>
                    <span class="hide-menu fw-bold">Dashboard</span>
                </a>
            </li>

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu text-light">Transactions</span>
            </li>

            <li class="sidebar-item">
              <a class="btn sidebar-link" href="requests.php" aria-expanded="false">
                <span>
                  <i class="fas fa-bullhorn"></i>
                </span>
                <span class="hide-menu fw-bold">Review Requests</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="btn sidebar-link" href="rp_records.php" aria-expanded="false">
                <span>
                  <i class="fa-solid fa-folder-open"></i>
                </span>
                <span class="hide-menu fw-bold">RP Records</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="btn sidebar-link" href="history.php" aria-expanded="false">
                <span>
                  <i class="fa-solid fa-thumbs-up"></i>
                </span>
                <span class="hide-menu fw-bold">Approved Requests</span>
              </a>
            </li>

            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu text-light">Logout</span>
            </li>

            <li class="sidebar-item" style="margin-top: 10px">
              <a class="btn sidebar-link" href="../logout.php" aria-expanded="false">
                <span>
                  <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </span>
                <span class="hide-menu fw-bold">Logout</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">             
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-settings fs-6"></i>
                      <p class="mb-0 fs-3">Settings</p>
                    </a>
                    <a href="../logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->

<div class="container-fluid">
  