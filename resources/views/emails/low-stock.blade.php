<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Low Stock Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #721c24;
            margin: 0;
        }
        .product-info {
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
        }
        .product-info p {
            margin: 8px 0;
        }
        .product-name {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .stock-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 10px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>⚠️ Low Stock Alert</h2>
    </div>

    <p>Hello Admin,</p>

    <p>This is an automated notification to inform you that the following product is running low on stock:</p>

    <div class="product-info">
        <p class="product-name">{{ $product->name }}</p>
        <p><strong>Current Stock:</strong> {{ $product->stock_quantity }} units</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
    </div>

    <div class="stock-warning">
        <strong>Action Required:</strong> Please consider restocking this item soon to avoid running out of inventory.
    </div>

    <p style="margin-top: 30px;">
        Best regards,<br>
        {{ config('app.name') }} Inventory System
    </p>
</body>
</html>
