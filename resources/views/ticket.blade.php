<!DOCTYPE html>
<html>
<head></head>
<body>
<style>
    table {
        border: 1px solid black;
    }
    th,td {
        border: 1px solid black;
    }
</style>
@isset($matrix)
    <table>
        @foreach($matrix as $key => $value)
            <tr>
                <th>{{$key}}</th>
                @for($i=0;$i<count($value);$i++)
                    <td>{{$value[$i]}}</td>
                @endfor
            </tr>
        @endforeach
    </table>
@endisset
@isset($normmatrix)
    <table>
        @foreach($normmatrix as $key => $value)
            <tr>
                <th>{{$key}}</th>
                @for($i=0;$i<count($value);$i++)
                    <td>{{$value[$i]}}</td>
                @endfor
            </tr>
        @endforeach
    </table>
@endisset
@isset($similarmatrix)
    <table>
        @foreach($similarmatrix as $key => $value)
            <tr>
                <th>{{$key}}</th>
                @for($i=0;$i<count($value);$i++)
                    <td>{{$value[$i]}}</td>
                @endfor
            </tr>
        @endforeach
    </table>
@endisset
@isset($normmatrixFull)
    <table>
        @foreach($normmatrixFull as $key => $value)
            <tr>
                <th>{{$key}}</th>
                @for($i=0;$i<count($value);$i++)
                    <td>{{$value[$i]}}</td>
                @endfor
            </tr>
        @endforeach
    </table>
@endisset
@isset($matrixFinal)
    <table>
        @foreach($matrixFinal as $key => $value)
            <tr>
                <th>{{$key}}</th>
                @for($i=0;$i<count($value);$i++)
                    <td>{{$value[$i]}}</td>
                @endfor
            </tr>
        @endforeach
    </table>
@endisset
</body>
</html>
