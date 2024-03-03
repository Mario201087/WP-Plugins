<?php
function calorie_calculator_shortcode() {
    ob_start();
    ?>
     <style>
        .calculator label {
            display: block;
            margin-bottom: 5px;
            color: black;
        }

        .calculator input,
        .calculator select,
        .calculator button {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .calculator button {
            padding: 10px;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .calculator h3,
        .calculator h2 {
            margin-top: 20px;
            color: black;
        }

        .calculator h3 span {
            display: inline; /* Add this style for inline display */
        }
    </style>
    <div id="calorie-calculator" class="calculator">
        <div class="calculator-container">
            <h2>Calorie Calculator</h2>
            <label for="age">Age (years):</label>
            <input type="number" id="age" placeholder="Enter age" required>

            <label for="gender">Gender:</label>
            <select id="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" placeholder="Enter weight" required>

            <label for="height">Height (cm):</label>
            <input type="number" id="height" placeholder="Enter height" required>

            <label for="activity">Activity Level:</label>
            <select id="activity">
                <option value="sedentary">Sedentary (little or no exercise)</option>
                <option value="lightly_active">Lightly active (light exercise/sports 1-3 days/week)</option>
                <option value="moderately_active">Moderately active (moderate exercise/sports 3-5 days/week)</option>
                <option value="very_active">Very active (hard exercise/sports 6-7 days a week)</option>
                <option value="extra_active">Extra active (very hard exercise/sports & physical job or 2x training)</option>
            </select>

            <button onclick="calculateCalories()">Calculate Calories</button>

            <h3>Your Daily Calories: <span id="calories-result"></span></h3>
        </div>
    </div>

    <script>
        function calculateCalories() {
            var age = document.getElementById("age").value;
            var gender = document.getElementById("gender").value;
            var weight = document.getElementById("weight").value;
            var height = document.getElementById("height").value;
            var activity = document.getElementById("activity").value;

            if (age > 0 && weight > 0 && height > 0) {
                // Formula for calculating daily calories (Harris-Benedict Equation)
                var bmr = (gender === "male") ? 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age) : 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
                var calories = calculateActivityMultiplier(activity) * bmr;

                document.getElementById("calories-result").innerHTML = calories.toFixed(2);
            } else {
                document.getElementById("calories-result").innerHTML = "Please enter valid age, weight, and height.";
            }
        }

        function calculateActivityMultiplier(activity) {
            switch (activity) {
                case "sedentary":
                    return 1.2;
                case "lightly_active":
                    return 1.375;
                case "moderately_active":
                    return 1.55;
                case "very_active":
                    return 1.725;
                case "extra_active":
                    return 1.9;
                default:
                    return 1.2;
            }
        }
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('calorie_calculator', 'calorie_calculator_shortcode');
?>