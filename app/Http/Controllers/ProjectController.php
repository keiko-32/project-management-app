<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * 一覧画面を表示する
     */
    public function index(Request $request)
    {

        // 検索・絞り込み条件を取得
        $keyword = $request->input('keyword');  // 検索キーワードを取得
        $status = $request->input('status');    // ステータスを取得
        $userId = $request->input('user_id');   // 担当者IDを取得


        // 並び替え条件を取得（デフォルトは登作成日時の降順）
        // 選択肢・'amount'（案件金額）・ 'created_at'（作成日時）, 'updated_at'（更新日時）
        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');   // 選択肢 'asc'（昇順）, 'desc'（降順）

        // 許可する並び替え条件
        $allowedSortBy = ['amount', 'created_at', 'updated_at'];
        $allowedOrder = ['asc', 'desc'];  // 許可する並び順
        if (!in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = 'created_at';     // 不正な値なら初期値に戻す
        }

        if (!in_array($order, $allowedOrder, true)) {
            $order = 'desc';
        }

        // 検索条件を組み立てる
        $query = Project::query()->with(['customer', 'user']);

        //キーワードがあったら検索する
        $query->when($keyword, function ($query, $keyword) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%")  // 案件名を検索する
                    ->orWhereHas('customer', function ($customerQuery) use ($keyword) {
                        // 顧客名にキーワードが含まれるか調べる
                        $customerQuery->where('name', 'like', "%{$keyword}%");   // 顧客名を調べる
                    });
            });
        });

        // ステータスによる絞り込み
        $query->when($status, function ($query, $status) {
            return $query->where('status', $status);
        });

        // 担当者による絞り込み
        $query->when($userId, function ($query, $userId) {
            return $query->where('user_id', $userId);

        });


        // 並び替え・ページネーション
        $projects = $query->orderBy($sortBy, $order) // 選択された条件でソート
            ->paginate(10)  // 1ページ10件
            ->withQueryString();  // 検索・ソート条件を全てのURLに自動で維持する

        // 全件数を対象に数える
        $count = Project::count();

        // 条件付きで数える場合
        $projectCount = Project::where('status', '受注')->count();

        // '受注'の合計金額を計算
        $totalAmount = Project::where('status', '受注')
            ->sum('amount');

        // Bladeへデータを渡す
        return view('projects.index', compact('projects', 'count', 'projectCount', 'totalAmount', 'status', 'keyword', 'userId', 'sortBy', 'order'));

    }
    /**
     * 新規登録画面の表示
     */
    public function create()
    {
        return view('projects.create');
    }
    /**
     * 新規データの保存処理
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        // ログインユーザーを案件の担当者に設定
        $data['user_id'] = auth()->id();

        Project::create($data);

        // 一覧画面へ戻る
        return redirect()->route('projects.index')
            ->with('success', ' 案件を作成しました。');
    }
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        //権限がある場合のみ以下の処理を実行。なければ４０３エラー
        return view('projects.edit', compact('project'));
    }
    public function update(UpdateProjectRequest $request, Project $project)
    {
        // 'update'ポリシーを使い$projectに対する権限をチェック
        $this->authorize('update', $project);

        // バリデーション済みのデータを取得
        $validated = $request->validated();
        // 案件情報を更新
        $project->update($validated);

        // 一覧画面へ戻る
        return redirect()->route('projects.index')
            ->with('success', '案件を更新しました。');
    }

    public function show(Project $project)
    {
        // $projectにはすでに該当す１件のデータあり
        // 存在しないIDの場合、自動的に４０４エラー
        return view('projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        // 認可通った場合、以下の処理を実行
        $project->delete();

        // 一覧へ戻る
        return redirect()->route('projects.index')
            ->with('success', '削除しました。');
    }
}



