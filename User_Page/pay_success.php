<?php
// Sample order data - replace with your actual order data
$orderData = [
    'order_number' => 'ORD-' . rand(100000, 999999),
    'customer_name' => 'John Doe',
    'customer_email' => 'john.doe@email.com',
    'order_total' => 149.99,
    'shipping_address' => 'phonm penh, cambodia',
    'estimated_delivery' => date('M j, Y', strtotime('+3 days')),
    'items' => [
        ['name' => 'Wireless Headphones', 'price' => 79.99, 'quantity' => 1],
        ['name' => 'Phone Case', 'price' => 29.99, 'quantity' => 2],
        ['name' => 'USB Cable', 'price' => 9.99, 'quantity' => 1]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Thank You!</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-20px); }
            60% { transform: translateY(-10px); }
        }

        .success-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .success-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }

        .success-body {
            padding: 40px;
        }

        .order-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border-left: 5px solid #4CAF50;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .info-content h4 {
            color: #333;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-content p {
            color: #666;
            font-size: 14px;
        }

        .order-summary {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .summary-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .order-number {
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .order-items {
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info h5 {
            color: #333;
            margin-bottom: 5px;
        }

        .item-info .quantity {
            color: #666;
            font-size: 14px;
        }

        .item-price {
            font-weight: 600;
            color: #333;
        }

        .order-total {
            text-align: right;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .total-label {
            color: #666;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 28px;
            font-weight: 700;
            color: #4CAF50;
        }

        .next-steps {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .next-steps h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .next-steps p {
            font-size: 16px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: white;
            color: #333;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #333;
        }

        .social-share {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .social-share h4 {
            margin-bottom: 15px;
            color: #333;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .social-icon.facebook { background: #3b5998; }
        .social-icon.twitter { background: #1da1f2; }
        .social-icon.instagram { background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); }
        .social-icon.whatsapp { background: #25d366; }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #4CAF50;
            position: fixed;
            animation: confetti-fall 3s linear infinite;
        }

        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .success-header {
                padding: 30px 20px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            .success-body {
                padding: 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Breadcrumbs / Stepper -->
<div class="flex items-center gap-2 pb-8">
<span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">1</span>
<span class="text-primary text-sm font-bold leading-normal">Shipping</span>
<div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
<span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">2</span>
<span class="text-accent text-sm font-medium leading-normal">Payment</span>
<div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
<span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">3</span>
<span class="text-accent text-sm font-medium leading-normal">success Payment</span>
</div>
    <!-- Confetti animation -->
    <div class="confetti" style="left: 10%; animation-delay: 0s;"></div>
    <div class="confetti" style="left: 20%; animation-delay: 0.5s; background: #ff6b6b;"></div>
    <div class="confetti" style="left: 30%; animation-delay: 1s; background: #4ecdc4;"></div>
    <div class="confetti" style="left: 40%; animation-delay: 1.5s; background: #45b7d1;"></div>
    <div class="confetti" style="left: 50%; animation-delay: 2s; background: #f9ca24;"></div>
    <div class="confetti" style="left: 60%; animation-delay: 0.3s; background: #6c5ce7;"></div>
    <div class="confetti" style="left: 70%; animation-delay: 0.8s; background: #a29bfe;"></div>
    <div class="confetti" style="left: 80%; animation-delay: 1.3s; background: #fd79a8;"></div>
    <div class="confetti" style="left: 90%; animation-delay: 1.8s; background: #fdcb6e;"></div>

    <div class="success-container">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Thank You, <?php echo htmlspecialchars($orderData['customer_name']); ?>!</h1>
            <p class="success-subtitle">Your order has been confirmed and is being processed</p>
        </div>

        <div class="success-body">
            <div class="order-info">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Order Number</h4>
                            <p><?php echo htmlspecialchars($orderData['order_number']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h4>Confirmation Email</h4>
                            <p>Sent to <?php echo htmlspecialchars($orderData['customer_email']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="info-content">
                            <h4>Estimated Delivery</h4>
                            <p><?php echo htmlspecialchars($orderData['estimated_delivery']); ?></p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Shipping Address</h4>
                            <p><?php echo htmlspecialchars($orderData['shipping_address']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="order-summary">
                <div class="summary-header">
                    <h3 class="summary-title">Order Summary</h3>
                    <span class="order-number"><?php echo htmlspecialchars($orderData['order_number']); ?></span>
                </div>
                
                <div class="order-items">
                    <?php foreach ($orderData['items'] as $item): ?>
                    <div class="order-item">
                        <div class="item-info">
                            <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="quantity">Quantity: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="item-price">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <p class="total-label">Total Amount</p>
                    <p class="total-amount">$<?php echo number_format($orderData['order_total'], 2); ?></p>
                </div>
            </div>

            <div class="next-steps">
                <h3><i class="fas fa-rocket"></i> What's Next?</h3>
                <p>We'll send you tracking information once your order ships. You can track your package in real-time!</p>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-box"></i> Track Order
                    </a>
                    <a href="#" class="btn btn-secondary">
                        <i class="fas fa-shopping-bag"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <div class="social-share">
                <h4>Share your purchase with friends!</h4>
                <div class="social-icons">
                    <div class="social-icon facebook">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="social-icon twitter">
                        <i class="fab fa-twitter"></i>
                    </div>
                    <div class="social-icon instagram">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <div class="social-icon whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe elements
            document.querySelectorAll('.order-info, .order-summary, .next-steps, .social-share').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                observer.observe(el);
            });

            // Social share functionality
            document.querySelectorAll('.social-icon').forEach(icon => {
                icon.addEventListener('click', function() {
                    const platform = this.classList[1];
                    const text = `Just placed an order worth $<?php echo number_format($orderData['order_total'], 2); ?>! 🎉`;
                    const url = window.location.href;
                    
                    let shareUrl = '';
                    switch(platform) {
                        case 'facebook':
                            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                            break;
                        case 'twitter':
                            shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${url}`;
                            break;
                        case 'whatsapp':
                            shareUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
                            break;
                        case 'instagram':
                            alert('Instagram sharing coming soon! 📸');
                            return;
                    }
                    
                    if (shareUrl) {
                        window.open(shareUrl, '_blank', 'width=600,height=400');
                    }
                });
            });

            // Track order button
            document.querySelector('.btn-primary').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Order tracking feature coming soon! 📦');
            });

            // Continue shopping button
            document.querySelector('.btn-secondary').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Redirecting to shop... 🛍️');
                // window.location.href = '/shop';
            });
        });
    </script>
</body>
</html>