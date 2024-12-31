<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Receipt</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .delivery-details {
            margin-bottom: 20px;
        }
        .customer-details {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container" id="delivery_receipt_section">
        <div class="header">
            <h1>Delivery Receipt</h1>
        </div>
        <div class="delivery-details">
            <h2>Delivery Details</h2>
            <!-- Add your delivery details here -->
            <p>Delivery Date: {{ $delivery->delivered_at }}</p>
            <p>Delivery Address: {{ $delivery->businessLocation->name }}</p>
        </div>
        <div class="customer-details">
            <h2>Customer Details</h2>
            <!-- Add your customer details here -->
            <p>Customer Name: {{ $customer->name }}</p>
        </div>
        <div class="delivery-products">
            <h2>Delivery Products</h2>
            <!-- Add your delivery products here -->
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($delivery->deliveryDetails as $delivery_detail)
                        <tr>
                            <td>{{ $delivery_detail->product->name }}</td>
                            <td>{{ $delivery_detail->quantity }}</td>
                            <td>{{ $delivery_detail->variation->sell_price_inc_tax }}</td>
                            <td>{{ $delivery_detail->quantity * $delivery_detail->variation->sell_price_inc_tax }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        <div class="footer">
            <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>