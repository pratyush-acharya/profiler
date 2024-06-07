<div>
    <h3>Result</h3>
    <table border=1>
        <tr>
            <th>Mid Term Status</th>
            <th>Mid Term Percentage</th>
            <th>Mid Term Attachment</th>
            <th>Final Term Status</th>
            <th>Final Term Percentage</th>
            <th>Final Term Attachment</th>
            <th>Board Status</th>
            <th>Board Percentage</th>
            <th>Board Attachment</th>
        </tr>
        @if($result != NULL)
        <tr>
            <td>{{$result->mid_status}}</td>
            <td>{{$result->mid_percentage}}</td>
            <td>{{$result->mid_attachment}}</td>

            <td>{{$result->final_status}}</td>
            <td>{{$result->final_percentage}}</td>
            <td>{{$result->final_attachment}}</td>

            <td>{{$result->board_status}}</td>
            <td>{{$result->board_percentage}}</td>
            <td>{{$result->board_attachment}}</td>
        </tr>
        @endif
    </table>
</div>
