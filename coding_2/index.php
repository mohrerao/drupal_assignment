<!doctype html>
<html lang="en">
<head>
    
    <title><?php echo "Hack Number"?></title>
    
</head>
<body>
<div id="main-container">
    <form id="hn-form" method="post" action="WordProcessor.php">
       <!--  <label for="num-cases">
            <input type="number" min="1" max="100" value="1" required="required"
                   name="num-cases" class="num-cases" placeholder="Number of test cases" />
        </label> -->
        <div id="cases-container">
            <div class="case case-0">
                <input type="text" class="s1" name="s1" required="required" placeholder="S1" /><br>
                <input type="text" class="s2" name="s2" required="required" placeholder="S2" /><br>
                <input type="text" class="c" name="c" required="required" placeholder="C" /><br>
                <input type="text" class="i" name="i" required="required" placeholder="I" /><br>
            </div>
        </div>
        <input type="submit" value="Process →" />
    </form>
    <div id="results">
        <ol class="results-list">
            Submit test case to see results
        </ol>
    </div>
</div>
</body>
</html>