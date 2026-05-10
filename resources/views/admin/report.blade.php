<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        /* Márgenes del PDF eliminados para aprovechar todo el ancho de página */
        @page {
            margin: 0cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #161b22;
            color: #c9d1d9;
            margin: 0;
            padding: 0rem;
        }

        /* Títulos principales y de sección */
        h1 {
            color: #9683ec;
            text-align: center;
            border-bottom: 2px solid #9683ec;
            padding-bottom: 0.5rem;
        }

        h2 {
            color: #9683ec;
            margin-top: 2rem;
            border-left: 4px solid #9683ec;
            padding-left: 0.5rem;
        }

        /* Tabla de estadísticas en cuadrícula 3x2 */
        .stats-grid {
            width: 100%;
            margin: 1rem 0;
            border-collapse: collapse;
        }

        .stats-grid td {
            background-color: #0d1117;
            border: 1px solid #30363d;
            padding: 0.75rem 1rem;
            text-align: center;
            width: 33%;
        }

        .stats-grid .number {
            font-size: 2rem;
            color: #9683ec;
            font-weight: bold;
        }

        .stats-grid .label {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        /* Tablas de rankings (top games y top developers) */
        table.list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table.list th {
            background-color: #9683ec;
            color: #0d0d14;
            padding: 0.5rem 1rem;
            text-align: left;
        }

        table.list td {
            background-color: #0d1117;
            border-bottom: 1px solid #30363d;
            padding: 0.5rem 1rem;
        }

        .footer {
            text-align: center;
            margin-top: 3rem;
            font-size: 0.75rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <h1>IndieGameConnect — Admin Report</h1>
    <p style="text-align: center; opacity: 0.6;">Generated on {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Platform Statistics</h2>
    <table class="stats-grid">
        <tr>
            <td>
                <div class="number">{{ $totalUsers }}</div>
                <div class="label">Total Users</div>
            </td>
            <td>
                <div class="number">{{ $totalGames }}</div>
                <div class="label">Total Games</div>
            </td>
            <td>
                <div class="number">{{ $totalPosts }}</div>
                <div class="label">Total Posts</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="number">{{ $newUsersThisMonth }}</div>
                <div class="label">New Users this month</div>
            </td>
            <td>
                <div class="number">{{ $newGamesThisMonth }}</div>
                <div class="label">New Games this month</div>
            </td>
            <td>
                <div class="number">{{ now()->format('M Y') }}</div>
                <div class="label">Report period</div>
            </td>
        </tr>
    </table>

    {{-- Top 5 juegos por número de seguidores --}}
    <h2>Top 5 Games by Followers</h2>
    <table class="list">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Followers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topGames as $i => $game)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $game->title }}</td>
                    <td>{{ $game->follows_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Top 5 developers por número de seguidores --}}
    <h2>Top 5 Developers by Followers</h2>
    <table class="list">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Followers</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topDevelopers as $i => $developer)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $developer->name }}</td>
                    <td>{{ $developer->follows_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        IndieGameConnect &copy; {{ date('Y') }} — Confidential Admin Report
    </div>
</body>
</html>