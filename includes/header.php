<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Asset Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fc;
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
        }

        .nav-link {
            color: white !important;
            margin-right: 8px;
        }

        .nav-link:hover {
            color: #ffd43b !important;
        }

        .upload-btn {
            background: #ffd43b;
            color: #000 !important;
            border-radius: 8px;
            font-weight: 600;
        }

        .upload-btn:hover {
            background: #ffca2c;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .page-title {
            color: #343a40;
            font-weight: 700;
        }

        .file-icon {
            font-size: 2rem;
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container">

            <a class="navbar-brand" href="/dams/index.php">
                🗂️ DAMS
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/dams/index.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/dams/categories/index.php">
                            Categories
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/dams/assets/index.php">
                            Assets
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link upload-btn px-3 ms-2" href="/dams/assets/create.php">
                            + Upload Asset
                        </a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <div class="container mt-4">