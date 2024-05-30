<?php

/*******w******** 
    
    Name: Jiale Cao 
    Date:  May 26
    Description: Asessment 2

 ****************/


 function validate_postal_code($postal_code) {
    return preg_match('/^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/', $postal_code);
}

$errors = [];

// Validate email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    $errors[] = "Invalid email address.";
}

// Validate postal code
$postal = filter_input(INPUT_POST, 'postal');
if ($postal && !validate_postal_code($postal)) {
    $errors[] = "Invalid postal code.";
}

// Validate credit card number
$cardnumber = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_INT);
if (!$cardnumber || strlen($cardnumber) != 10) {
    $errors[] = "Invalid credit card number.";
}

// Validate credit card month
$month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1, "max_range" => 12]
]);
if (!$month) {
    $errors[] = "Invalid expiry month.";
}

// Validate credit card year
$current_year = date("Y");
$year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT, [
    "options" => ["min_range" => $current_year, "max_range" => $current_year + 5]
]);
if (!$year) {
    $errors[] = "Invalid expiry year.";
}

// Validate card type
$cardtype = isset($_POST['cardtype']);
if (!$cardtype) {
    $errors[] = "You must choose a card type.";
}

// Validate other fields
$fullname = filter_input(INPUT_POST, 'fullname');
$address = filter_input(INPUT_POST, 'address');
$city = filter_input(INPUT_POST, 'city');
$province = filter_input(INPUT_POST, 'province');
$cardname = filter_input(INPUT_POST, 'cardname');

if (!$fullname) {
    $errors[] = "Full name is required.";
}
if (!$address) {
    $errors[] = "Address is required.";
}
if (!$city) {
    $errors[] = "City is required.";
}
if (!$province || !in_array($province, ['AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'ON', 'PE', 'QC', 'SK', 'NT', 'NU', 'YT'])) {
    $errors[] = "Invalid province.";
}
if (!$cardname) {
    $errors[] = "Name on card is required.";
}

// Validate product quantities
$quantities = [
    'qty1' => filter_input(INPUT_POST, 'qty1', FILTER_VALIDATE_INT),
    'qty2' => filter_input(INPUT_POST, 'qty2', FILTER_VALIDATE_INT),
    'qty3' => filter_input(INPUT_POST, 'qty3', FILTER_VALIDATE_INT),
    'qty4' => filter_input(INPUT_POST, 'qty4', FILTER_VALIDATE_INT),
    'qty5' => filter_input(INPUT_POST, 'qty5', FILTER_VALIDATE_INT),
];

$product_descriptions = [
    'qty1' => 'MacBook',
    'qty2' => 'Razer Mouse',
    'qty3' => 'WD HDD',
    'qty4' => 'Google Nexus 7',
    'qty5' => 'DD-45 Drums',
];

$product_prices = [
    'qty1' => 1899.99,
    'qty2' => 79.99,
    'qty3' => 179.99,
    'qty4' => 249.99,
    'qty5' => 119.99,
];

$total_cost = 0;
$order_items = [];

// Calculate total cost and prepare order items
foreach ($quantities as $key => $quantity) {
    if ($quantity > 0) {
        $order_items[] = [
            'quantity' => $quantity,
            'description' => $product_descriptions[$key],
            'cost' => $quantity * $product_prices[$key],
        ];
        $total_cost += $quantity * $product_prices[$key];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Thanks for your order!</title>
    <style>
        @import url(http://fonts.googleapis.com/css?family=Carrois+Gothic);

        body {
            margin: 10px auto;
            width: 700px;
            font-family: 'Carrois Gothic';
            border-radius: 10px;
        }

        h1,
        h2 {
            padding: 2px;
        }

        h1 {
            font-size: 22px;

        }

        table {
            font-size: 14px;
            border: 2px solid #000;
            width: 580px;
            margin: 0px auto 1em auto;
            border-radius: 10px;
        }

        td {
            border: 1px solid #000;
            padding: 2px;
            margin: 3px;
        }

        #rollingrick {
            margin: 10px auto;
            width: 650px;
        }

        .alignright {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .invoice {
            border: #000 solid 2px;
            padding: 5px;
            width: 660px;
            margin: 0px auto 0px;
            color: #000;
            border-radius: 10px;
            padding-bottom: 25px;
        }
    </style>
</head>

<body>
    <div class="invoice">
        <?php if (!empty($errors)) : ?>
            <h2>Some errors in your submission:</h2>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <h2>Thanks for your order, <?= $fullname ?>.</h2>
            <h3>Here's a summary of your order:</h3>
            <table>
                <tr>
                    <td colspan="4">
                        <h3>Address Information</h3>
                    </td>
                </tr>
                <tr>
                    <td class="alignright"><span class="bold">Address:</span></td>
                    <td><?= $address ?></td>
                    <td class="alignright"><span class="bold">City:</span></td>
                    <td><?= $city ?></td>
                </tr>
                <tr>
                    <td class="alignright"><span class="bold">Province:</span></td>
                    <td><?= $province ?></td>
                    <td class="alignright"><span class="bold">Postal Code:</span></td>
                    <td><?= $postal ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="alignright"><span class="bold">Email:</span></td>
                    <td colspan="2"><?= $email ?></td>
                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="3">
                        <h3>Order Information</h3>
                    </td>
                </tr>
                <tr>
                    <td><span class="bold">Quantity</span></td>
                    <td><span class="bold">Description</span></td>
                    <td><span class="bold">Cost</span></td>
                </tr>
                <?php foreach ($order_items as $item) : ?>
                    <tr>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td class="alignright"><?= number_format($item['cost'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="2" class="alignright"><span class="bold">Totals</span></td>
                    <td class="alignright"><span class="bold">$<?= number_format($total_cost, 2) ?></span></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>