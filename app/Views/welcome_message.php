<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk</title>

    <style>
        /* Basic Form Styling */
        form {
            margin: 20px auto;
            padding: 20px;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            font-size: 14px;
        }

        p {
            text-align: center;
            font-size: 16px;
            margin-top: 10px;
        }

        .error {
            color: red;
            font-size: 14px;
        }

        .success {
            color: green;
            font-size: 14px;
        }

        /* Grid container for 25 boxes */
        .locker-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin: 20px auto;
            max-width: 500px;

        }

        /* Individual lockers (boxes) */
        .locker {
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .locker.free {
            background-color: green;
        }

        .locker.occupied {
            background-color: blue;
        }
    </style>
</head>

<body>
    <form method="post" action="issueLocker">
        <?= csrf_field() ?>
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" id="user_id" required>
        <button type="submit">Issue Locker</button>
    </form>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" action="releaseLocker">
        <?= csrf_field() ?>
        <label for="user_id">User ID:</label>
        <input type="text" name="user_id" id="user_id" required>
        <button type="submit">Release Locker</button>
    </form>

    <div class="locker-container">
        <?php if (isset($lockers) && !empty($lockers)): ?>
            <?php for ($i = 501; $i <= 525; $i++): ?>
                <?php
                // Find the locker with the current locker_no
                $locker = array_filter($lockers, fn($l) => $l['locker_no'] == $i);
                $locker = $locker ? array_values($locker)[0] : ['locker_no' => $i, 'status' => 'free'];
                $statusClass = $locker['status'] === 'occupied' ? 'occupied' : 'free';
                ?>
                <div class="locker <?= $statusClass ?>"><?= $locker['locker_no'] ?><br>
                    <?= $locker['user_id'] ?></div>
            <?php endfor; ?>
        <?php else: ?>
            <p>No lockers found.</p>
        <?php endif; ?>
    </div>

</body>


</html>