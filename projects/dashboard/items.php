<?php
$items = [
    ["name" => "Pastel Wrist Strap", "price" => "$8.99", "desc" => "Soft silicone loop with pastel finish."],
    ["name" => "Pearl Phone Charm", "price" => "$12.50", "desc" => "Elegant pearl-style charm for daily outfits."],
    ["name" => "Minimal Black Strap", "price" => "$9.50", "desc" => "Clean, modern design with secure hook."],
    ["name" => "Color Pop Bead Charm", "price" => "$11.00", "desc" => "Bright colorful beads for a fun look."],
    ["name" => "Clear Acrylic Charm", "price" => "$10.25", "desc" => "Lightweight transparent charm with shine."],
    ["name" => "Braided Lanyard Strap", "price" => "$14.00", "desc" => "Durable braided strap for all-day carry."],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strapify Items</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #40a99b;
            color: #ffffff;
            min-height: 100vh;
            padding: 36px 22px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        h1 {
            font-size: 42px;
            margin-bottom: 18px;
        }

        p.subtitle {
            font-size: 20px;
            margin-bottom: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .item-card {
            background: rgba(53, 122, 107, 0.95);
            border-radius: 10px;
            padding: 18px;
        }

        .item-card h2 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .price {
            display: inline-block;
            margin-bottom: 10px;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.16);
            padding: 4px 10px;
            border-radius: 999px;
        }

        .back {
            display: inline-block;
            margin-top: 26px;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            background-color: #357a6b;
            transition: 0.3s;
        }

        .back:hover {
            background-color: #3b8a7a;
        }
    </style>
</head>
<body>
    <main class="container">
        <h1>Strapify Items</h1>
        <p class="subtitle">Choose your favorite phone straps and charms.</p>

        <section class="grid">
            <?php foreach ($items as $item): ?>
                <article class="item-card">
                    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                    <span class="price"><?php echo htmlspecialchars($item['price']); ?></span>
                    <p><?php echo htmlspecialchars($item['desc']); ?></p>
                </article>
            <?php endforeach; ?>
        </section>

        <a href="Dashboard1.php" class="back">Back to Dashboard</a>
    </main>
</body>
</html>
