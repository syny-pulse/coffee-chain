<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Chain - Welcome</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --coffee-dark: #2D1B0E;
            --coffee-medium: #6F4E37;
            --coffee-light: #A0702A;
            --cream: #F5F1EB;
            --accent: #D4A574;
            --text-dark: #2D1B0E;
            --text-light: #666;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--cream) 0%, #FAFAF8 100%);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(245, 241, 235, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            z-index: 1000;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(111, 78, 55, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
            text-decoration: none;
        }

        .logo i {
            margin-right: 0.5rem;
            color: var(--coffee-medium);
            font-size: 1.8rem;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--coffee-medium);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--coffee-medium);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--coffee-medium);
            border: 2px solid var(--coffee-medium);
        }

        .btn-outline:hover {
            background: var(--coffee-medium);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(111, 78, 55, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding-top: 80px;
            background:
                radial-gradient(circle at 20% 80%, rgba(160, 112, 42, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(111, 78, 55, 0.1) 0%, transparent 50%);
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 1rem;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--coffee-dark) 0%, var(--coffee-medium) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-content p {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin: 3rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(111, 78, 55, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--coffee-medium);
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .coffee-illustration {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 20px 40px rgba(111, 78, 55, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        .coffee-illustration i {
            font-size: 8rem;
            color: white;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: floatAround 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 60px;
            height: 60px;
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 50px;
            height: 50px;
            top: 70%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 70px;
            height: 70px;
            bottom: 20%;
            left: 10%;
            animation-delay: 4s;
        }

        /* Section Styles */
        .section {
            padding: 6rem 0;
        }

        .section-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 1rem;
        }

        .section-header p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Features Section */
        .features {
            background: rgba(255, 255, 255, 0.5);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            border: 1px solid rgba(111, 78, 55, 0.1);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(111, 78, 55, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--coffee-dark);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: var(--text-light);
            line-height: 1.7;
        }

        /* About Section */
        .about {
            background: linear-gradient(135deg, rgba(245, 241, 235, 0.8) 0%, rgba(250, 250, 248, 0.8) 100%);
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-light);
        }

        .about-text h3 {
            font-size: 1.5rem;
            color: var(--coffee-dark);
            margin: 2rem 0 1rem;
        }

        .about-visual {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .about-visual::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.3)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.3)"/><circle cx="60" cy="70" r="2.5" fill="rgba(255,255,255,0.3)"/><circle cx="30" cy="80" r="1" fill="rgba(255,255,255,0.3)"/></svg>');
            animation: float 10s ease-in-out infinite;
        }

        .about-visual i {
            font-size: 6rem;
            color: white;
            opacity: 0.9;
        }

        .mission-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .mission-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            border: 1px solid rgba(111, 78, 55, 0.1);
            transition: transform 0.3s ease;
        }

        .mission-card:hover {
            transform: translateY(-5px);
        }

        .mission-card i {
            font-size: 2.5rem;
            color: var(--coffee-medium);
            margin-bottom: 1rem;
        }

        .mission-card h4 {
            font-size: 1.2rem;
            color: var(--coffee-dark);
            margin-bottom: 0.5rem;
        }

        .mission-card p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Contact Section */
        .contact {
            background: rgba(255, 255, 255, 0.6);
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }

        .contact-info h3 {
            font-size: 1.5rem;
            color: var(--coffee-dark);
            margin-bottom: 1rem;
        }

        .contact-info p {
            color: var(--text-light);
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            border: 1px solid rgba(111, 78, 55, 0.1);
            transition: transform 0.3s ease;
        }

        .contact-item:hover {
            transform: translateX(5px);
        }

        .contact-item i {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .contact-item-content h5 {
            color: var(--coffee-dark);
            margin-bottom: 0.25rem;
        }

        .contact-item-content p {
            color: var(--text-light);
            margin: 0;
            font-size: 0.9rem;
        }

        .contact-form {
            background: rgba(255, 255, 255, 0.9);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid rgba(111, 78, 55, 0.1);
            box-shadow: 0 10px 30px rgba(111, 78, 55, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--coffee-dark);
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(111, 78, 55, 0.2);
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--coffee-medium);
            box-shadow: 0 0 0 3px rgba(111, 78, 55, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(111, 78, 55, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Footer */
        .footer {
            background: var(--coffee-dark);
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            text-align: center;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--accent);
        }

        .footer-section p,
        .footer-section a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: var(--accent);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes floatAround {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            25% {
                transform: translateY(-10px) rotate(90deg);
            }

            50% {
                transform: translateY(-5px) rotate(180deg);
            }

            75% {
                transform: translateY(-15px) rotate(270deg);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero-container,
            .about-content,
            .contact-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 2rem;
            }

            .hero-content h1,
            .section-header h2 {
                font-size: 2.5rem;
            }

            .hero-stats,
            .mission-values {
                grid-template-columns: 1fr;
            }

            .coffee-illustration {
                width: 300px;
                height: 300px;
            }

            .coffee-illustration i {
                font-size: 6rem;
            }

            .auth-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .section {
                padding: 4rem 0;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <i class="fas fa-seedling"></i>
                Coffee Chain
            </a>

            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn btn-outline">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Register
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-container">
            <div class="hero-content">
                <h1>From Farm to Cup</h1>
                <p>Streamline your coffee supply chain with intelligent demand prediction, seamless communication, and
                    optimized inventory management across farmers, processors, and retailers.</p>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">80%</span>
                        <span class="stat-label">Prediction Accuracy</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50%</span>
                        <span class="stat-label">Faster Processing</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">25%</span>
                        <span class="stat-label">Cost Reduction</span>
                    </div>
                </div>

                <div class="auth-buttons">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-rocket"></i>
                        Get Started
                    </a>
                    <a href="#features" class="btn btn-outline">
                        <i class="fas fa-info-circle"></i>
                        Learn More
                    </a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="coffee-illustration">
                    <i class="fas fa-coffee"></i>
                </div>
                <div class="floating-elements">
                    <div class="floating-element">
                        <i class="fas fa-chart-line" style="color: var(--coffee-medium);"></i>
                    </div>
                    <div class="floating-element">
                        <i class="fas fa-users" style="color: var(--coffee-medium);"></i>
                    </div>
                    <div class="floating-element">
                        <i class="fas fa-cogs" style="color: var(--coffee-medium);"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features section" id="features">
        <div class="section-container">
            <div class="section-header">
                <h2>Intelligent Supply Chain Management</h2>
                <p>Powered by machine learning and designed for the complete coffee ecosystem</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>AI-Powered Predictions</h3>
                    <p>Advanced machine learning algorithms using ARIMA, LSTM, and Random Forest models to predict
                        demand with 80% accuracy and optimize your supply chain.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-link"></i>
                    </div>
                    <h3>Three-Tier Integration</h3>
                    <p>Seamlessly connect farmers, processors, and retailers in one unified platform with real-time data
                        flow and automated coordination.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h3>Smart Inventory</h3>
                    <p>Dynamic inventory management with automated reorder points, safety stock calculations, and
                        real-time tracking across all supply chain tiers.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Real-Time Communication</h3>
                    <p>Built-in messaging system enabling seamless collaboration between farmers, processors, and
                        retailers with context-aware notifications.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3>Customer Segmentation</h3>
                    <p>RFM analysis to identify distinct customer groups and enable targeted marketing strategies that
                        improve satisfaction by 20%.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Automated Validation</h3>
                    <p>Java-powered vendor validation system with automated compliance checks, risk assessment, and
                        facility visit scheduling.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section" id="about">
        <div class="section-container">
            <div class="section-header">
                <h2>About Coffee Chain Management</h2>
                <p>Revolutionizing the coffee industry through intelligent supply chain optimization</p>
            </div>

            <div class="about-content">
                <div class="about-text">
                    <p>Coffee Chain Management System is a comprehensive platform designed to transform the traditional
                        coffee supply chain. Built by Group 26 (G-26), our system bridges the gap between coffee farmers
                        in Bukomansimbi, processors, and retailers like Café Javas, creating a seamless ecosystem that
                        benefits everyone involved.</p>

                    <h3>Our Story</h3>
                    <p>Founded on the principle that technology can solve real-world problems, we recognized the
                        inefficiencies plaguing the coffee industry. From unpredictable demand to communication barriers
                        between supply chain partners, these challenges were costing businesses millions and preventing
                        optimal resource utilization.</p>

                    <p>Our solution combines cutting-edge machine learning algorithms with intuitive user interfaces,
                        creating a platform that's both powerful and accessible to users at every level of technical
                        expertise.</p>
                </div>

                <div class="about-visual">
                    <i class="fas fa-globe-americas"></i>
                </div>
            </div>

            <div class="mission-values">
                <div class="mission-card">
                    <i class="fas fa-bullseye"></i>
                    <h4>Our Mission</h4>
                    <p>To create a transparent, efficient, and sustainable coffee supply chain that empowers farmers,
                        optimizes operations, and delivers quality to consumers.</p>
                </div>

                <div class="mission-card">
                    <i class="fas fa-eye"></i>
                    <h4>Our Vision</h4>
                    <p>To become the leading platform for agricultural supply chain management, setting the standard for
                        transparency and efficiency in the industry.</p>
                </div>

                <div class="mission-card">
                    <i class="fas fa-heart"></i>
                    <h4>Our Values</h4>
                    <p>Innovation, transparency, sustainability, and partnership drive everything we do, ensuring mutual
                        success for all stakeholders.</p>
                </div>

                <div class="mission-card">
                    <i class="fas fa-lightbulb"></i>
                    <h4>Innovation</h4>
                    <p>Leveraging AI and machine learning to solve complex supply chain challenges with elegant,
                        user-friendly solutions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact section" id="contact">
        <div class="section-container">
            <div class="section-header">
                <h2>Get in Touch</h2>
                <p>Ready to revolutionize your coffee supply chain? We'd love to hear from you</p>
            </div>

            <div class="contact-content">
                <div class="contact-info">
                    <h3>Let's Start a Conversation</h3>
                    <p>Whether you're a coffee farmer looking to optimize your harvest predictions, a processor seeking
                        better inventory management, or a retailer wanting to improve customer satisfaction, we're here
                        to help you succeed.</p>

                    <div class="contact-details">
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="contact-item-content">
                                <h5>Our Location</h5>
                                <p>Kampala, Uganda<br>East Africa</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div class="contact-item-content">
                                <h5>Phone Number</h5>
                                <p>+256 700 123 456<br>Available 24/7</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div class="contact-item-content">
                                <h5>Email Address</h5>
                                <p>info@coffeechain.ug<br>support@coffeechain.ug</p>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div class="contact-item-content">
                                <h5>Business Hours</h5>
                                <p>Mon - Fri: 8:00 AM - 6:00 PM<br>Sat: 9:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" name="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" name="lastName" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company">Company/Organization</label>
                            <input type="text" id="company" name="company">
                        </div>

                        <div class="form-group">
                            <label for="role">Your Role in Coffee Supply Chain</label>
                            <select id="role" name="role">
                                <option value="">Select your role</option>
                                <option value="farmer">Coffee Farmer</option>
                                <option value="processor">Coffee Processor</option>
                                <option value="retailer">Coffee Retailer</option>
                                <option value="distributor">Distributor</option>
                                <option value="investor">Investor</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message"
                                placeholder="Tell us about your coffee supply chain challenges and how we can help..." required></textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Coffee Chain Management</h4>
                    <p>Connecting the complete coffee ecosystem from Bukomansimbi farms to retail outlets like Café
                        Javas, powered by intelligent supply chain optimization.</p>
                </div>

                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <p><a href="#home">Home</a></p>
                    <p><a href="#features">Features</a></p>
                    <p><a href="#about">About</a></p>
                    <p><a href="#contact">Contact</a></p>
                </div>

                <div class="footer-section">
                    <h4>Support</h4>
                    <p><a href="#contact">Contact Us</a></p>
                    <p><a href="#help">Help Center</a></p>
                    <p><a href="#documentation">Documentation</a></p>
                    <p><a href="https://github.com/syny-pulse/coffee-chain.git">GitHub</a></p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Coffee Supply Chain Management System - G-26. Built with Laravel.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(245, 241, 235, 0.98)';
                navbar.style.boxShadow = '0 2px 20px rgba(111, 78, 55, 0.1)';
            } else {
                navbar.style.background = 'rgba(245, 241, 235, 0.95)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Add hover animations to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animated counter for stats
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (element.textContent.includes('%') ? '%' : '');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (element.textContent.includes('%') ? '%' : '');
                }
            }, 20);
        }

        // Trigger counter animation when stats come into view
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const target = parseInt(stat.textContent);
                        animateCounter(stat, target);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        const heroStats = document.querySelector('.hero-stats');
        if (heroStats) {
            statsObserver.observe(heroStats);
        }

        // Contact form handling
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Show success message (in a real app, you'd send this to a server)
            const submitBtn = this.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
            submitBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';

            // Reset form
            setTimeout(() => {
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.style.background =
                    'linear-gradient(135deg, var(--coffee-medium) 0%, var(--coffee-light) 100%)';
            }, 3000);
        });

        // Form validation
        const inputs = document.querySelectorAll('input[required], textarea[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = 'rgba(111, 78, 55, 0.2)';
                }
            });
        });

        // Email validation
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(this.value) && this.value !== '') {
                this.style.borderColor = '#dc3545';
            } else {
                this.style.borderColor = 'rgba(111, 78, 55, 0.2)';
            }
        });

        // Add fade-in animation for sections
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Apply fade-in animation to sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            fadeObserver.observe(section);
        });
    </script>
</body>

</html>
