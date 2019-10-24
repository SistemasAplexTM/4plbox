<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manifiesto Interno</title>
</head>

<body>
    <table class="content" border="1">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ date("F j, Y") }}</td>
                <td>MANIFIESTO INTERNO</td>
                <td></td>
                <td>PAID</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>MASTER</td>
                <td>7774878878</td>
                <td></td>
                <td></td>
                <td>NO PAID</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>PESO</td>
                <td>158</td>
                <td></td>
                <td></td>
                <td>BOGOTA</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>PIEZAS</td>
                <td>7</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <th>#</th>
                <th>ESTATUS</th>
                <th>GUIA</th>
                <th>NOMBRE</th>
                <th>CIUDAD</th>
                <th>DIRECCION</th>
                <th>OBSERVACION</th>
            </tr>
        </thead>
        <tbody>
            @php
            $cont = 1;
            @endphp
            @foreach ($data as $item)
            @if(count($item->groups) > 0)
            <tr>
                <td></td>
                <td></td>
                <td>{{ substr($item->num_warehouse, 2) }}</td>
                <td colspan="4">CONSOLIDADO</td>
            </tr>
            @foreach ($item->groups as $it)
            <tr>
                <td>{{ $cont++ }}</td>
                <td>{{ $it->id }}</td>
                <td>{{ substr($it->num_warehouse, 2) }}</td>
                <td>{{ $it->nombre_full }}</td>
                <td>{{ $it->nombre }}</td>
                <td>{{ $it->direccion }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="7"></td>
            </tr>
            @else
            <tr>
                <td>{{ $cont++ }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ substr($item->num_warehouse, 2) }}</td>
                <td>{{ $item->nombre_full }}</td>
                <td>{{ $item->nombre }}</td>
                <td>{{ $item->direccion }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>