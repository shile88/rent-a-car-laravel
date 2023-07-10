<html>
    <head>
        <title>Car Rentals</title>
    </head>
    <body>
        <table>
            <tr>

                You have successfully rented a car:
                {{$car->type}} {{$car->model}} with price {{$car->price_per_day}}€ per day.
            </tr>
        </table>
    </body>
</html>
