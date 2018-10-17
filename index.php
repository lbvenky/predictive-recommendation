

#/***********************************************************************
# * Predictive Recommendation
#
# * Author lbvenky
# ************************************************************************
<?php 
 require_once('config.php');
?>
<html>
  <head lang="en">
      <meta charset="UTF-8">
      <title>Dashboard</title>
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <script src="scripts/jquery-3.3.1.min.js"></script>
      <script src="scripts/canvasjs/canvasjs.min.js"></script>
      <script src="scripts/script.js"></script>
  </head>
  <body>
    <form action="index.php" method="post">
      <?php
        $disease  = $_POST['disease'];
        $location = $_POST['location'];
        $age      = $_POST['age'];
        $gender   = urlencode($_POST['gender']);

        //Calculate age for a range
        $incAge = $age +5;
        $ageRange = (!empty($age)) ? "[$age TO $incAge]" : '';
        $ageRange = urlencode($ageRange);
        $solrApi  = SOLR_API;

        $apiStr = "$solrApi?q=*:*&rows=200";
        if(!empty($ageRange)) {
            $apiStr .= "&fq=Age:$ageRange";
        }
        if(!empty($gender)) {
            $apiStr .= "&fq=Sex:$gender";
        }
        if(!empty($disease)) {
            $apiStr .= "&fq=Disease:$disease";
        }
        if(!empty($location)) {
            $apiStr = "$solrApi?q=*:*&fq=Postcode:$location";
        }

        if (empty($ageRange) && empty($gender) && empty($disease) && empty($location)) {
          $apiStr = "$solrApi?q=";
        }

        $solrJson = file_get_contents($apiStr);

        $medicationArr = array();
        foreach (json_decode($solrJson)->response as $key => $val) {
          foreach($val as $dbkey => $dbVal) {
            $medicationArr[$dbkey]['Medication'] = $dbVal->Medication[0];
            $medicationArr[$dbkey]['Quantity'] = $dbVal->Quantity[0];
            $medicationArr[$dbkey]['Episodicity'] = $dbVal->Episodicity[0];
            $medicationArr[$dbkey]['Disease'] = $dbVal->Disease[0];
            $medicationArr[$dbkey]['Postcode'] = $dbVal->Postcode[0];
          }
        }

        foreach ($medicationArr as $key => $value) {
            $getDisease[] = $value['Disease'];
            $getMedication[] = $value['Medication'];
        }

        $fillGraphMedication = array();
        foreach (array_count_values($getMedication) as $key => $val) {
          $fillGraphMedication[] = array('label' => $key, 'y' => $val);
        }

        $fillGraphDisease = array();
        foreach (array_count_values($getDisease) as $key => $val) {
          $fillGraphDisease[] = array('label' => $key, 'y' => $val);
        }

       ?>
      <div class="container">
        <div class="header">Care Plan Recommendations
          <br/><span class="notes">Recommended Treatment Plans and Medications based on Predictive analysis within exisiting Patient care records</span>
        </div>
      	<ul class="tabs">
      		<li class="tab-link current" data-tab="tab-1">Medications</li>
          <li class="tab-link" data-tab="tab-2">Regional Disease Breakout</li>
          <li class="tab-link" data-tab="tab-3">Treatment Plans</li>
      	</ul>

      	<div id="tab-1" class="tab-content current">
          <div align="center" class="filter">
            Disease <input type="text" name="disease" placeholder="Find Similar Disease" value="<?php echo $disease; ?>"/>
            Patient Age <input type="text" name="age" placeholder="Patient Age" value="<?php echo $age; ?>" />
            Gender <select name="gender" id="gender">
              <option value="M">Male</option>
              <option value="F">Female</option>
            </select>
            <input type="submit" value="Submit" />
          </div><br/>
          <div id="chartContainer" style="height: 420px; width: 100%;"></div>
      	</div>
        <div id="tab-2" class="tab-content">
          <div align="center">
            Location <input type="text" name="location" placeholder="Demographic Location" value="<?php echo $location; ?>"/>
            <input type="submit" value="Submit" name="tab2" id="tab2"/>
          </div><br/>
          <div id="chartContainer-2" style="height: 420px; width: 100%;"></div>
        </div>
      	<div id="tab-3" class="tab-content">
        <?php
        foreach ($medicationArr as $key => $value) {
          echo '<li>' . $value['Medication'] . ' Dosage for ' . $value['Quantity'] . '</li>';
        }
         ?>
      	</div>
      </div>
    </form>
  </body>
  <script type="text/javascript">
  document.getElementById('gender').value = "<?php echo $gender;?>";
  window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      exportEnabled: true,
      title:{
        text: ""
      },
      subtitles: [{
        text: "Medication Prescribed for Patients with similar Observation by other GP's"
      }],
      data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 12,
        indexLabel: "{label} - #percent%",
        yValueFormatString: "Prescribed to #,##0 Patients",
        dataPoints: <?php echo json_encode($fillGraphMedication, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();

    //Regional Disease Breakout
    var chart2 = new CanvasJS.Chart("chartContainer-2", {
      animationEnabled: true,
      exportEnabled: true,
      title:{
        text: ""
      },
      subtitles: [{
        text: "Common Observation for this Region"
      }],
      data: [{
        type: "pie",
        showInLegend: "true",
        legendText: "{label}",
        indexLabelFontSize: 12,
        indexLabel: "{label} - #percent%",
        yValueFormatString: "Prescribed to #,##0 Patients",
        dataPoints: <?php echo json_encode($fillGraphDisease, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart2.render();
  }
  </script>
</html>
