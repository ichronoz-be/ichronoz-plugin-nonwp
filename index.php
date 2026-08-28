<?php
$pageTitle = "iChronoz Demo Hotel";
$currentYear = date('Y');

$rooms = [
    [
        'name' => 'Deluxe Room',
        'price' => 850000,
        'image' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1000&q=80',
        'description' => 'A comfortable room with modern interiors and a beautiful city view.'
    ],
    [
        'name' => 'Ocean Suite',
        'price' => 1450000,
        'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=80',
        'description' => 'Spacious suite with premium amenities and relaxing ocean views.'
    ],
    [
        'name' => 'Family Room',
        'price' => 1100000,
        'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1000&q=80',
        'description' => 'Perfect for families looking for extra space, comfort, and convenience.'
    ]
];

function rupiah($amount)
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, sans-serif;
            color: #212529;
        }

        .navbar {
            background: rgba(15, 23, 42, 0.95);
        }

        .hero {
            min-height: 92vh;
            display: flex;
            align-items: center;
            position: relative;
            background:
                linear-gradient(
                    rgba(0, 0, 0, 0.48),
                    rgba(0, 0, 0, 0.48)
                ),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=85')
                center center / cover no-repeat;
            color: #fff;
        }

        .hero h1 {
            font-size: clamp(2.8rem, 6vw, 5rem);
            font-weight: 700;
            line-height: 1.05;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 650px;
        }

        .booking-box {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            margin-top: -70px;
            position: relative;
            z-index: 10;
        }

        .section-title {
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        .room-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-6px);
        }

        .room-card img {
            height: 250px;
            object-fit: cover;
        }

        .amenity-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            font-size: 28px;
            color: #0d6efd;
            margin-bottom: 16px;
        }

        .about-image {
            min-height: 480px;
            object-fit: cover;
            width: 100%;
            border-radius: 20px;
        }

        .testimonial-card {
            border: 0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        .cta-section {
            background:
                linear-gradient(
                    rgba(3, 37, 65, 0.82),
                    rgba(3, 37, 65, 0.82)
                ),
                url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1600&q=80')
                center / cover;
            color: white;
        }

        footer {
            background: #0f172a;
        }

        @media (max-width: 991px) {
            .hero {
                min-height: 80vh;
                padding-top: 100px;
                padding-bottom: 100px;
            }

            .booking-box {
                margin-top: -40px;
            }
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3">
    <div class="container">

        <a class="navbar-brand fw-bold fs-4" href="#">
            <?= $pageTitle ?>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#rooms">Rooms</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#amenities">Amenities</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="btn btn-warning px-4" href="#booking">
                        Book Now
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>


<!-- Hero -->
<section id="home" class="hero">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <span class="text-uppercase fw-semibold">
                    Luxury. Comfort. Escape.
                </span>

                <h1 class="mt-3">
                    Your perfect stay starts here.
                </h1>

                <p class="mt-4 mb-4">
                    Experience premium hospitality, beautiful rooms,
                    unforgettable views, and exceptional service at
                    iChronoz Demo Hotel.
                </p>

                <a href="#rooms" class="btn btn-warning btn-lg px-4 me-2">
                    Explore Rooms
                </a>

                <a href="#about" class="btn btn-outline-light btn-lg px-4">
                    Discover More
                </a>

            </div>
        </div>

    </div>
</section>


<!-- Booking Form -->
<section id="booking">
    <div class="container">

        <div class="booking-box p-4 p-lg-5">

            <!-- iChronoz Content -->
                <?php include __DIR__ . '/ichronoz/search-form.php'; ?>
            <!-- End iChronoz Content -->

        </div>

    </div>
</section>


<!-- Rooms -->
<section id="rooms" class="py-5 mt-5">
    <div class="container py-4">

        <div class="section-title text-center">
            <span class="text-primary fw-semibold text-uppercase">
                Our Rooms
            </span>

            <h2 class="display-5 fw-bold mt-2">
                Stay in comfort
            </h2>

            <p class="text-secondary">
                Choose from our carefully designed rooms and suites,
                created to make every stay relaxing and memorable.
            </p>
        </div>

        <div class="row g-4">

            <?php foreach ($rooms as $room): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card room-card h-100">

                        <img
                            src="<?= htmlspecialchars($room['image']) ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($room['name']) ?>"
                        >

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="fw-bold mb-0">
                                    <?= htmlspecialchars($room['name']) ?>
                                </h4>

                                <span class="badge bg-primary">
                                    Popular
                                </span>
                            </div>

                            <p class="text-secondary">
                                <?= htmlspecialchars($room['description']) ?>
                            </p>

                            <div class="d-flex gap-3 text-secondary mb-4">
                                <span>
                                    <i class="bi bi-people"></i>
                                    2 Guests
                                </span>

                                <span>
                                    <i class="bi bi-wifi"></i>
                                    WiFi
                                </span>

                                <span>
                                    <i class="bi bi-snow"></i>
                                    AC
                                </span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <span class="fw-bold fs-5 text-primary">
                                        <?= rupiah($room['price']) ?>
                                    </span>

                                    <small class="text-secondary">
                                        / night
                                    </small>
                                </div>

                                <a href="#" class="btn btn-outline-primary">
                                    View Room
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>


<!-- Amenities -->
<section id="amenities" class="py-5 bg-light">
    <div class="container py-4">

        <div class="section-title text-center">

            <span class="text-primary fw-semibold text-uppercase">
                Hotel Facilities
            </span>

            <h2 class="display-5 fw-bold mt-2">
                Everything you need
            </h2>

            <p class="text-secondary">
                Enjoy premium facilities designed for comfort,
                convenience, relaxation, and unforgettable experiences.
            </p>

        </div>

        <div class="row g-4 text-center">

            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <div class="amenity-icon">
                        <i class="bi bi-water"></i>
                    </div>

                    <h5 class="fw-bold">
                        Swimming Pool
                    </h5>

                    <p class="text-secondary">
                        Relax beside our beautiful outdoor swimming pool.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <div class="amenity-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>

                    <h5 class="fw-bold">
                        Restaurant
                    </h5>

                    <p class="text-secondary">
                        Enjoy delicious local and international cuisine.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <div class="amenity-icon">
                        <i class="bi bi-wifi"></i>
                    </div>

                    <h5 class="fw-bold">
                        Free WiFi
                    </h5>

                    <p class="text-secondary">
                        High-speed wireless internet throughout the hotel.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="p-4">
                    <div class="amenity-icon">
                        <i class="bi bi-car-front"></i>
                    </div>

                    <h5 class="fw-bold">
                        Free Parking
                    </h5>

                    <p class="text-secondary">
                        Complimentary parking for all hotel guests.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- About -->
<section id="about" class="py-5">
    <div class="container py-5">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <img
                    src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80"
                    alt="Hotel"
                    class="about-image"
                >

            </div>

            <div class="col-lg-6">

                <span class="text-primary fw-semibold text-uppercase">
                    About Our Hotel
                </span>

                <h2 class="display-5 fw-bold mt-2">
                    A memorable hotel experience.
                </h2>

                <p class="text-secondary fs-5 mt-4">
                    iChronoz Demo Hotel combines modern comfort with warm
                    hospitality. Our mission is to provide every guest
                    with a relaxing and memorable stay.
                </p>

                <p class="text-secondary">
                    Whether you're visiting for business, vacation,
                    or a weekend getaway, our team is ready to make
                    your stay exceptional.
                </p>

                <div class="row mt-4">

                    <div class="col-4">
                        <h3 class="fw-bold text-primary">
                            120+
                        </h3>

                        <span class="text-secondary">
                            Rooms
                        </span>
                    </div>

                    <div class="col-4">
                        <h3 class="fw-bold text-primary">
                            15+
                        </h3>

                        <span class="text-secondary">
                            Years
                        </span>
                    </div>

                    <div class="col-4">
                        <h3 class="fw-bold text-primary">
                            10K+
                        </h3>

                        <span class="text-secondary">
                            Guests
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>


<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container py-4">

        <div class="section-title text-center">

            <span class="text-primary fw-semibold text-uppercase">
                Guest Reviews
            </span>

            <h2 class="display-5 fw-bold mt-2">
                What our guests say
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card testimonial-card h-100">
                    <div class="card-body p-4">

                        <div class="text-warning mb-3">
                            ★★★★★
                        </div>

                        <p class="text-secondary">
                            “Beautiful hotel, comfortable room,
                            and the staff were extremely friendly.
                            We really enjoyed our stay.”
                        </p>

                        <strong>
                            Sarah Williams
                        </strong>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card testimonial-card h-100">
                    <div class="card-body p-4">

                        <div class="text-warning mb-3">
                            ★★★★★
                        </div>

                        <p class="text-secondary">
                            “Excellent location and amazing facilities.
                            The breakfast was great and the room was spotless.”
                        </p>

                        <strong>
                            Michael Tan
                        </strong>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card testimonial-card h-100">
                    <div class="card-body p-4">

                        <div class="text-warning mb-3">
                            ★★★★★
                        </div>

                        <p class="text-secondary">
                            “One of the best hotel experiences we've had.
                            Highly recommended for couples and families.”
                        </p>

                        <strong>
                            Andini Pratama
                        </strong>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- CTA -->
<section class="cta-section py-5">
    <div class="container py-5 text-center">

        <h2 class="display-5 fw-bold">
            Ready for your next getaway?
        </h2>

        <p class="fs-5 mt-3 mb-4">
            Book directly with us and enjoy a comfortable,
            memorable hotel experience.
        </p>

        <a
            href="#booking"
            class="btn btn-warning btn-lg px-5"
        >
            Book Your Stay
        </a>

    </div>
</section>


<!-- Footer -->
<footer id="contact" class="text-white pt-5 pb-4">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">

                <h3 class="fw-bold">
                    iChronoz Demo Hotel
                </h3>

                <p class="text-white-50 mt-3">
                    Premium hospitality, beautiful rooms,
                    exceptional service, and unforgettable stays.
                </p>

            </div>

            <div class="col-lg-3">

                <h5>
                    Quick Links
                </h5>

                <ul class="list-unstyled mt-3">

                    <li class="mb-2">
                        <a href="#home" class="text-white-50 text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="#rooms" class="text-white-50 text-decoration-none">
                            Rooms
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="#amenities" class="text-white-50 text-decoration-none">
                            Amenities
                        </a>
                    </li>

                    <li>
                        <a href="#about" class="text-white-50 text-decoration-none">
                            About
                        </a>
                    </li>

                </ul>

            </div>

            <div class="col-lg-4">

                <h5>
                    Contact
                </h5>

                <p class="text-white-50 mt-3 mb-2">
                    <i class="bi bi-geo-alt me-2"></i>
                    Bali, Indonesia
                </p>

                <p class="text-white-50 mb-2">
                    <i class="bi bi-telephone me-2"></i>
                    +62 812 3456 7890
                </p>

                <p class="text-white-50">
                    <i class="bi bi-envelope me-2"></i>
                    reservation@ichronoz.com
                </p>

            </div>

        </div>

        <hr class="border-secondary mt-5">

        <div class="text-center text-white-50">
            &copy; <?= $currentYear ?> iChronoz Demo Hotel.
            All rights reserved.
        </div>

    </div>
</footer>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
