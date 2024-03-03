<?php
function bmi_calculator_shortcode() {
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

    <div id="bmi-calculator" class="calculator">
        <div class="calculator-container">
            <h2>BMI Calculator</h2>
            <label for="height">Height (cm):</label>
            <input type="number" id="height" placeholder="Enter height" required>
            <br>
            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" placeholder="Enter weight" required>
            <br>
            <button onclick="calculateBMI()">Calculate BMI</button>
            <br>
            <h3>Your BMI: <span id="result"></span></h3>
        </div>
    </div>

    <script>
        function calculateBMI() {
            var height = document.getElementById("height").value;
            var weight = document.getElementById("weight").value;

            if (height > 0 && weight > 0) {
                var bmi = (weight / ((height / 100) * (height / 100))).toFixed(2);
                document.getElementById("result").innerHTML = bmi;
            } else {
                document.getElementById("result").innerHTML = "Please enter valid height and weight.";
            }
        }
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('bmi_calculator', 'bmi_calculator_shortcode');
?>