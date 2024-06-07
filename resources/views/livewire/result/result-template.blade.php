<!DOCTYPE html>
<html>
<head>
	<title>Marksheet</title>
	<style type="text/css">
		*{
			margin: 0;
			padding: 0;
			font-family: arial;
			font-size: 14px;
		}

        .container{
            padding: 50px;
        }

		table{
			border-collapse: collapse; 
		}

		table td{
			padding: 2px 5px;
		}

		table th{
			font-size: 14px;
		}

		.text-center{
			text-align: center;
		}

		.text-left{
			text-align: left;
		}

		.w-100{
			width: 100%;
		}

		.w-mark{
			width: 80px;
		}

		.w-subject{
			width: 300px;
		}

		.font-size-small{
			font-size: 14px;
		}

		.pl-1{
			padding-left: 2px;
		}

		.mt-30{
			margin-top: 30px;
		}

		.mt-20{
			margin-top: 20px;
		}

		.decorate-table tr:nth-child(odd) {
        	background-color: #cecdcd;
        }

        .decorate-table tr:nth-child(even) {
        	border-top: 1px solid black;
        	border-bottom: 1px solid black;
        }

        .decorate-table tr:nth-child(odd) td {
        	border-right: 0.1px solid rgba(255, 255, 255, .2);;
        	border-left: 0.1px solid rgba(255, 255, 255, .2);;
        }

        .decorate-table tr:nth-child(even) td {
        	border-top: 1px solid black;
        	border-bottom: 1px solid black;
        }
        
        .decorate-table tr:nth-child(even) th {
        	border-top: 1px solid black;
        	border-bottom: 1px solid black;
        }

        .decorate-table tr td, .decorate-table tr th{
        	padding: 7px 10px;
        }


        .decorate-table{
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        .content td{
        	padding-top: 15px; 
        }

        .placeholder{
        	border-top: dashed 1.5px black;
        	border-bottom: none;
        	width: 200px; 
        }
	</style>
</head>
<body>
<div class="container" style="width: 700px;">
	<table class="w-100">
		<tr>
			<td class="text-center"><img src="{{ public_path('/images/dwitLogo.png') }}" width="110"></td>
		</tr>
		
		<tr>
			<td class="text-center" style="padding-top: 20px;">School of Computer Science & Infromation Technology</td>
		</tr>
		<tr>
			<td class="text-center"><b>MARK SHEET</b></td>
		</tr>
		<tr>
			<td class="text-center">{{ $examType }} EXAMINATIONS {{ $examDate }}</td>
		</tr>
	</table>

	<table class="w-100 mt-30">
		<tr>
			<td style="width: 300px;">DATE: <b>{{ $publishDate }}</b></td>
			<td style="width: 120px;">ROLL NO: <b>{{ $roll }}</b></td>
			<td style="width: 260px; text-align: right;">NAME: <b>{{ $name }}</b></td>
		</tr>
		<tr>
			<td style="padding-top: 10px; padding-bottom: 20px;">CLASS: <b>{{$grade}}</b></td>
		</tr>
	</table>

	<table class="w-100 decorate-table">
		<tr>
			<th class="text-left w-subject">SUBJECTS</th>
			<th class="w-mark">FULL MARKS</th>
			<th class="w-mark">PASS MARKS</th>
			<th class="w-mark">OBTAINED MARKS</th>
			<th class="w-mark">STATUS</th>
		</tr>

        @for($i = 1; $i <= $subjectCount; $i++)
		<tr>
			<th class="text-left font-size-small pl-1">{{ $title[$i]}}</th>
			<td class="text-center">{{ $fullMarks[$i] }}</td>
			<td class="text-center">{{ $passMarks[$i] }}</td>
			<td class="text-center">{{ $myMarks[$i] }}</td>
			<td class="text-center">@if($myMarks[$i] >= $passMarks[$i]) PASS @else FAIL @endif</td>
		</tr>
        @endfor
	</table>

	<table class="w-100 mt-30 content">
		<tr>
			<td><b>NOTE: </b> Student must pass in all the subjects in order to pass the examination.</td>
		</tr>
		<tr>
			<td><span>TOTAL AGGREGATE PERCENTAGE: <b>{{ $percentage }} %</b></span> <span style="margin-left: 210px;">OVERALL STATUS: <b>{{$status}}</b></span></td>
		</tr>
		<tr>
			<td><b>NOTE:</b> Student must meet minimum of 90% attendance to be eligible to appear for final examinations.</td>
		</tr>
		<tr>
			<td><b>RANKING CRITERIA</b></td>
		</tr>
		<tr>
			<td style="padding-top: 5px;">40% &amp; ABOVE - IIIRD DIVISION | 55% &amp; ABOVE - IIND DIVISION | 70% &amp; ABOVE - IST DIVISION | 80% &amp; ABOVE - DISTINCTION</td>
		</tr>
	</table>

	<table class="w-100 mt-20">
		<tr>
			<td><img src="{{ public_path('/images/signature.png') }}"></td>
		</tr>
		<tr>
			<td style="padding-top: 0;"><hr class="placeholder"></td>
		</tr>
		<tr>
			<td><b>Mr. BIJAY BABU REGMI</b></td>
		</tr>
		<tr>
			<td><b>ASSOCIATE DIRECTOR OF ACADEMICS OPERATIONS</b></td>
		</tr>
	</table>
</div>
</body>
</html>