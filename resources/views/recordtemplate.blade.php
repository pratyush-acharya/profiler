<html>
<head>
    <title>Student Record List</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        .pt-2{
            padding-top: 20px;
        }

        .pt-3{
            padding-top: 30px;
        }

        .pl-1{
            padding-left: 10px;
        }

        .pl-2{
            padding-left: 20px;
        }

        .container table{
            width: 700px;
        }

        .w-365{
            width: 285px;
        }

        table {
            border-collapse: collapse;
        }

        .pb-2{
            padding-bottom: 20px;
        }

        td{
            padding: 1 0;
        }
    </style>
</head>

<body>
    <div class="container" style="margin-left: 60px;">
        <table class="table">
            <tr>
                <th colspan=3 class="pb-2" style="padding-top: 65px;"><h1>Deerwalk Institute of Technology</h1></th>
            </tr>
            <tr>
                <td style="padding-top: 20px;"></td>
            </tr>
            <tr>
                <td>Name: {{ $studentDetail->name }}</td>
                <td></td>
                <td>Start Date: {{ $studentDetail->student->batch->start_date }}</td>
            </tr>
            <tr>
                <td>Email: {{ $studentDetail->email }}</td>
                <td></td>
                <td>End Date: {{ $studentDetail->student->batch->end_date }}</td>
            </tr>
            <tr>
                <td>Batch: {{ $studentDetail->student->batch->year }} ({{ $studentDetail->student->batch->stream }})</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding-top: 10px;"></td>
            </tr>
            @for($i=1; $i< 9; $i++)
            <tr>
                <th style="padding-top: 50px;" colspan=3><h3>Semester {{ $i }}</h3></th>
            </tr>
            @foreach($recordList as $rclist)
                @if($rclist->semester == $i)
                <tr>
                    <td colspan=3 class="pt-1"><h4>{{ $rclist->category->name }}<h4></td>
                </tr>
                <tr>
                    <td class="pl-2 w-365">{{ $rclist->comment }}</td>
                    <td>{{ ($rclist->start_date == NULL)? '' : 'Start-Date: '.$rclist->start_date }}</td>
                    <td>{{ ($rclist->end_date == NULL)? '' : 'End-Date: '.$rclist->end_date }}</td>
                </tr>
                @endif
            @endforeach
            <tr>
                <td colspan=3 class="pt-2"><h4>Result</h4></td>
            </tr>
            @foreach($resultList as $rlist)
                @if($rlist->semester == $i)
                <tr>
                    <td class="pl-2">Mid Term</td>
                    <td>{{ $rlist->mid_status }}</td>
                    <td>{{ $rlist->mid_percentage }} %</td>
                </tr>
                <tr>
                    <td class="pl-2">Final Term</td>
                    <td>{{ $rlist->final_status }}</td>
                    <td>{{ $rlist->final_percentage }} %</td>
                </tr>
                <tr>
                    <td class="pl-2">Board</td>
                    <td>{{ $rlist->board_status }}</td>
                    <td>{{ $rlist->board_percentage }} %</td>
                </tr>
                @endif
            @endforeach
            @endfor
        </table>
    </div>
</body>
</html>