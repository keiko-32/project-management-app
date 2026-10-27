<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>案件一覧</title>
</head>

<body>
    <h1>案件一覧</h1>
    <form action="{{  route('projects.index') }}" method="GET">
        <input type="text" name="keyword" value="{{  request('keyword') }}" placeholder="キーワード">
        <button type="submit">検索</button>

        @if(request('keyword'))
            <a href="{{ route('projects.index') }}">リセット</a>
        @endif
    </form>
    <!-- 検索結果/ 一覧リスト -->
    <table>
        <thead>
            <tr>
                <th>案件名</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>

                    <td>{{ $project->name }}</td>
                </tr>
            @empty
                <tr>
                    <td>該当するデータはありません</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ページネーションのリンク維持（検索条件を引き継ぐ） -->
    {{ $projects->links() }}


</body>

</html>