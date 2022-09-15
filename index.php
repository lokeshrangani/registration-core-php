<?php include('server.php') ?>
<!DOCTYPE html>
<html>

<head>
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="header">
        <h2>Welcome</h2>
    </div>

    <!-- notification message -->
    <form method="post" action="index.php" enctype="multipart/form-data" autocomplete="off">
        <?php include('success.php'); ?>
        <?php include('errors.php'); ?>
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="input-group">
            <label>Birth Date</label>
            <input type="date" name="birth_date" max='<?php echo date('Y-m-d'); ?>' value="<?php echo $birth_date; ?>" required>
        </div>
        <div class="input-group">
            <label>Address</label>
            <input type="text" name="address" value="<?php echo $address; ?>" required>
        </div>
        <div class="input-group">
            <label>Profile Picture</label>
            <input type="file" name="profile_pic" required>
        </div>
        <div class="input-group">
            <label>Credit Card Number</label>
            <input type="text" name="cc_number" value="<?php echo $cc_number; ?>" required>
        </div>
        <div>
            <label>Credit Card Expiry</label>
            <select name="cc_expiry_month" required>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <input style="display: inline;" type="number" min="2022" max="2030" name="cc_expiry_year" step="1" value="2023" required />
        </div>
        <div class="input-group">
            <label>Credit Card CVC</label>
            <input type="number" min="000" max="999" name="cc_CVC" step="1" value="<?php echo $cc_CVC; ?>" required />
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
    </form>
</body>

</html>