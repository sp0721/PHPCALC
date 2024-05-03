<?php
$result = ""; // Initialize result variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the mathematical expression from the form input
    if (!empty($_POST['display'])) {
        $expression = $_POST['display'];

        // Remove any unwanted characters from the expression
        $expression = preg_replace('/[^0-9+\-*.\/\(\)\s]/', '', $expression);

        // Evaluate the expression and store the result
        if (!empty($expression)) {
            $result = evaluateExpression($expression);
        }
    }
}

// Function to evaluate the mathematical expression
function evaluateExpression($expression) {
    // Check for division by zero
    if (strpos($expression, '/0') !== false) {
        return 'Error: Division by zero';
    }

    // Attempt to evaluate the expression
    $result = @eval('return ' . $expression . ';');

    // Check for errors in evaluation
    if ($result === false) {
        return 'Error: Invalid expression';
    }

    // Return the result
    return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <link rel="stylesheet" type="text/css" href="calc.css">
</head>
<body>
    <div class="calculator-wrapper">
        <h1>Calculator</h1> <!-- Calculator title -->
        <div id="calculator">
            <form id="calcForm" method="POST" action="#">
                <input type="text" name="display" id="display" value="<?php echo $result; ?>" readonly>
                <div class="buttons">
                    <!-- Numerical buttons -->
                    <button type="button" onclick="addToDisplay('7')">7</button>
                    <button type="button" onclick="addToDisplay('8')">8</button>
                    <button type="button" onclick="addToDisplay('9')">9</button>
                    <button type="button" onclick="addToDisplay('+')">+</button>
                    <button type="button" onclick="addToDisplay('4')">4</button>
                    <button type="button" onclick="addToDisplay('5')">5</button>
                    <button type="button" onclick="addToDisplay('6')">6</button>
                    <button type="button" onclick="addToDisplay('-')">-</button>
                    <button type="button" onclick="addToDisplay('1')">1</button>
                    <button type="button" onclick="addToDisplay('2')">2</button>
                    <button type="button" onclick="addToDisplay('3')">3</button>
                    <button type="button" onclick="addToDisplay('*')">*</button>
                    <button type="button" onclick="addToDisplay('0')">0</button>
                    <button type="button" onclick="addToDisplay('.')">.</button>
                    <button type="button" onclick="clearDisplay()">C</button>
                    <button type="button" onclick="addToDisplay('/')">/</button>
                    <!-- Memory functions -->
                    <button type="button" onclick="storeMemory()">M+</button>
                    <button type="button" onclick="recallMemory()">MR</button>
                    <button type="button" onclick="clearMemory()">MC</button>
                    <!-- Equal button -->
                    <button type="button" onclick="calculate()">=</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addToDisplay(value) {
            var display = document.getElementById("display");
            var lastChar = display.value.slice(-1);

            if (value === '+' || value === '-' || value === '*' || value === '/') {
                if (lastChar === '+' || lastChar === '-' || lastChar === '*' || lastChar === '/') {
                    return;
                }
            }
            if (value === '.') {
                if (lastChar === '.') {
                    return;
                }
            }

            display.value += value;
        }

        function clearDisplay() {
            document.getElementById("display").value = '';
        }

        function calculate() {
            var display = document.getElementById("display");
            var expression = display.value;
            try {
                var result = eval(expression);
                display.value = result;
            } catch (error) {
                display.value = 'Error';
            }
        }

        // Memory functions
        var memory = 0;

        function storeMemory() {
            var display = document.getElementById("display");
            var currentValue = parseFloat(display.value);
            memory += currentValue;
        }

        function recallMemory() {
            var display = document.getElementById("display");
            display.value = memory;
        }

        function clearMemory() {
            memory = 0;
        }

        // Keyboard input support
        document.addEventListener('keydown', function(event) {
            var key = event.key;
            if ((key >= '0' && key <= '9') || key === '+' || key === '-' || key === '*' || key === '/' || key === '.') {
                addToDisplay(key);
            } else if (key === 'Enter') {
                calculate();
            }
        });
    </script>
</body>
</html>
