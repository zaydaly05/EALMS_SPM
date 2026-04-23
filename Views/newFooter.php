<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance & Leave Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            padding: 50px 20px;
            text-align: center;
        }

        .main-content h1 {
            color: #000000;
            margin-bottom: 20px;
            font-size: 2.5em;
        }

        .main-content p {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .demo-button {
            background-color: #007bff;
            color: #ffffff;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }

        .demo-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        footer {
            background-color: #f8f9fa;
            color: #333333;
            padding: 40px 0 0 0;
            margin-top: auto;
            border-top: 3px solid #007bff;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #000000;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            display: inline-block;
        }

        .footer-section p {
            line-height: 1.8;
            margin-bottom: 10px;
            font-size: 14px;
            color: #555555;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #333333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer-section ul li a:hover {
            color: #007bff;
            transform: translateX(5px);
        }

        .footer-section ul li a i {
            margin-right: 8px;
            width: 20px;
            color: #007bff;
        }

        .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 14px;
            color: #555555;
        }

        .contact-info i {
            margin-right: 10px;
            font-size: 16px;
            width: 20px;
            color: #007bff;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .footer-bottom {
            background-color: #007bff;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #ffffff;
        }

        .footer-bottom p {
            margin: 5px 0;
        }

        .footer-bottom a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-bottom a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
            }
            
            .footer-section {
                text-align: center;
            }

            .footer-section h3 {
                display: block;
            }

            .social-links {
                justify-content: center;
            }

            .contact-info {
                justify-content: center;
            }

            .main-content h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Employee Attendance & Leave Management System</h1>
        <p>Streamline your workforce management with our comprehensive solution</p>
        <a href="#" class="demo-button">Mark Attendance</a>
        <a href="#" class="demo-button">Request Leave</a>
        <a href="#" class="demo-button">View Reports</a>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About System</h3>
                    <p>Our Employee Attendance and Leave Management System streamlines workforce management, making it easier to track attendance, manage leave requests, and generate comprehensive reports.</p>
                </div>

                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i>Home</a></li>
                        <li><a href="attendance.php"><i class="fas fa-calendar-check"></i>Attendance</a></li>
                        <li><a href="leave.php"><i class="fas fa-calendar-times"></i>Leave Management</a></li>
                        <li><a href="reports.php"><i class="fas fa-chart-bar"></i>Reports</a></li>
                        <li><a href="profile.php"><i class="fas fa-user"></i>My Profile</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>123 Business Street, Cairo, Egypt</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <span>+20 123 456 7890</span>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <span>info@attendancesystem.com</span>
                    </div>
                    <div class="social-links">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="help.php"><i class="fas fa-question-circle"></i>Help Center</a></li>
                        <li><a href="faq.php"><i class="fas fa-comments"></i>FAQ</a></li>
                        <li><a href="privacy.php"><i class="fas fa-shield-alt"></i>Privacy Policy</a></li>
                        <li><a href="terms.php"><i class="fas fa-file-contract"></i>Terms of Service</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope-open-text"></i>Contact Support</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Employee Attendance & Leave Management System. All Rights Reserved.</p>
            <p>Developed with <i class="fas fa-heart"></i> by Your Team</p>
        </div>
    </footer>
</body>
</html>