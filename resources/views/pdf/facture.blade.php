<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        h3 {
            color: #333;
            margin: 10px 0;
        }

        .logo img {
            display: block;
            margin: 0 auto;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>{{ $content['created_at'] }}</h4>

        <div class="logo">

            <img src="./default_full_logo.png" width="200" alt="logo">
        </div>



        <div>
            <h3><b><?= get_label('Full Name', 'Full Name') ?> :</b> {{ $content['name'] }}</h3>
            <h3><b><?= get_label('Email Address', 'Email Address') ?> :</b> {{ $content['email'] }}</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th><?= get_label('Paid amount', 'Paid amount') ?></th>
                    <th><?= get_label('Total amount', 'Total amount') ?></th>
                    <th><?= get_label('Paid amount', 'Paid amount') ?></th>
                    <th><?= get_label('Total amount', 'Total amount') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                </tr>
                <tr>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                </tr>
                <tr>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                </tr>
                <tr>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                </tr>
                <tr>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                    <td>{{ $content['paid'] }}</td>
                    <td>{{ $content['tot'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td><b><?= get_label('Rest', 'Rest') ?> :</b> {{ $content['rest'] }}</td>
                </tr>
            </tbody>
        </table>

        <div>
            <h3><b><?= get_label('Type', 'Type') ?> :</b> {{ $content['type'] }}</h3>
        </div>
    </div>
</body>
</html>
