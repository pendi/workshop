@extends('skeleton')

<?php
	use Ghunti\HighchartsPHP\Highchart;
    use Ghunti\HighchartsPHP\HighchartJsExpr;
?>

@section('content')
	<style>
		.highcharts-credits { display: none; }
		.chart-border { border: 1px solid #cecece; }
	</style>

	<div class="wrapper">
		<h1 class="title">Statistics</h1>
		<div class="row">
			<?php foreach ($dataChart as $key => $value): ?>
				<div class="span-6">
	    			<div id="<?php echo str_replace(" ", "", $key) ?>" class="chart-border"></div>
				</div>
			<?php endforeach ?>
		</div>
	</div>

	<?php
		foreach ($dataChart as $key => $chartData) {
			$chartName = $key;
			$chart = str_replace(" ", "_", $key);
			$key = str_replace(" ", "", $key);
			
			$chart = new Highchart();

			$chart->chart->renderTo = $key;
			$chart->chart->plotBackgroundColor = null;
			$chart->chart->plotBorderWidth = null;
			$chart->chart->plotShadow = false;
			$chart->title->text = $chartName;

			$chart->tooltip->formatter = new HighchartJsExpr(
			    "function() {
			    return '<b>'+ this.point.name +'</b>: '+ Math.round(this.percentage*10)/10 +' %'; }");

			$chart->plotOptions->pie->allowPointSelect = 1;
			$chart->plotOptions->pie->cursor = "pointer";
			$chart->plotOptions->pie->dataLabels->enabled = false;
			$chart->plotOptions->pie->showInLegend = 1;
			$chart->series[] = array(
			    'type' => "pie",
			    'name' => "Browser share",
			    'data' => $chartData
			);


			$chart->printScripts();
			echo "<script type='text/javascript'>".$chart->render($key)."</script>";
		}
	?>

	
@endsection

@section('contextual')
@endsection
